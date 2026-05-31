<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Invoice::class, 'invoice');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $invoices = Invoice::query()
            ->whereHas('job', fn ($query) => $query->where('user_id', auth()->id()))
            ->with(['job.client'])
            ->orderByRaw("CASE invoices.status WHEN 'overdue' THEN 0 WHEN 'sent' THEN 1 WHEN 'draft' THEN 2 ELSE 3 END")
            ->latest()
            ->paginate(15);

        $totalUnpaid = Invoice::query()
            ->whereHas('job', fn ($query) => $query->where('user_id', auth()->id()))
            ->whereIn('status', Invoice::UNPAID_STATUSES)
            ->sum('amount');

        $jobs = auth()->user()->jobs()->with('client')->latest()->get();

        return view('invoices.index', compact('invoices', 'totalUnpaid', 'jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): RedirectResponse
    {
        return redirect()->route('invoices.index', array_filter([
            'open' => 'create-invoice',
            'job_id' => $request->query('job_id'),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        Invoice::query()->create($request->validated());

        return redirect()
            ->route('invoices.index')
            ->with('status', 'invoice-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['job.client']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice): View
    {
        $jobs = auth()->user()->jobs()->with('client')->latest()->get();

        return view('invoices.edit', compact('invoice', 'jobs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($request->validated());

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('status', 'invoice-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('status', 'invoice-deleted');
    }
}
