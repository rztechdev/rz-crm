<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Payment;
use App\Models\MaintenanceSubscription;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Show Project Invoice (HTML, PDF, Word).
     */
    public function projectInvoice(Request $request, Project $project)
    {
        $project->load(['lead', 'payments']);

        $invoiceNumber = 'INV/' . $project->created_at->format('Ym') . '/' . str_pad($project->id, 4, '0', STR_PAD_LEFT);
        
        ActivityLogger::log('view_invoice', "Melihat/mencetak invoice {$invoiceNumber} untuk project {$project->nama_project}", 'Project', $project->id);

        $data = [
            'project' => $project,
            'lead' => $project->lead,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => $project->created_at->translatedFormat('d F Y'),
            'dueDate' => $project->created_at->addDays(7)->translatedFormat('d F Y'),
        ];

        $format = strtolower($request->get('format', 'html'));
        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.project', $data)->setPaper('a4', 'portrait');
            return $pdf->download("Invoice-{$project->id}-{$invoiceNumber}.pdf");
        }
        if ($format === 'word' || $format === 'doc') {
            $html = view('invoices.project', $data)->render();
            return response($html, 200, [
                'Content-Type' => 'application/msword; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"Invoice-{$invoiceNumber}.doc\"",
            ]);
        }

        return view('invoices.project', $data);
    }

    /**
     * Show Official Kwitansi (Payment Receipt) (HTML, PDF, Word).
     */
    public function paymentReceipt(Request $request, Payment $payment)
    {
        $payment->load('project.lead');
        $project = $payment->project;
        $lead = $project?->lead;

        $receiptNumber = 'KW/' . ($payment->tanggal ? $payment->tanggal->format('Ym') : now()->format('Ym')) . '/' . str_pad($payment->id, 4, '0', STR_PAD_LEFT);
        $terbilang = $this->terbilang($payment->jumlah) . ' Rupiah';

        ActivityLogger::log('view_receipt', "Melihat/mencetak kwitansi {$receiptNumber} senilai Rp " . number_format($payment->jumlah, 0, ',', '.'), 'Payment', $payment->id);

        $data = [
            'payment' => $payment,
            'project' => $project,
            'lead' => $lead,
            'receiptNumber' => $receiptNumber,
            'receiptDate' => $payment->tanggal ? $payment->tanggal->translatedFormat('d F Y') : now()->translatedFormat('d F Y'),
            'terbilang' => $terbilang,
        ];

        $format = strtolower($request->get('format', 'html'));
        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.receipt', $data)->setPaper('a4', 'portrait');
            return $pdf->download("Kwitansi-{$receiptNumber}.pdf");
        }
        if ($format === 'word' || $format === 'doc') {
            $html = view('invoices.receipt', $data)->render();
            return response($html, 200, [
                'Content-Type' => 'application/msword; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"Kwitansi-{$receiptNumber}.doc\"",
            ]);
        }

        return view('invoices.receipt', $data);
    }

    /**
     * Show Maintenance Subscription Invoice (HTML, PDF, Word).
     */
    public function maintenanceInvoice(Request $request, MaintenanceSubscription $subscription)
    {
        $subscription->load(['lead', 'project']);

        $invoiceNumber = 'INV-MNT/' . now()->format('Ym') . '/' . str_pad($subscription->id, 4, '0', STR_PAD_LEFT);

        $data = [
            'subscription' => $subscription,
            'lead' => $subscription->lead,
            'project' => $subscription->project,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => now()->translatedFormat('d F Y'),
            'dueDate' => $subscription->tanggal_jatuh_tempo_berikutnya ? $subscription->tanggal_jatuh_tempo_berikutnya->translatedFormat('d F Y') : now()->addDays(7)->translatedFormat('d F Y'),
        ];

        $format = strtolower($request->get('format', 'html'));
        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.maintenance', $data)->setPaper('a4', 'portrait');
            return $pdf->download("Invoice-Maintenance-{$subscription->id}.pdf");
        }
        if ($format === 'word' || $format === 'doc') {
            $html = view('invoices.maintenance', $data)->render();
            return response($html, 200, [
                'Content-Type' => 'application/msword; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"Invoice-Maintenance-{$subscription->id}.doc\"",
            ]);
        }

        return view('invoices.maintenance', $data);
    }

    /**
     * Helper to convert number to Indonesian words (Terbilang).
     */
    private function terbilang($angka): string
    {
        $angka = abs((int)$angka);
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($angka < 12) {
            return $baca[$angka];
        } elseif ($angka < 20) {
            return trim($this->terbilang($angka - 10) . ' Belas');
        } elseif ($angka < 100) {
            return trim($this->terbilang((int)($angka / 10)) . ' Puluh ' . $this->terbilang($angka % 10));
        } elseif ($angka < 200) {
            return trim('Seratus ' . $this->terbilang($angka - 100));
        } elseif ($angka < 1000) {
            return trim($this->terbilang((int)($angka / 100)) . ' Ratus ' . $this->terbilang($angka % 100));
        } elseif ($angka < 2000) {
            return trim('Seribu ' . $this->terbilang($angka - 1000));
        } elseif ($angka < 1000000) {
            return trim($this->terbilang((int)($angka / 1000)) . ' Ribu ' . $this->terbilang($angka % 1000));
        } elseif ($angka < 1000000000) {
            return trim($this->terbilang((int)($angka / 1000000)) . ' Juta ' . $this->terbilang($angka % 1000000));
        } elseif ($angka < 1000000000000) {
            return trim($this->terbilang((int)($angka / 1000000000)) . ' Miliar ' . $this->terbilang($angka % 1000000000));
        }

        return (string)$angka;
    }
}
