<?php

namespace Tests\Concerns;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Quote;
use App\Models\User;

trait CreatesTradeData
{
    /**
     * @return array{user: User, client: Client}
     */
    protected function createUserWithClient(): array
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        return compact('user', 'client');
    }

    /**
     * @return array{user: User, client: Client, job: Job}
     */
    protected function createUserWithJob(array $jobAttributes = []): array
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();
        $job = Job::factory()->for($user)->for($client)->create($jobAttributes);

        return compact('user', 'client', 'job');
    }

    /**
     * @return array{user: User, client: Client, job: Job, quote: Quote}
     */
    protected function createUserWithQuote(array $quoteAttributes = [], array $jobAttributes = []): array
    {
        ['user' => $user, 'client' => $client, 'job' => $job] = $this->createUserWithJob($jobAttributes);
        $quote = Quote::factory()->for($job)->create($quoteAttributes);

        return compact('user', 'client', 'job', 'quote');
    }

    /**
     * @return array{user: User, client: Client, job: Job, invoice: Invoice}
     */
    protected function createUserWithInvoice(array $invoiceAttributes = [], array $jobAttributes = []): array
    {
        ['user' => $user, 'client' => $client, 'job' => $job] = $this->createUserWithJob($jobAttributes);
        $invoice = Invoice::factory()->for($job)->create($invoiceAttributes);

        return compact('user', 'client', 'job', 'invoice');
    }

    /**
     * @return array{user: User, client: Client, job: Job, expense: Expense}
     */
    protected function createUserWithExpense(array $expenseAttributes = [], array $jobAttributes = []): array
    {
        ['user' => $user, 'client' => $client, 'job' => $job] = $this->createUserWithJob($jobAttributes);
        $expense = Expense::factory()->for($user)->for($job)->create($expenseAttributes);

        return compact('user', 'client', 'job', 'expense');
    }
}
