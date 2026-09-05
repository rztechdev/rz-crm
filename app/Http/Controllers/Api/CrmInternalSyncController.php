<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\CompanySetting;
use App\Services\ActivityLogger;
use App\Services\WhatsApp\FlustraWhatsAppService;
use App\Services\WhatsApp\WhatsAppTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CrmInternalSyncController extends Controller
{
    /**
     * Handle two-way sync from Portal Client Kanban to CRM.
     */
    public function syncFromPortal(Request $request)
    {
        // 1. Authenticate with Bearer token
        $token = $request->bearerToken();
        $expectedToken = 'rz_portal_sync_secret_key_2026';
        try {
            $settings = CompanySetting::get();
            if (!empty($settings->portal_sync_secret)) {
                $expectedToken = $settings->portal_sync_secret;
            }
        } catch (\Throwable $e) {}

        if (!$token || !hash_equals($expectedToken, $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized token.',
            ], 401);
        }

        // 2. Validate payload
        $validated = $request->validate([
            'project_id'    => 'nullable|integer',
            'project_name'  => 'required|string',
            'kanban_status' => 'required|in:todo,in_progress,review,done',
            'link_website'  => 'nullable|string',
            'send_wa'       => 'nullable|boolean',
        ]);

        // 3. Find matching CRM project
        $project = null;
        if (!empty($validated['project_id'])) {
            $project = Project::where('portal_project_id', $validated['project_id'])->first();
        }
        if (!$project) {
            $project = Project::where('nama_project', $validated['project_name'])->first();
        }
        if (!$project) {
            $project = Project::where('nama_project', 'like', '%' . $validated['project_name'] . '%')->first();
        }
        if (!$project && !empty($validated['project_id'])) {
            $project = Project::find($validated['project_id']);
        }

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Proyek tidak ditemukan di CRM.',
            ], 404);
        }

        // Link portal_project_id if not yet set
        if (empty($project->portal_project_id) && !empty($validated['project_id'])) {
            $project->portal_project_id = $validated['project_id'];
            $project->synced_to_portal_at = now();
        }

        // 4. Map Kanban status to CRM status
        $newCrmStatus = match($validated['kanban_status']) {
            'in_progress' => 'dikerjakan',
            'review'      => 'review',
            'done'        => 'selesai',
            'todo'        => in_array($project->status, ['dikerjakan', 'review']) ? 'draft' : $project->status,
        };

        $oldStatus = $project->status;
        $statusChanged = ($oldStatus !== $newCrmStatus);

        // 5. Update website link if provided
        if (!empty($validated['link_website'])) {
            $project->link_website = $validated['link_website'];
        }

        if ($newCrmStatus === 'selesai' && !$project->tanggal_selesai) {
            $project->tanggal_selesai = now()->toDateString();
        }

        $project->status = $newCrmStatus;
        $project->save();

        ActivityLogger::log(
            'project_kanban_sync',
            "Status proyek #{$project->id} disinkronkan dari Kanban Portal: [{$oldStatus} -> {$newCrmStatus}]" . ($project->link_website ? " (Link: {$project->link_website})" : ''),
            $project
        );

        // 6. Trigger WhatsApp notification if requested
        $sendWa = $request->boolean('send_wa', true);
        $waResult = false;
        $waError = null;

        if ($sendWa && $project->lead && !empty($project->lead->kontak_wa)) {
            $lead = $project->lead;
            $msg = match ($newCrmStatus) {
                'dikerjakan' => WhatsAppTemplates::projectInProgress($lead, $project),
                'review'     => WhatsAppTemplates::projectReview($lead, $project),
                'selesai'    => WhatsAppTemplates::projectCompleted($lead, $project, $project->link_website),
                'draft'      => WhatsAppTemplates::projectStatusUpdated($lead, $project),
                default      => null,
            };

            if ($msg) {
                try {
                    $waService = app(FlustraWhatsAppService::class);
                    $res = $waService->sendWhatsApp(
                        to: $lead->kontak_wa,
                        message: $msg,
                        lead: $lead,
                        tipePesan: 'project_status_' . $newCrmStatus,
                        isAutomated: false
                    );
                    $waResult = $res['success'] ?? false;
                    if (!$waResult) {
                        $waError = $res['message'] ?? 'Gagal mengirim pesan via WhatsApp Gateway';
                    }
                } catch (\Throwable $e) {
                    $waError = $e->getMessage();
                    Log::error('Gagal mengirim WA dari Kanban sync: ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success'      => true,
            'message'      => "Status proyek di CRM berhasil disinkronkan ke '{$project->status_label}'." . ($waResult ? " Notifikasi WhatsApp terkirim ke klien." : ''),
            'crm_status'   => $project->status,
            'link_website' => $project->link_website,
            'wa_sent'      => $waResult,
            'wa_error'     => $waError,
        ]);
    }

    /**
     * Handle payment verification sync from Portal Client to CRM.
     */
    public function syncPaymentFromPortal(Request $request)
    {
        // 1. Authenticate with Bearer token
        $token = $request->bearerToken();
        $expectedToken = 'rz_portal_sync_secret_key_2026';
        try {
            $settings = CompanySetting::get();
            if (!empty($settings->portal_sync_secret)) {
                $expectedToken = $settings->portal_sync_secret;
            }
        } catch (\Throwable $e) {}

        if (!$token || !hash_equals($expectedToken, $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized token.',
            ], 401);
        }

        // 2. Validate payload
        $validated = $request->validate([
            'project_name' => 'required|string',
            'jenis'        => 'required|in:dp,pelunasan,penuh',
            'jumlah'       => 'required|numeric|min:1',
            'catatan'      => 'nullable|string',
            'verified_by'  => 'nullable|string',
        ]);

        // 3. Find CRM project
        $project = Project::where('nama_project', $validated['project_name'])->first();
        if (!$project) {
            $project = Project::where('nama_project', 'like', '%' . $validated['project_name'] . '%')->first();
        }

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Proyek tidak ditemukan di CRM.',
            ], 404);
        }

        $paymentType = $validated['jenis'] === 'dp' ? 'dp' : 'pelunasan';

        // 4. Create Payment record in CRM
        $payment = \App\Models\Payment::create([
            'project_id' => $project->id,
            'jenis'      => $paymentType,
            'jumlah'     => $validated['jumlah'],
            'status'     => 'lunas',
            'tanggal'    => now(),
            'catatan'    => $validated['catatan'] ?: "Diverifikasi dari Portal Client" . (!empty($validated['verified_by']) ? " oleh {$validated['verified_by']}" : ''),
        ]);

        // 5. Update CRM project status if needed
        if ($paymentType === 'dp' && in_array($project->status, ['draft', 'pending'])) {
            $project->status = 'dp_diterima';
            $project->save();
        }

        ActivityLogger::log(
            'payment_portal_sync',
            "Pembayaran {$paymentType} sebesar Rp " . number_format($validated['jumlah'], 0, ',', '.') . " disinkronkan dari verifikasi Portal Client.",
            $project
        );

        return response()->json([
            'success'     => true,
            'message'     => "Pembayaran {$paymentType} berhasil dicatat di CRM.",
            'payment_id'  => $payment->id,
            'crm_status'  => $project->status,
            'total_paid'  => $project->total_paid,
            'sisa_tagihan'=> $project->sisa_tagihan,
        ]);
    }
}
