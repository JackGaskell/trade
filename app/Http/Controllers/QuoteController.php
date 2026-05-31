<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Quote::class, 'quote');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $quotes = Quote::query()
            ->whereHas('job', fn ($query) => $query->where('user_id', auth()->id()))
            ->with(['job.client'])
            ->latest()
            ->paginate(15);

        return view('quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $jobs = auth()->user()->jobs()->with('client')->latest()->get();
        $selectedJobId = $request->query('job_id');

        return view('quotes.create', compact('jobs', 'selectedJobId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuoteRequest $request): RedirectResponse
    {
        Quote::query()->create($request->validated());

        return redirect()
            ->route('quotes.index')
            ->with('status', 'quote-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote): View
    {
        $quote->load(['job.client']);

        return view('quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote): View
    {
        $jobs = auth()->user()->jobs()->with('client')->latest()->get();

        return view('quotes.edit', compact('quote', 'jobs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuoteRequest $request, Quote $quote): RedirectResponse
    {
        $quote->update($request->validated());

        return redirect()
            ->route('quotes.show', $quote)
            ->with('status', 'quote-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote): RedirectResponse
    {
        $quote->delete();

        return redirect()
            ->route('quotes.index')
            ->with('status', 'quote-deleted');
    }
}
