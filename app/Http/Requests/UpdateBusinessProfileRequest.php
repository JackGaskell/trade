<?php

namespace App\Http\Requests;

use App\Models\BusinessProfile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusinessProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_type' => ['required', Rule::in(BusinessProfile::BUSINESS_TYPES)],
            'trading_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'vat_registered' => ['required', 'boolean'],
            'vat_number' => [
                'nullable',
                'required_if:vat_registered,true',
                'string',
                'max:20',
                'regex:/^GB[0-9]{9}$|^GB[0-9]{12}$|^GBGD[0-9]{3}$|^GBHA[0-9]{3}$/i',
            ],
            'utr' => ['nullable', 'string', 'regex:/^[0-9]{10}$/'],
            'accounting_year_start_month' => ['required', 'integer', 'min:1', 'max:12'],
            'accounting_year_start_day' => ['required', 'integer', 'min:1', 'max:31'],
            'cis_registered' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'vat_number.required_if' => 'A VAT number is required when you are VAT registered.',
            'vat_number.regex' => 'Enter a valid UK VAT number (e.g. GB123456789).',
            'utr.regex' => 'Your UTR must be exactly 10 digits.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $vatNumber = $this->input('vat_number');

        $this->merge([
            'vat_registered' => $this->boolean('vat_registered'),
            'cis_registered' => $this->boolean('cis_registered'),
            'vat_number' => is_string($vatNumber) ? strtoupper(str_replace(' ', '', $vatNumber)) : $vatNumber,
            'utr' => is_string($this->input('utr')) ? preg_replace('/\s+/', '', $this->input('utr')) : $this->input('utr'),
        ]);
    }
}
