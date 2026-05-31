<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Job;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTradeData;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use CreatesTradeData;
    use RefreshDatabase;

    public function test_guests_are_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('What needs your attention today?');
    }

    public function test_dashboard_shows_outstanding_invoice_total(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        Invoice::factory()->for($job)->create([
            'amount' => 300,
            'status' => Invoice::STATUS_SENT,
        ]);
        Invoice::factory()->for($job)->create([
            'amount' => 200,
            'status' => Invoice::STATUS_OVERDUE,
        ]);
        Invoice::factory()->for($job)->create([
            'amount' => 1000,
            'status' => Invoice::STATUS_PAID,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Outstanding Invoices')
            ->assertSee('£500.00');
    }

    public function test_dashboard_shows_overdue_invoice_total(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        Invoice::factory()->for($job)->create([
            'amount' => 450,
            'status' => Invoice::STATUS_OVERDUE,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Overdue Invoices')
            ->assertSee('£450.00');
    }

    public function test_dashboard_shows_quotes_awaiting_response_count(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        Quote::factory()->for($job)->create(['status' => Quote::STATUS_SENT]);
        Quote::factory()->for($job)->create(['status' => Quote::STATUS_SENT]);
        Quote::factory()->for($job)->create(['status' => Quote::STATUS_ACCEPTED]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Quotes Awaiting Response')
            ->assertSee('2', false);
    }

    public function test_dashboard_shows_active_jobs_count(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob(['status' => Job::STATUS_IN_PROGRESS]);
        Job::factory()->for($user)->for($job->client)->create(['status' => Job::STATUS_COMPLETED]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Active Jobs')
            ->assertSee('1', false);
    }

    public function test_dashboard_shows_upcoming_jobs(): void
    {
        ['user' => $user] = $this->createUserWithJob([
            'title' => 'Upcoming Fence Install',
            'scheduled_date' => now()->addDays(3),
            'status' => Job::STATUS_ACCEPTED,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Upcoming Jobs')
            ->assertSee('Upcoming Fence Install');
    }

    public function test_dashboard_does_not_show_completed_jobs_as_upcoming(): void
    {
        ['user' => $user] = $this->createUserWithJob([
            'title' => 'Finished Patio Job',
            'scheduled_date' => now()->addDays(5),
            'status' => Job::STATUS_COMPLETED,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('No upcoming jobs scheduled.')
            ->assertDontSee('Finished Patio Job');
    }

    public function test_dashboard_shows_recent_clients(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Recent Clients')
            ->assertSee($client->name);
    }

    public function test_dashboard_shows_recent_quotes(): void
    {
        ['user' => $user, 'quote' => $quote] = $this->createUserWithQuote([
            'status' => Quote::STATUS_SENT,
            'amount' => 750,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Recent Quotes')
            ->assertSee($quote->quote_number)
            ->assertSee('£750.00');
    }

    public function test_dashboard_shows_recent_invoices(): void
    {
        ['user' => $user, 'invoice' => $invoice] = $this->createUserWithInvoice([
            'amount' => 500,
            'status' => Invoice::STATUS_SENT,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Recent Invoices')
            ->assertSee($invoice->invoice_number)
            ->assertSee('£500.00');
    }
}
