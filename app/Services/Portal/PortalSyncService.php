<?php

namespace App\Services\Portal;

use App\Models\Project;
use App\Models\Lead;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortalSyncService
{
    protected string $syncUrl;
    protected string $secretToken;

    public function __construct()
    {
        try {
            $settings = \App\Models\CompanySetting::get();
            $this->syncUrl = !empty($settings->portal_sync_url) ? $settings->portal_sync_url : config('services.portal.sync_url', 'https://portalclient.rzdigitalcreative.my.id/api/internal/v1/sync-client-project');
            $this->secretToken = !empty($settings->portal_sync_secret) ? $settings->portal_sync_secret : config('services.portal.sync_secret', 'rz_portal_sync_secret_key_2026');
        } catch (\Throwable $e) {
            $this->syncUrl = config('services.portal.sync_url', 'https://portalclient.rzdigitalcreative.my.id/api/internal/v1/sync-client-project');
            $this->secretToken = config('services.portal.sync_secret', 'rz_portal_sync_secret_key_2026');
        }
    }

    /**
     * Synchronize a CRM project and its client to the Portal Client system.
     */
    public function syncProject(Project $project, bool $sendWaInvite = true): array
    {
        if (\App\Services\WhatsApp\FlustraWhatsAppService::isTestEnvironment()) {
            return [
                'success' => true,
                'message' => 'Simulated portal sync in testing environment',
                'data' => [
                    'project_id' => 999,
                    'user_id' => 999,
                    'paid_amount' => 0,
                ],
            ];
        }

        $lead = $project->lead;
        if (!$lead) {
            return [
                'success' => false,
                'message' => 'Data prospek/lead tidak ditemukan untuk proyek ini.',
            ];
        }

        // Generate client email if not present
        $clientEmail = $lead->email;
        if (empty($clientEmail)) {
            $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $lead->nama_usaha ?: 'klien'));
            $clientEmail = $cleanName . '@client.rzdigitalcreative.my.id';
            $lead->update(['email' => $clientEmail]);
        }

        $invoiceNumber = 'INV/' . $project->created_at->format('Ym') . '/' . str_pad($project->id, 4, '0', STR_PAD_LEFT);

        $settings = \App\Models\CompanySetting::get();

        $payload = [
            'client_name' => $lead->nama_kontak ?: $lead->nama_usaha,
            'client_email' => $clientEmail,
            'client_phone' => $lead->kontak_wa,
            'project_name' => $project->nama_project,
            'project_description' => "Paket: {$project->paket_label} — Nilai: Rp " . number_format($project->harga, 0, ',', '.') . ($project->catatan ? " ({$project->catatan})" : ''),
            'start_date' => $project->tanggal_mulai?->toDateString() ?: now()->toDateString(),
            'end_date' => $project->tanggal_selesai?->toDateString(),
            'link_website' => $project->link_website,
            'send_wa_invite' => $sendWaInvite,
            'amount' => (int) $project->harga,
            'paid_amount' => (int) $project->total_paid,
            'balance_due' => (int) $project->remaining_balance,
            'invoice_number' => $invoiceNumber,
            'payment_status' => $project->payment_status,
            'project_status' => $project->status,
            'company_settings' => [
                'company_name' => $settings->company_name,
                'brand_name' => $settings->brand_name,
                'bank_name' => $settings->bank_name,
                'bank_account_number' => $settings->bank_account_number,
                'bank_account_holder' => $settings->bank_account_holder,
                'phone_support' => $settings->phone_support,
                'phone_admin_alerts' => $settings->phone_admin_alerts,
                'email_internal_alert' => $settings->email_internal_alert,
                'wa_api_url' => $settings->wa_api_url,
                'wa_api_key' => $settings->wa_api_key,
            ],
        ];

        $activeSubscription = $project->activeSubscription;
        if ($activeSubscription) {
            $payload['subscription'] = [
                'tipe' => $activeSubscription->tipe,
                'harga' => (int) $activeSubscription->harga,
                'tanggal_mulai' => $activeSubscription->tanggal_mulai->toDateString(),
                'tanggal_expired' => $activeSubscription->tanggal_expired->toDateString(),
                'status' => $activeSubscription->status,
                'auto_renew' => $activeSubscription->auto_renew,
            ];
        }

        try {
            $response = Http::withHeaders([
                'X-Internal-Secret' => $this->secretToken,
                'Accept' => 'application/json',
            ])->timeout(10)->post($this->syncUrl, $payload);

            if ($response->successful()) {
                $body = $response->json();
                if ($body['success'] ?? false) {
                    $resData = $body['data'] ?? [];
                    
                    $project->update([
                        'portal_project_id' => $resData['project_id'] ?? null,
                        'portal_user_id' => $resData['user_id'] ?? null,
                        'synced_to_portal_at' => now(),
                    ]);

                    // Two-way financial reconciliation: If Portal has verified payment, bring it into CRM
                    $portalPaidAmount = (float) ($resData['paid_amount'] ?? 0);
                    $crmTotalPaid = (float) $project->total_paid;
                    if ($portalPaidAmount > $crmTotalPaid) {
                        $diff = $portalPaidAmount - $crmTotalPaid;
                        $isDp = ($crmTotalPaid == 0 && $portalPaidAmount < $project->harga);
                        \App\Models\Payment::create([
                            'project_id' => $project->id,
                            'jenis'      => $isDp ? 'dp' : 'pelunasan',
                            'jumlah'     => $diff,
                            'status'     => 'lunas',
                            'tanggal'    => now(),
                            'catatan'    => 'Disinkronkan otomatis dari verifikasi Portal Client (Sinkron Ulang)',
                        ]);

                        if ($isDp && in_array($project->status, ['draft', 'pending'])) {
                            $project->status = 'dp_diterima';
                            $project->save();
                        }
                    }

                    ActivityLogger::log(
                        'portal_synced',
                        "Sinkronisasi proyek {$project->nama_project} ke Portal Klien berhasil (Portal Project #{$project->portal_project_id})",
                        'Project',
                        $project->id
                    );

                    return [
                        'success' => true,
                        'message' => $body['message'] ?? 'Proyek dan akun klien berhasil disinkronkan ke Portal!',
                        'data' => $resData,
                    ];
                }
            }

            $errMsg = $response->json('error') ?? $response->json('message') ?? 'Server Portal merespons dengan HTTP ' . $response->status();
            Log::warning("PortalSyncService Failed [HTTP {$response->status()}]: {$errMsg}");

            return [
                'success' => false,
                'message' => "Gagal sinkronisasi ke Portal: {$errMsg}",
            ];
        } catch (\Throwable $e) {
            Log::error("PortalSyncService Exception: " . $e->getMessage());

            return [
                'success' => false,
                'message' => "Koneksi ke Portal Client bermasalah: " . $e->getMessage(),
            ];
        }
    }
}
