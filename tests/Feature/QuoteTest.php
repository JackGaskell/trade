<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTradeData;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use CreatesTradeData;
    use RefreshDatabase;

    public function test_guests_are_redirected_from_quotes_index(): void
    {
        $this->get(route('quotes.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_quotes_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('quotes.index'))
            ->assertOk()
            ->assertSee('Quotes');
    }

    public function test_user_can_create_a_quote(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)
            ->post(route('quotes.store'), [
                'job_id' => $job->id,
                'amount' => 1250.50,
                'description' => 'Full bathroom refit',
                'valid_until' => now()->addMonth()->format('Y-m-d'),
                'status' => Quote::STATUS_DRAFT,
            ])
            ->assertRedirect(route('quotes.index'))
            ->assertSessionHas('status', 'quote-created');

        $this->assertDatabaseHas('quotes', [
            'job_id' => $job->id,
            'amount' => 1250.50,
            'status' => Quote::STATUS_DRAFT,
        ]);
    }

    public function test_quote_number_is_generated_automatically(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)
            ->post(route('quotes.store'), [
                'job_id' => $job->id,
                'amount' => 500,
                'status' => Quote::STATUS_DRAFT,
            ]);

        $quote = Quote::query()->first();
        $this->assertNotNull($quote);
        $this->assertMatchesRegularExpression('/^Q-\d{4}-\d{4}$/', $quote->quote_number);
    }

    public function test_quote_numbers_increment_per_user(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        Quote::factory()->for($job)->create();
        Quote::factory()->for($job)->create();

        $numbers = Quote::query()->orderBy('id')->pluck('quote_number')->all();
        $this->assertSame('Q-'.now()->year.'-0001', $numbers[0]);
        $this->assertSame('Q-'.now()->year.'-0002', $numbers[1]);
    }

    public function test_quote_amount_is_required(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)
            ->from(route('quotes.index'))
            ->post(route('quotes.store'), [
                'job_id' => $job->id,
                'status' => Quote::STATUS_DRAFT,
            ])
            ->assertRedirect(route('quotes.index'))
            ->assertSessionHasErrors('amount');
    }

    public function test_user_cannot_create_quote_for_another_users_job(): void
    {
        $user = User::factory()->create();
        ['job' => $otherJob] = $this->createUserWithJob();

        $this->actingAs($user)
            ->post(route('quotes.store'), [
                'job_id' => $otherJob->id,
                'amount' => 100,
                'status' => Quote::STATUS_DRAFT,
            ])
            ->assertSessionHasErrors('job_id');
    }

    public function test_user_can_view_their_quote(): void
    {
        ['user' => $user, 'quote' => $quote] = $this->createUserWithQuote([
            'amount' => 999.99,
            'status' => Quote::STATUS_SENT,
        ]);

        $this->actingAs($user)
            ->get(route('quotes.show', $quote))
            ->assertOk()
            ->assertSee($quote->quote_number)
            ->assertSee('£999.99');
    }

    public function test_user_cannot_view_another_users_quote(): void
    {
        ['quote' => $quote] = $this->createUserWithQuote();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('quotes.show', $quote))
            ->assertForbidden();
    }

    public function test_user_can_update_their_quote(): void
    {
        ['user' => $user, 'job' => $job, 'quote' => $quote] = $this->createUserWithQuote();

        $this->actingAs($user)
            ->put(route('quotes.update', $quote), [
                'job_id' => $job->id,
                'amount' => 1500,
                'description' => 'Updated scope',
                'valid_until' => null,
                'status' => Quote::STATUS_ACCEPTED,
            ])
            ->assertRedirect(route('quotes.show', $quote))
            ->assertSessionHas('status', 'quote-updated');

        $this->assertDatabaseHas('quotes', [
            'id' => $quote->id,
            'amount' => 1500,
            'status' => Quote::STATUS_ACCEPTED,
        ]);
    }

    public function test_user_can_delete_their_quote(): void
    {
        ['user' => $user, 'quote' => $quote] = $this->createUserWithQuote();

        $this->actingAs($user)
            ->delete(route('quotes.destroy', $quote))
            ->assertRedirect(route('quotes.index'))
            ->assertSessionHas('status', 'quote-deleted');

        $this->assertDatabaseMissing('quotes', ['id' => $quote->id]);
    }

    public function test_create_quote_form_preselects_job_from_query_string(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob(['title' => 'Garden Decking']);

        $this->actingAs($user)
            ->get(route('quotes.create', ['job_id' => $job->id]))
            ->assertRedirect(route('quotes.index', ['open' => 'create-quote', 'job_id' => $job->id]));

        $this->actingAs($user)
            ->get(route('quotes.index', ['open' => 'create-quote', 'job_id' => $job->id]))
            ->assertOk()
            ->assertSee('Garden Decking');
    }
}
