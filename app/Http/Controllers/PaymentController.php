<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['project.lead']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $payments = $query->latest('tanggal')->paginate(15)->withQueryString();

        $totalLunas = Payment::where('status', 'lunas')->sum('jumlah');
        $totalPending = Payment::where('status', 'pending')->sum('jumlah');

        return view('payments.index', compact('payments', 'totalLunas', 'totalPending'));
    }

    /**
     * Store a newly created payment for a project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'jenis' => 'required|in:dp,pelunasan,maintenance,lainnya',
            'jumlah' => 'required|numeric|min:1',
            'status' => 'required|in:pending,lunas',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $payment = Payment::create($validated);

        return back()->with('success', "Pembayaran Rp " . number_format($payment->jumlah, 0, ',', '.') . " berhasil dicatat.");
    }

    /**
     * Update payment status (e.g. pending -> lunas).
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,lunas',
        ]);

        $payment->update(['status' => $request->status]);

        return back()->with('success', "Status pembayaran berhasil diubah menjadi: " . strtoupper($payment->status));
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back()->with('success', "Data pembayaran berhasil dihapus.");
    }
}
