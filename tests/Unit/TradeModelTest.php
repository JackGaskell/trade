<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\Job;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTradeData;
use Tests\TestCase;

class TradeModelTest extends TestCase
{
    use CreatesTradeData;
    use RefreshDatabase;

    public function test_invoice_is_unpaid_for_sent_and_overdue_statuses(): void
    {
        $sent = Invoice::factory()->make(['status' => Invoice::STATUS_SENT]);
        $overdue = Invoice::factory()->make(['status' => Invoice::STATUS_OVERDUE]);
        $paid = Invoice::factory()->make(['status' => Invoice::STATUS_PAID]);
        $draft = Invoice::factory()->make(['status' => Invoice::STATUS_DRAFT]);

        $this->assertTrue($sent->isUnpaid());
        $this->assertTrue($overdue->isUnpaid());
        $this->assertFalse($paid->isUnpaid());
        $this->assertFalse($draft->isUnpaid());
    }

    public function test_invoice_formats_amount_in_pounds(): void
    {
        $invoice = Invoice::factory()->make(['amount' => 1234.5]);

        $this->assertSame('£1,234.50', $invoice->formattedAmount());
        $this->assertSame('£0.00', Invoice::formatMoney(0));
    }

    public function test_quote_generates_sequential_numbers_for_same_user(): void
    {
        ['job' => $job] = $this->createUserWithJob();

        $first = Quote::factory()->for($job)->create();
        $second = Quote::factory()->for($job)->create();

        $this->assertSame('Q-'.now()->year.'-0001', $first->quote_number);
        $this->assertSame('Q-'.now()->year.'-0002', $second->quote_number);
    }

    public function test_invoice_generates_sequential_numbers_for_same_user(): void
    {
        ['job' => $job] = $this->createUserWithJob();

        $first = Invoice::factory()->for($job)->create();
        $second = Invoice::factory()->for($job)->create();

        $this->assertSame('INV-'.now()->year.'-0001', $first->invoice_number);
        $this->assertSame('INV-'.now()->year.'-0002', $second->invoice_number);
    }

    public function test_user_has_expected_relationships(): void
    {
        ['user' => $user, 'client' => $client, 'job' => $job] = $this->createUserWithJob();
        $quote = Quote::factory()->for($job)->create();
        $invoice = Invoice::factory()->for($job)->create();

        $this->assertTrue($user->clients->contains($client));
        $this->assertTrue($user->jobs->contains($job));
        $this->assertTrue($user->quotes->contains($quote));
        $this->assertTrue($user->invoices->contains($invoice));
    }

    public function test_job_belongs_to_client_and_has_quotes_and_invoices(): void
    {
        ['client' => $client, 'job' => $job] = $this->createUserWithJob();
        $quote = Quote::factory()->for($job)->create();
        $invoice = Invoice::factory()->for($job)->create();

        $this->assertTrue($job->client->is($client));
        $this->assertTrue($job->quotes->contains($quote));
        $this->assertTrue($job->invoices->contains($invoice));
    }

    public function test_deleting_client_cascades_to_jobs(): void
    {
        ['user' => $user, 'client' => $client, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)->delete(route('clients.destroy', $client));

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
        $this->assertDatabaseMissing('trade_jobs', ['id' => $job->id]);
    }
}
