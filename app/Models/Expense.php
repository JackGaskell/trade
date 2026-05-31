<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'user_id',
    'job_id',
    'expense_date',
    'supplier',
    'description',
    'category',
    'amount',
    'vat_amount',
    'receipt_path',
    'receipt_original_name',
])]
class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    public const CATEGORY_MATERIALS_STOCK = 'materials_stock';

    public const CATEGORY_TOOLS_EQUIPMENT = 'tools_equipment';

    public const CATEGORY_VEHICLE_TRAVEL = 'vehicle_travel';

    public const CATEGORY_SUBCONTRACTORS = 'subcontractors';

    public const CATEGORY_INSURANCE = 'insurance';

    public const CATEGORY_OFFICE_PHONE_SOFTWARE = 'office_phone_software';

    public const CATEGORY_PROFESSIONAL_FEES = 'professional_fees';

    public const CATEGORIES = [
        self::CATEGORY_MATERIALS_STOCK,
        self::CATEGORY_TOOLS_EQUIPMENT,
        self::CATEGORY_VEHICLE_TRAVEL,
        self::CATEGORY_SUBCONTRACTORS,
        self::CATEGORY_INSURANCE,
        self::CATEGORY_OFFICE_PHONE_SOFTWARE,
        self::CATEGORY_PROFESSIONAL_FEES,
    ];

    public const CATEGORY_LABELS = [
        self::CATEGORY_MATERIALS_STOCK => 'Materials & stock',
        self::CATEGORY_TOOLS_EQUIPMENT => 'Tools & equipment',
        self::CATEGORY_VEHICLE_TRAVEL => 'Vehicle & travel',
        self::CATEGORY_SUBCONTRACTORS => 'Subcontractors',
        self::CATEGORY_INSURANCE => 'Insurance',
        self::CATEGORY_OFFICE_PHONE_SOFTWARE => 'Office / phone / software',
        self::CATEGORY_PROFESSIONAL_FEES => 'Professional fees',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'decimal:2',
            'vat_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Expense $expense) {
            $expense->deleteReceipt();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function categoryLabel(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function formattedAmount(): string
    {
        return Invoice::formatMoney($this->amount);
    }

    public function formattedVatAmount(): ?string
    {
        return $this->vat_amount !== null ? Invoice::formatMoney($this->vat_amount) : null;
    }

    public function hasReceipt(): bool
    {
        return filled($this->receipt_path);
    }

    public function deleteReceipt(): void
    {
        if ($this->receipt_path && Storage::disk('local')->exists($this->receipt_path)) {
            Storage::disk('local')->delete($this->receipt_path);
        }
    }
}
