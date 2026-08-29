<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\MessageLog;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of leads with filters.
     */
    public function index(Request $request)
    {
        $query = Lead::with(['projects', 'activeMaintenanceSubscription']);

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Sumber
        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        // Filter by Paket
        if ($request->filled('paket')) {
            $query->where('paket_diminati', $request->paket);
        }

        // Filter Overdue Follow-ups
        if ($request->filter === 'overdue') {
            $query->whereNotNull('follow_up_date')
                ->where('follow_up_date', '<', now()->toDateString())
                ->whereNotIn('status', ['deal', 'tidak_lanjut']);
        } elseif ($request->filter === 'today') {
            $query->where('follow_up_date', now()->toDateString())
                ->whereNotIn('status', ['deal', 'tidak_lanjut']);
        }

        // Search Keyword (Nama Usaha, Kontak, WA)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_usaha', 'like', "%{$q}%")
                    ->orWhere('nama_kontak', 'like', "%{$q}%")
                    ->orWhere('kontak_wa', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'follow_up') {
            $query->orderByRaw('CASE WHEN follow_up_date IS NULL THEN 1 ELSE 0 END, follow_up_date ASC');
        } else {
            $query->latest();
        }

        $leads = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Lead::count(),
            'belum_dihubungi' => Lead::where('status', 'belum_dihubungi')->count(),
            'sudah_chat' => Lead::where('status', 'sudah_chat')->count(),
            'nego' => Lead::where('status', 'nego')->count(),
            'deal' => Lead::where('status', 'deal')->count(),
            'tidak_lanjut' => Lead::where('status', 'tidak_lanjut')->count(),
            'overdue' => Lead::whereNotNull('follow_up_date')->where('follow_up_date', '<', now()->toDateString())->whereNotIn('status', ['deal', 'tidak_lanjut'])->count(),
        ];

        return view('leads.index', compact('leads', 'statusCounts'));
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'nama_kontak' => 'nullable|string|max:255',
            'kontak_wa' => 'required|string|max:50',
            'sumber' => 'required|string',
            'status' => 'required|string',
            'paket_diminati' => 'required|string',
            'catatan' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $lead = Lead::create($validated);

        // If directly created with Deal status, automatically create the project
        if ($lead->status === 'deal') {
            $this->createInitialProjectForDeal($lead);
        }

        return redirect()->route('leads.show', $lead)->with('success', "Lead {$lead->nama_usaha} berhasil ditambahkan.");
    }

    /**
     * Display the specified lead details with chat timeline & projects.
     */
    public function show(Lead $lead)
    {
        $lead->load(['projects.payments', 'maintenanceSubscriptions', 'messageLogs']);

        return view('leads.show', compact('lead'));
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'nama_kontak' => 'nullable|string|max:255',
            'kontak_wa' => 'required|string|max:50',
            'sumber' => 'required|string',
            'status' => 'required|string',
            'paket_diminati' => 'required|string',
            'catatan' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $oldStatus = $lead->status;
        $lead->update($validated);

        // If status changed to Deal and no project exists yet, auto-create project
        if ($oldStatus !== 'deal' && $lead->status === 'deal' && $lead->projects()->count() === 0) {
            $this->createInitialProjectForDeal($lead);
        }

        return back()->with('success', "Data lead {$lead->nama_usaha} berhasil diperbarui.");
    }

    /**
     * Convert Lead to Deal (Action button).
     */
    public function convertToDeal(Request $request, Lead $lead)
    {
        $lead->update([
            'status' => 'deal',
            'follow_up_date' => null,
        ]);

        $project = $this->createInitialProjectForDeal($lead, $request->get('nama_project'), $request->get('harga'));

        return redirect()->route('projects.show', $project)->with('success', "🎉 Selamat! Lead berhasil ditandai Deal dan Project baru telah dibuat.");
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead)
    {
        $nama = $lead->nama_usaha;
        $lead->delete();

        return redirect()->route('leads.index')->with('success', "Lead {$nama} berhasil dihapus.");
    }

    /**
     * Helper to create the initial project when a lead becomes a Deal.
     */
    protected function createInitialProjectForDeal(Lead $lead, ?string $customProjectName = null, ?int $customPrice = null): Project
    {
        $packageKey = $lead->paket_diminati !== 'belum_tahu' ? $lead->paket_diminati : 'landing_page';
        $packages = config('flustra.packages', []);
        $defaultPrice = $packages[$packageKey]['price'] ?? 499000;

        return Project::create([
            'lead_id' => $lead->id,
            'nama_project' => $customProjectName ?: "Website {$lead->nama_usaha}",
            'paket' => $packageKey,
            'harga' => $customPrice ?: $defaultPrice,
            'status' => 'draft',
            'tanggal_mulai' => now()->toDateString(),
            'catatan' => "Dikonversi otomatis dari Lead ID #{$lead->id}.",
        ]);
    }
}
