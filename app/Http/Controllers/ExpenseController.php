<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Expense::class, 'expense');
    }

    public function index(): View
    {
        $user = auth()->user();

        $expenses = $user->expenses()
            ->with('job.client')
            ->latest('expense_date')
            ->latest('id')
            ->paginate(15);

        $expensesThisMonth = $user->expenses()
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $jobs = $user->jobs()->with('client')->latest()->get();
        $vatRegistered = (bool) $user->businessProfile?->vat_registered;

        return view('expenses.index', compact('expenses', 'expensesThisMonth', 'jobs', 'vatRegistered'));
    }

    public function create(Request $request): RedirectResponse
    {
        return redirect()->route('expenses.index', array_filter([
            'open' => 'create-expense',
            'job_id' => $request->query('job_id'),
        ]));
    }

    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['receipt', 'remove_receipt']);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('receipt')) {
            [$data['receipt_path'], $data['receipt_original_name']] = $this->storeReceipt($request->file('receipt'), $request->user()->id);
        }

        Expense::query()->create($data);

        return redirect()
            ->route('expenses.index')
            ->with('status', 'expense-created');
    }

    public function show(Expense $expense): View
    {
        $expense->load('job.client');

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        $jobs = auth()->user()->jobs()->with('client')->latest()->get();
        $vatRegistered = (bool) auth()->user()->businessProfile?->vat_registered;

        return view('expenses.edit', compact('expense', 'jobs', 'vatRegistered'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $data = $request->safe()->except(['receipt', 'remove_receipt']);

        if ($request->boolean('remove_receipt')) {
            $expense->deleteReceipt();
            $data['receipt_path'] = null;
            $data['receipt_original_name'] = null;
        }

        if ($request->hasFile('receipt')) {
            $expense->deleteReceipt();
            [$data['receipt_path'], $data['receipt_original_name']] = $this->storeReceipt($request->file('receipt'), $request->user()->id);
        }

        $expense->update($data);

        return redirect()
            ->route('expenses.show', $expense)
            ->with('status', 'expense-updated');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('status', 'expense-deleted');
    }

    public function receipt(Expense $expense): StreamedResponse
    {
        $this->authorize('view', $expense);

        abort_unless($expense->hasReceipt(), 404);
        abort_unless(Storage::disk('local')->exists($expense->receipt_path), 404);

        return Storage::disk('local')->response(
            $expense->receipt_path,
            $expense->receipt_original_name ?? 'receipt',
        );
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function storeReceipt(UploadedFile $file, int $userId): array
    {
        $path = $file->store('expenses/'.$userId, 'local');

        return [$path, $file->getClientOriginalName()];
    }
}
