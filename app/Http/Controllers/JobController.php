<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Client;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Job::class, 'job');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jobs = auth()->user()
            ->jobs()
            ->with('client')
            ->latest()
            ->paginate(15);

        $clients = auth()->user()->clients()->orderBy('name')->get();

        return view('jobs.index', compact('jobs', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): RedirectResponse
    {
        return redirect()->route('jobs.index', array_filter([
            'open' => 'create-job',
            'client_id' => $request->query('client_id'),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request): RedirectResponse
    {
        auth()->user()->jobs()->create($request->validated());

        return redirect()
            ->route('jobs.index')
            ->with('status', 'job-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): View
    {
        $job->load([
            'client',
            'quotes' => fn ($query) => $query->latest(),
            'invoices' => fn ($query) => $query->latest(),
            'expenses' => fn ($query) => $query->latest('expense_date'),
        ]);

        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job): View
    {
        $clients = auth()->user()->clients()->orderBy('name')->get();

        return view('jobs.edit', compact('job', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, Job $job): RedirectResponse
    {
        $job->update($request->validated());

        return redirect()
            ->route('jobs.show', $job)
            ->with('status', 'job-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return redirect()
            ->route('jobs.index')
            ->with('status', 'job-deleted');
    }
}
