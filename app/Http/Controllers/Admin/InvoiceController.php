<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'reservation.vehicle']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'reservation.vehicle']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid,overdue,cancelled',
        ]);

        $data = ['status' => $validated['status']];

        if ($validated['status'] === 'paid' && !$invoice->paid_at) {
            $data['paid_at'] = now();
        }

        $invoice->update($data);

        return back()->with('success', 'Facture mise à jour.');
    }

    public function download(Invoice $invoice)
    {
        $invoice->load(['reservation.vehicle', 'client']);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Impossible de supprimer une facture payée.');
        }

        $invoice->delete();

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Facture supprimée.');
    }
}