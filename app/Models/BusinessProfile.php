<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'business_type',
    'trading_name',
    'address',
    'vat_registered',
    'vat_number',
    'utr',
    'accounting_year_start_month',
    'accounting_year_start_day',
    'cis_registered',
])]
class BusinessProfile extends Model
{
    /** @use HasFactory<\Database\Factories\BusinessProfileFactory> */
    use HasFactory;

    public const TYPE_SOLE_TRADER = 'sole_trader';

    public const TYPE_PARTNERSHIP = 'partnership';

    public const TYPE_LIMITED_COMPANY = 'limited_company';

    public const BUSINESS_TYPES = [
        self::TYPE_SOLE_TRADER,
        self::TYPE_PARTNERSHIP,
        self::TYPE_LIMITED_COMPANY,
    ];

    public const BUSINESS_TYPE_LABELS = [
        self::TYPE_SOLE_TRADER => 'Sole trader',
        self::TYPE_PARTNERSHIP => 'Partnership',
        self::TYPE_LIMITED_COMPANY => 'Limited company',
    ];

    protected function casts(): array
    {
        return [
            'vat_registered' => 'boolean',
            'cis_registered' => 'boolean',
            'utr' => 'encrypted',
            'accounting_year_start_month' => 'integer',
            'accounting_year_start_day' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function businessTypeLabel(): string
    {
        return self::BUSINESS_TYPE_LABELS[$this->business_type] ?? ucfirst(str_replace('_', ' ', $this->business_type));
    }

    public function accountingYearLabel(): string
    {
        $start = sprintf('%02d-%02d', $this->accounting_year_start_month, $this->accounting_year_start_day);
        $startDate = \Carbon\Carbon::createFromFormat('m-d', $start);
        $endDate = $startDate->copy()->addYear()->subDay();

        return $startDate->format('j F').' – '.$endDate->format('j F');
    }

    public function isComplete(): bool
    {
        return filled($this->business_type)
            && filled($this->trading_name)
            && filled($this->address);
    }
}
