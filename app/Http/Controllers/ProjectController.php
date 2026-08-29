<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\MaintenanceSubscription;
use App\Services\WhatsApp\FlustraWhatsAppService;
use App\Services\WhatsApp\WhatsAppTemplates;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected FlustraWhatsAppService $waService;

    public function __construct(FlustraWhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $query = Project::with(['lead', 'payments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('paket')) {
            $query->where('paket', $request->paket);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_project', 'like', "%{$q}%")
                    ->orWhereHas('lead', function ($leadSub) use ($q) {
                        $leadSub->where('nama_usaha', 'like', "%{$q}%")
                            ->orWhere('nama_kontak', 'like', "%{$q}%")
                            ->orWhere('kontak_wa', 'like', "%{$q}%");
                    });
            });
        }

        $projects = $query->latest()->paginate(12)->withQueryString();

        $statusCounts = [
            'all' => Project::count(),
            'draft' => Project::where('status', 'draft')->count(),
            'dp_diterima' => Project::where('status', 'dp_diterima')->count(),
            'dikerjakan' => Project::where('status', 'dikerjakan')->count(),
            'review' => Project::where('status', 'review')->count(),
            'selesai' => Project::where('status', 'selesai')->count(),
        ];

        return view('projects.index', compact('projects', 'statusCounts'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'nama_project' => 'required|string|max:255',
            'paket' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'link_website' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)->with('success', "Project {$project->nama_project} berhasil dibuat.");
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $project->load(['lead.messageLogs', 'payments', 'maintenanceSubscription']);

        return view('projects.show', compact('project'));
    }

    /**
     * Update the specified project details.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'nama_project' => 'required|string|max:255',
            'paket' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'link_website' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $project->update($validated);

        return back()->with('success', "Project {$project->nama_project} berhasil diperbarui.");
    }

    /**
     * Update Project Status with Automated WhatsApp Trigger (Moment 1 & Moment 2).
     */
    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:draft,dp_diterima,dikerjakan,review,selesai,dibatalkan',
            'link_website' => 'nullable|string|max:255',
            'send_wa' => 'nullable|boolean',
            'dp_amount' => 'nullable|numeric',
            'create_maintenance' => 'nullable|boolean',
        ]);

        $newStatus = $request->status;
        $oldStatus = $project->status;
        $sendWa = $request->boolean('send_wa', true);
        $lead = $project->lead;

        $project->status = $newStatus;

        if ($request->filled('link_website')) {
            $project->link_website = $request->link_website;
        }

        if ($newStatus === 'selesai' && !$project->tanggal_selesai) {
            $project->tanggal_selesai = now()->toDateString();
        }

        $project->save();

        $waNotificationSent = false;
        $waMessageSummary = '';

        // =========================================================================
        // AUTOMATION TRIGGER 1: Status diubah ke DP_DITERIMA
        // =========================================================================
        if ($newStatus === 'dp_diterima' && $oldStatus !== 'dp_diterima') {
            // Optional: Record DP payment if specified or if no payments exist
            if ($request->filled('dp_amount') && $request->dp_amount > 0) {
                Payment::create([
                    'project_id' => $project->id,
                    'jenis' => 'dp',
                    'jumlah' => $request->dp_amount,
                    'status' => 'lunas',
                    'tanggal' => now()->toDateString(),
                    'catatan' => 'Uang Muka (DP) pengerjaan project.',
                ]);
            }

            if ($sendWa) {
                $msg = WhatsAppTemplates::dpReceived($lead, $project);
                $res = $this->waService->sendWhatsApp(
                    to: $lead->kontak_wa,
                    message: $msg,
                    lead: $lead,
                    tipePesan: 'invoice_dp',
                    isAutomated: true // Enforce deal status guardrail
                );

                if ($res['success'] ?? false) {
                    $waNotificationSent = true;
                    $waMessageSummary = "WhatsApp konfirmasi DP & pengerjaan berhasil dikirim ke {$lead->kontak_wa}.";
                }
            }
        }

        // =========================================================================
        // AUTOMATION TRIGGER 2: Status diubah ke SELESAI
        // =========================================================================
        if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
            // Optional: Create Maintenance Subscription if requested
            if ($request->boolean('create_maintenance', false)) {
                MaintenanceSubscription::firstOrCreate(
                    ['lead_id' => $lead->id, 'project_id' => $project->id],
                    [
                        'harga_bulanan' => config('flustra.default_maintenance_price', 150000),
                        'status' => 'aktif',
                        'tanggal_mulai' => now()->toDateString(),
                        'tanggal_jatuh_tempo_berikutnya' => now()->addMonth()->toDateString(),
                        'catatan' => "Langganan pemeliharaan untuk project {$project->nama_project}.",
                    ]
                );
            }

            if ($sendWa) {
                $msg = WhatsAppTemplates::projectCompleted($lead, $project, $project->link_website);
                $res = $this->waService->sendWhatsApp(
                    to: $lead->kontak_wa,
                    message: $msg,
                    lead: $lead,
                    tipePesan: 'project_selesai',
                    isAutomated: true // Enforce deal status guardrail
                );

                if ($res['success'] ?? false) {
                    $waNotificationSent = true;
                    $waMessageSummary = "WhatsApp pemberitahuan website live + proposal maintenance berhasil dikirim ke {$lead->kontak_wa}.";
                }
            }
        }

        $flashMessage = "Status project {$project->nama_project} diubah menjadi: {$project->status_label}.";
        if ($waNotificationSent) {
            $flashMessage .= " " . $waMessageSummary;
        }

        return back()->with('success', $flashMessage);
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $nama = $project->nama_project;
        $project->delete();

        return redirect()->route('projects.index')->with('success', "Project {$nama} berhasil dihapus.");
    }
}
