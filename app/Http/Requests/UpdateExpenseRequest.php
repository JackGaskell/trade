<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateExpenseRequest extends ExpenseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('expense'));
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->expenseRules(updating: true);
    }
}
