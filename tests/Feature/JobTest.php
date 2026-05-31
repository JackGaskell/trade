<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTradeData;
use Tests\TestCase;

class JobTest extends TestCase
{
    use CreatesTradeData;
    use RefreshDatabase;

    public function test_guests_are_redirected_from_jobs_index(): void
    {
        $this->get(route('jobs.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_jobs_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('jobs.index'))
            ->assertOk()
            ->assertSee('Jobs');
    }

    public function test_jobs_index_only_shows_own_jobs(): void
    {
        ['user' => $user, 'job' => $ownJob] = $this->createUserWithJob(['title' => 'My Kitchen Refit']);
        ['job' => $otherJob] = $this->createUserWithJob(['title' => 'Other Bathroom']);

        $this->actingAs($user)
            ->get(route('jobs.index'))
            ->assertOk()
            ->assertSee('My Kitchen Refit')
            ->assertDontSee('Other Bathroom');
    }

    public function test_user_can_create_a_job(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $this->actingAs($user)
            ->post(route('jobs.store'), [
                'client_id' => $client->id,
                'title' => 'Boiler Install',
                'description' => 'New combi boiler',
                'address' => '2 Oak Avenue',
                'scheduled_date' => now()->addWeek()->format('Y-m-d'),
                'status' => Job::STATUS_ENQUIRY,
                'notes' => null,
            ])
            ->assertRedirect(route('jobs.index'))
            ->assertSessionHas('status', 'job-created');

        $this->assertDatabaseHas('trade_jobs', [
            'user_id' => $user->id,
            'client_id' => $client->id,
            'title' => 'Boiler Install',
        ]);
    }

    public function test_job_title_is_required(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $this->actingAs($user)
            ->from(route('jobs.create'))
            ->post(route('jobs.store'), [
                'client_id' => $client->id,
                'title' => '',
                'status' => Job::STATUS_ENQUIRY,
            ])
            ->assertRedirect(route('jobs.create'))
            ->assertSessionHasErrors('title');
    }

    public function test_user_cannot_create_job_for_another_users_client(): void
    {
        $user = User::factory()->create();
        ['client' => $otherClient] = $this->createUserWithClient();

        $this->actingAs($user)
            ->post(route('jobs.store'), [
                'client_id' => $otherClient->id,
                'title' => 'Sneaky Job',
                'status' => Job::STATUS_ENQUIRY,
            ])
            ->assertSessionHasErrors('client_id');
    }

    public function test_user_can_view_their_job_with_quotes_and_invoices_sections(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob(['title' => 'Roof Repair']);

        $this->actingAs($user)
            ->get(route('jobs.show', $job))
            ->assertOk()
            ->assertSee('Roof Repair')
            ->assertSee('Quotes')
            ->assertSee('Invoices');
    }

    public function test_user_cannot_view_another_users_job(): void
    {
        ['job' => $job] = $this->createUserWithJob();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('jobs.show', $job))
            ->assertForbidden();
    }

    public function test_user_can_update_their_job(): void
    {
        ['user' => $user, 'client' => $client, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)
            ->put(route('jobs.update', $job), [
                'client_id' => $client->id,
                'title' => 'Updated Job Title',
                'description' => null,
                'address' => null,
                'scheduled_date' => null,
                'status' => Job::STATUS_IN_PROGRESS,
                'notes' => null,
            ])
            ->assertRedirect(route('jobs.show', $job))
            ->assertSessionHas('status', 'job-updated');

        $this->assertDatabaseHas('trade_jobs', [
            'id' => $job->id,
            'title' => 'Updated Job Title',
            'status' => Job::STATUS_IN_PROGRESS,
        ]);
    }

    public function test_user_can_delete_their_job(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();

        $this->actingAs($user)
            ->delete(route('jobs.destroy', $job))
            ->assertRedirect(route('jobs.index'))
            ->assertSessionHas('status', 'job-deleted');

        $this->assertDatabaseMissing('trade_jobs', ['id' => $job->id]);
    }

    public function test_create_job_form_preselects_client_from_query_string(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $response = $this->actingAs($user)
            ->get(route('jobs.create', ['client_id' => $client->id]));

        $response->assertOk();
        $response->assertSee('value="'.$client->id.'"', false);
    }
}
