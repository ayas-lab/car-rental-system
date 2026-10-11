<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class InvoiceController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $invoices = $user
            ->invoices()
            ->with('reservation.vehicle')
            ->latest()
            ->paginate(10);

        return view('client.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $invoice->load(['reservation.vehicle', 'client']);

        return view('client.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $invoice->load(['reservation.vehicle', 'client']);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    protected function authorizeInvoice(Invoice $invoice): void
    {
        if ($invoice->client_id !== Auth::id()) {
            abort(403);
        }
    }
}