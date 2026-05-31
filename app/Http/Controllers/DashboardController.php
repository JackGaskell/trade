<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $outstandingTotal = $user->invoices()
            ->whereIn('invoices.status', Invoice::UNPAID_STATUSES)
            ->sum('invoices.amount');

        $overdueTotal = $user->invoices()
            ->where('invoices.status', Invoice::STATUS_OVERDUE)
            ->sum('invoices.amount');

        $quotesAwaitingResponse = $user->quotes()
            ->where('quotes.status', Quote::STATUS_SENT)
            ->count();

        $activeJobs = $user->jobs()
            ->whereNotIn('status', [Job::STATUS_COMPLETED, Job::STATUS_CANCELLED])
            ->count();

        $expensesThisMonth = $user->expenses()
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $paidIncomeThisMonth = $user->invoices()
            ->where('invoices.status', Invoice::STATUS_PAID)
            ->whereYear('invoices.updated_at', now()->year)
            ->whereMonth('invoices.updated_at', now()->month)
            ->sum('invoices.amount');

        $profitEstimate = $paidIncomeThisMonth - $expensesThisMonth;

        $upcomingJobs = $user->jobs()
            ->with('client')
            ->whereNotNull('scheduled_date')
            ->whereDate('scheduled_date', '>=', today())
            ->whereNotIn('status', [Job::STATUS_COMPLETED, Job::STATUS_CANCELLED])
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        $recentClients = $user->clients()
            ->latest()
            ->take(5)
            ->get();

        $recentQuotes = $user->quotes()
            ->with(['job.client'])
            ->latest('quotes.created_at')
            ->take(5)
            ->get();

        $recentInvoices = $user->invoices()
            ->with(['job.client'])
            ->latest('invoices.created_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'outstandingTotal',
            'overdueTotal',
            'quotesAwaitingResponse',
            'activeJobs',
            'expensesThisMonth',
            'profitEstimate',
            'upcomingJobs',
            'recentClients',
            'recentQuotes',
            'recentInvoices',
        ));
    }
}
