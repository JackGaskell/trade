<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBusinessProfileRequest;
use App\Models\BusinessProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $profile = $request->user()->businessProfile ?? new BusinessProfile([
            'business_type' => BusinessProfile::TYPE_SOLE_TRADER,
            'accounting_year_start_month' => 4,
            'accounting_year_start_day' => 6,
            'vat_registered' => false,
            'cis_registered' => false,
        ]);

        return view('settings.business.edit', [
            'profile' => $profile,
        ]);
    }

    public function update(UpdateBusinessProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if (! $validated['vat_registered']) {
            $validated['vat_number'] = null;
        }

        if (! $request->filled('utr')) {
            unset($validated['utr']);
        }

        $profile = $user->businessProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated,
        );

        return redirect()
            ->route('settings.business.edit')
            ->with('status', 'business-profile-updated');
    }
}
