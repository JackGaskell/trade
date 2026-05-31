<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTradeData;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use CreatesTradeData;
    use RefreshDatabase;

    public function test_guests_are_redirected_from_invoices_index(): void
    {
        $this->get(route('invoices.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_invoices_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('invoices.index'))
            ->assertOk()
            ->assertSee('Invoices');
    }

    public function test_user_can_create_an_invoice(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)
            ->post(route('invoices.store'), [
                'job_id' => $job->id,
                'amount' => 850.00,
                'due_date' => now()->addWeeks(2)->format('Y-m-d'),
                'status' => Invoice::STATUS_DRAFT,
                'notes' => 'Payment within 14 days',
            ])
            ->assertRedirect(route('invoices.index'))
            ->assertSessionHas('status', 'invoice-created');

        $this->assertDatabaseHas('invoices', [
            'job_id' => $job->id,
            'amount' => 850.00,
            'status' => Invoice::STATUS_DRAFT,
        ]);
    }

    public function test_invoice_number_is_generated_automatically(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)
            ->post(route('invoices.store'), [
                'job_id' => $job->id,
                'amount' => 200,
                'status' => Invoice::STATUS_DRAFT,
            ]);

        $invoice = Invoice::query()->first();
        $this->assertNotNull($invoice);
        $this->assertMatchesRegularExpression('/^INV-\d{4}-\d{4}$/', $invoice->invoice_number);
    }

    public function test_invoice_numbers_increment_per_user(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        Invoice::factory()->for($job)->create();
        Invoice::factory()->for($job)->create();

        $numbers = Invoice::query()->orderBy('id')->pluck('invoice_number')->all();
        $this->assertSame('INV-'.now()->year.'-0001', $numbers[0]);
        $this->assertSame('INV-'.now()->year.'-0002', $numbers[1]);
    }

    public function test_invoices_index_shows_unpaid_total_banner_when_outstanding(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        Invoice::factory()->for($job)->create([
            'amount' => 500,
            'status' => Invoice::STATUS_SENT,
        ]);

        $this->actingAs($user)
            ->get(route('invoices.index'))
            ->assertOk()
            ->assertSee('Total Unpaid')
            ->assertSee('£500.00');
    }

    public function test_user_cannot_create_invoice_for_another_users_job(): void
    {
        $user = User::factory()->create();
        ['job' => $otherJob] = $this->createUserWithJob();

        $this->actingAs($user)
            ->post(route('invoices.store'), [
                'job_id' => $otherJob->id,
                'amount' => 100,
                'status' => Invoice::STATUS_DRAFT,
            ])
            ->assertSessionHasErrors('job_id');
    }

    public function test_user_can_view_their_invoice(): void
    {
        ['user' => $user, 'invoice' => $invoice] = $this->createUserWithInvoice([
            'amount' => 1200,
            'status' => Invoice::STATUS_OVERDUE,
        ]);

        $this->actingAs($user)
            ->get(route('invoices.show', $invoice))
            ->assertOk()
            ->assertSee($invoice->invoice_number)
            ->assertSee('£1,200.00')
            ->assertSee('Overdue');
    }

    public function test_user_cannot_view_another_users_invoice(): void
    {
        ['invoice' => $invoice] = $this->createUserWithInvoice();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('invoices.show', $invoice))
            ->assertForbidden();
    }

    public function test_user_can_update_their_invoice(): void
    {
        ['user' => $user, 'job' => $job, 'invoice' => $invoice] = $this->createUserWithInvoice();

        $this->actingAs($user)
            ->put(route('invoices.update', $invoice), [
                'job_id' => $job->id,
                'amount' => 975.50,
                'due_date' => now()->addWeek()->format('Y-m-d'),
                'status' => Invoice::STATUS_PAID,
                'notes' => 'Paid by bank transfer',
            ])
            ->assertRedirect(route('invoices.show', $invoice))
            ->assertSessionHas('status', 'invoice-updated');

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'amount' => 975.50,
            'status' => Invoice::STATUS_PAID,
        ]);
    }

    public function test_user_can_delete_their_invoice(): void
    {
        ['user' => $user, 'invoice' => $invoice] = $this->createUserWithInvoice();

        $this->actingAs($user)
            ->delete(route('invoices.destroy', $invoice))
            ->assertRedirect(route('invoices.index'))
            ->assertSessionHas('status', 'invoice-deleted');

        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    public function test_deleting_a_job_cascades_to_invoices(): void
    {
        ['user' => $user, 'job' => $job, 'invoice' => $invoice] = $this->createUserWithInvoice();

        $this->actingAs($user)
            ->delete(route('jobs.destroy', $job));

        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }
}
