<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_settings_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('settings.business.edit'));

        $response
            ->assertOk()
            ->assertSee('Business & Tax Profile')
            ->assertSee('VAT registered')
            ->assertSee('Unique Taxpayer Reference');
    }

    public function test_user_can_create_business_profile(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch(route('settings.business.update'), [
                'business_type' => BusinessProfile::TYPE_SOLE_TRADER,
                'trading_name' => 'Smith Plumbing',
                'address' => "12 High Street\nLeeds\nLS1 1AA",
                'vat_registered' => false,
                'utr' => '1234567890',
                'accounting_year_start_month' => 4,
                'accounting_year_start_day' => 6,
                'cis_registered' => true,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('settings.business.edit'));

        $profile = $user->fresh()->businessProfile;

        $this->assertNotNull($profile);
        $this->assertSame(BusinessProfile::TYPE_SOLE_TRADER, $profile->business_type);
        $this->assertSame('Smith Plumbing', $profile->trading_name);
        $this->assertSame("12 High Street\nLeeds\nLS1 1AA", $profile->address);
        $this->assertFalse($profile->vat_registered);
        $this->assertNull($profile->vat_number);
        $this->assertSame('1234567890', $profile->utr);
        $this->assertTrue($profile->cis_registered);
        $this->assertSame(4, $profile->accounting_year_start_month);
        $this->assertSame(6, $profile->accounting_year_start_day);
    }

    public function test_user_can_update_existing_business_profile(): void
    {
        $user = User::factory()->create();
        BusinessProfile::factory()->for($user)->create([
            'trading_name' => 'Old Name',
            'utr' => '1111111111',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('settings.business.update'), [
                'business_type' => BusinessProfile::TYPE_LIMITED_COMPANY,
                'trading_name' => 'New Name Ltd',
                'address' => 'Updated address',
                'vat_registered' => true,
                'vat_number' => 'gb123456789',
                'accounting_year_start_month' => 4,
                'accounting_year_start_day' => 6,
                'cis_registered' => false,
            ]);

        $response->assertSessionHasNoErrors();

        $profile = $user->fresh()->businessProfile;

        $this->assertSame(BusinessProfile::TYPE_LIMITED_COMPANY, $profile->business_type);
        $this->assertSame('New Name Ltd', $profile->trading_name);
        $this->assertSame('GB123456789', $profile->vat_number);
        $this->assertSame('1111111111', $profile->utr);
    }

    public function test_utr_is_not_cleared_when_left_blank_on_update(): void
    {
        $user = User::factory()->create();
        BusinessProfile::factory()->for($user)->create([
            'utr' => '9876543210',
        ]);

        $this
            ->actingAs($user)
            ->patch(route('settings.business.update'), [
                'business_type' => BusinessProfile::TYPE_SOLE_TRADER,
                'trading_name' => 'Kept UTR Trading',
                'address' => '1 Test Road',
                'vat_registered' => false,
                'accounting_year_start_month' => 4,
                'accounting_year_start_day' => 6,
                'cis_registered' => false,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('9876543210', $user->fresh()->businessProfile->utr);
    }

    public function test_vat_number_is_required_when_vat_registered(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('settings.business.edit'))
            ->patch(route('settings.business.update'), [
                'business_type' => BusinessProfile::TYPE_SOLE_TRADER,
                'trading_name' => 'VAT Trader',
                'vat_registered' => true,
                'accounting_year_start_month' => 4,
                'accounting_year_start_day' => 6,
                'cis_registered' => false,
            ]);

        $response
            ->assertSessionHasErrors('vat_number')
            ->assertRedirect(route('settings.business.edit'));
    }

    public function test_utr_must_be_ten_digits(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('settings.business.edit'))
            ->patch(route('settings.business.update'), [
                'business_type' => BusinessProfile::TYPE_SOLE_TRADER,
                'trading_name' => 'Bad UTR',
                'vat_registered' => false,
                'utr' => '12345',
                'accounting_year_start_month' => 4,
                'accounting_year_start_day' => 6,
                'cis_registered' => false,
            ]);

        $response
            ->assertSessionHasErrors('utr')
            ->assertRedirect(route('settings.business.edit'));
    }

    public function test_guest_cannot_access_business_settings(): void
    {
        $this->get(route('settings.business.edit'))->assertRedirect('/login');
    }
}
