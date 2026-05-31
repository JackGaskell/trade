<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesTradeData;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use CreatesTradeData;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_guests_are_redirected_from_expenses_index(): void
    {
        $this->get(route('expenses.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_expenses_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('expenses.index'))
            ->assertOk()
            ->assertSee('Expenses');
    }

    public function test_expenses_index_only_shows_own_expenses(): void
    {
        ['user' => $user, 'expense' => $ownExpense] = $this->createUserWithExpense(['supplier' => 'Own Supplier']);
        Expense::factory()->create(['supplier' => 'Other Supplier']);

        $this->actingAs($user)
            ->get(route('expenses.index'))
            ->assertOk()
            ->assertSee('Own Supplier')
            ->assertDontSee('Other Supplier');
    }

    public function test_user_can_create_an_expense(): void
    {
        ['user' => $user, 'job' => $job] = $this->createUserWithJob();
        $receipt = UploadedFile::fake()->create('receipt.pdf', 100, 'application/pdf');

        $this->actingAs($user)
            ->post(route('expenses.store'), [
                'expense_date' => '2026-05-15',
                'supplier' => 'Screwfix',
                'description' => 'Copper pipe fittings',
                'category' => Expense::CATEGORY_MATERIALS_STOCK,
                'amount' => 84.50,
                'job_id' => $job->id,
                'receipt' => $receipt,
            ])
            ->assertRedirect(route('expenses.index'))
            ->assertSessionHas('status', 'expense-created');

        $expense = Expense::query()->first();

        $this->assertNotNull($expense);
        $this->assertSame($user->id, $expense->user_id);
        $this->assertSame($job->id, $expense->job_id);
        $this->assertSame('Screwfix', $expense->supplier);
        $this->assertSame('84.50', $expense->amount);
        $this->assertTrue($expense->hasReceipt());
        Storage::disk('local')->assertExists($expense->receipt_path);
    }

    public function test_vat_amount_is_stored_when_user_is_vat_registered(): void
    {
        $user = User::factory()->create();
        BusinessProfile::factory()->for($user)->vatRegistered()->create();

        $this->actingAs($user)
            ->post(route('expenses.store'), [
                'expense_date' => '2026-05-10',
                'supplier' => 'Toolstation',
                'category' => Expense::CATEGORY_TOOLS_EQUIPMENT,
                'amount' => 120.00,
                'vat_amount' => 20.00,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('20.00', Expense::query()->first()->vat_amount);
    }

    public function test_vat_amount_is_cleared_when_user_is_not_vat_registered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('expenses.store'), [
                'expense_date' => '2026-05-10',
                'supplier' => 'Toolstation',
                'category' => Expense::CATEGORY_TOOLS_EQUIPMENT,
                'amount' => 120.00,
                'vat_amount' => 20.00,
            ])
            ->assertSessionHasNoErrors();

        $this->assertNull(Expense::query()->first()->vat_amount);
    }

    public function test_user_cannot_link_expense_to_another_users_job(): void
    {
        $user = User::factory()->create();
        ['job' => $otherJob] = $this->createUserWithJob();

        $this->actingAs($user)
            ->from(route('expenses.create'))
            ->post(route('expenses.store'), [
                'expense_date' => '2026-05-10',
                'supplier' => 'Invalid Job Link',
                'category' => Expense::CATEGORY_INSURANCE,
                'amount' => 50,
                'job_id' => $otherJob->id,
            ])
            ->assertSessionHasErrors('job_id');

        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_user_can_view_their_expense(): void
    {
        ['user' => $user, 'expense' => $expense] = $this->createUserWithExpense();

        $this->actingAs($user)
            ->get(route('expenses.show', $expense))
            ->assertOk()
            ->assertSee($expense->supplier);
    }

    public function test_user_cannot_view_another_users_expense(): void
    {
        ['expense' => $expense] = $this->createUserWithExpense();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('expenses.show', $expense))
            ->assertForbidden();
    }

    public function test_user_can_update_their_expense(): void
    {
        ['user' => $user, 'expense' => $expense] = $this->createUserWithExpense(['supplier' => 'Old Supplier']);

        $this->actingAs($user)
            ->put(route('expenses.update', $expense), [
                'expense_date' => '2026-05-20',
                'supplier' => 'Updated Supplier',
                'category' => Expense::CATEGORY_VEHICLE_TRAVEL,
                'amount' => 45.00,
            ])
            ->assertRedirect(route('expenses.show', $expense))
            ->assertSessionHas('status', 'expense-updated');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'supplier' => 'Updated Supplier',
            'category' => Expense::CATEGORY_VEHICLE_TRAVEL,
        ]);
    }

    public function test_user_can_delete_their_expense_and_receipt(): void
    {
        ['user' => $user, 'expense' => $expense] = $this->createUserWithExpense();
        $path = 'expenses/'.$user->id.'/receipt.pdf';
        $expense->update(['receipt_path' => $path, 'receipt_original_name' => 'receipt.pdf']);
        Storage::disk('local')->put($path, 'receipt contents');

        $this->actingAs($user)
            ->delete(route('expenses.destroy', $expense))
            ->assertRedirect(route('expenses.index'))
            ->assertSessionHas('status', 'expense-deleted');

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_user_can_download_their_receipt(): void
    {
        ['user' => $user, 'expense' => $expense] = $this->createUserWithExpense();
        $path = 'expenses/'.$user->id.'/receipt.pdf';
        $expense->update([
            'receipt_path' => $path,
            'receipt_original_name' => 'receipt.pdf',
        ]);
        Storage::disk('local')->put($path, 'receipt contents');

        $this->actingAs($user)
            ->get(route('expenses.receipt', $expense))
            ->assertOk();
    }

    public function test_user_cannot_download_another_users_receipt(): void
    {
        ['expense' => $expense] = $this->createUserWithExpense();
        $path = 'expenses/'.$expense->user_id.'/receipt.pdf';
        $expense->update([
            'receipt_path' => $path,
            'receipt_original_name' => 'receipt.pdf',
        ]);
        Storage::disk('local')->put($path, 'receipt contents');
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('expenses.receipt', $expense))
            ->assertForbidden();
    }
}
