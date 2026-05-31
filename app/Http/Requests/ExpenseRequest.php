<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class ExpenseRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    protected function expenseRules(bool $updating = false): array
    {
        $receiptRule = $updating
            ? ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']
            : ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];

        return [
            'expense_date' => ['required', 'date'],
            'supplier' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['required', 'string', Rule::in(Expense::CATEGORIES)],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
            'vat_amount' => ['nullable', 'numeric', 'min:0', 'lte:amount'],
            'job_id' => [
                'nullable',
                'integer',
                Rule::exists('trade_jobs', 'id')->where('user_id', $this->user()->id),
            ],
            'receipt' => $receiptRule,
            'remove_receipt' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $vatRegistered = (bool) $this->user()->businessProfile?->vat_registered;

        $merge = [
            'remove_receipt' => $this->boolean('remove_receipt'),
        ];

        if (! $vatRegistered) {
            $merge['vat_amount'] = null;
        }

        $this->merge($merge);
    }
}
