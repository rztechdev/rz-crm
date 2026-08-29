<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\Payment;
use App\Models\MaintenanceSubscription;
use App\Models\MessageLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // 1. Total leads breakdown per status
        $totalLeads = Lead::count();
        $leadsPerStatus = [
            'belum_dihubungi' => Lead::where('status', 'belum_dihubungi')->count(),
            'sudah_chat' => Lead::where('status', 'sudah_chat')->count(),
            'nego' => Lead::where('status', 'nego')->count(),
            'deal' => Lead::where('status', 'deal')->count(),
            'tidak_lanjut' => Lead::where('status', 'tidak_lanjut')->count(),
        ];

        // 2. Potensi Pipeline (Total harga estimasi dari lead berstatus Sudah Chat + Nego)
        $pipelineLeads = Lead::whereIn('status', ['sudah_chat', 'nego'])->get();
        $potensiPipeline = $pipelineLeads->sum(function ($lead) {
            return $lead->getDefaultPackagePrice();
        });

        // 3. Total Closing Bulan Ini (Sum harga project yang Deal / dibuat bulan berjalan)
        $closingBulanIni = Project::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'dibatalkan')
            ->sum('harga');

        // Total Project Deal bulan ini
        $projectDealCount = Project::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'dibatalkan')
            ->count();

        // 4. MRR dari maintenance aktif (Sum hargaBulanan semua MaintenanceSubscription berstatus Aktif)
        $mrrMaintenance = MaintenanceSubscription::where('status', 'aktif')->sum('harga_bulanan');
        $activeMaintenanceCount = MaintenanceSubscription::where('status', 'aktif')->count();

        // 5. Follow-up Hari Ini & Overdue Follow-ups
        $overdueFollowUps = Lead::whereNotNull('follow_up_date')
            ->where('follow_up_date', '<', $now->toDateString())
            ->whereNotIn('status', ['deal', 'tidak_lanjut'])
            ->orderBy('follow_up_date', 'asc')
            ->take(5)
            ->get();

        $todayFollowUps = Lead::where('follow_up_date', $now->toDateString())
            ->whereNotIn('status', ['deal', 'tidak_lanjut'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 6. Proyek yang sedang berjalan (Dikerjakan / Review)
        $activeProjects = Project::with('lead')
            ->whereIn('status', ['dp_diterima', 'dikerjakan', 'review'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 7. Pesan WhatsApp Masuk Terbaru (Balasan klien)
        $recentIncomingMessages = MessageLog::with('lead')
            ->where('arah', 'masuk')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 8. Riwayat Aktivitas Terkini (Proyek & Pembayaran)
        $recentProjects = Project::with('lead')->latest()->take(6)->get();

        $stats = [
            'total_leads' => $totalLeads,
            'leads_per_status' => $leadsPerStatus,
            'potensi_pipeline' => $potensiPipeline,
            'closing_bulan_ini' => $closingBulanIni,
            'project_deal_count' => $projectDealCount,
            'mrr_maintenance' => $mrrMaintenance,
            'active_maintenance_count' => $activeMaintenanceCount,
            'overdue_count' => Lead::whereNotNull('follow_up_date')->where('follow_up_date', '<', $now->toDateString())->whereNotIn('status', ['deal', 'tidak_lanjut'])->count(),
            'today_count' => Lead::where('follow_up_date', $now->toDateString())->whereNotIn('status', ['deal', 'tidak_lanjut'])->count(),
        ];

        return view('dashboard', compact(
            'stats',
            'overdueFollowUps',
            'todayFollowUps',
            'activeProjects',
            'recentIncomingMessages',
            'recentProjects'
        ));
    }
}
