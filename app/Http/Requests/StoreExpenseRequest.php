<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreExpenseRequest extends ExpenseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Expense::class);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->expenseRules();
    }
}
