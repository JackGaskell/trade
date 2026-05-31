<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['job_id', 'invoice_number', 'amount', 'due_date', 'status', 'notes'])]
class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SENT = 'sent';

    public const STATUS_PAID = 'paid';

    public const STATUS_OVERDUE = 'overdue';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SENT,
        self::STATUS_PAID,
        self::STATUS_OVERDUE,
    ];

    public const UNPAID_STATUSES = [
        self::STATUS_SENT,
        self::STATUS_OVERDUE,
    ];

    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_SENT => 'Sent',
        self::STATUS_PAID => 'Paid',
        self::STATUS_OVERDUE => 'Overdue',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = static::generateInvoiceNumber($invoice->job_id);
            }
        });
    }

    public static function generateInvoiceNumber(int $jobId): string
    {
        $userId = Job::query()->whereKey($jobId)->value('user_id');
        $year = now()->year;

        $count = static::query()
            ->whereHas('job', fn ($query) => $query->where('user_id', $userId))
            ->whereYear('created_at', $year)
            ->count() + 1;

        return sprintf('INV-%d-%04d', $year, $count);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function isUnpaid(): bool
    {
        return in_array($this->status, self::UNPAID_STATUSES, true);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function formattedAmount(): string
    {
        return '£'.number_format((float) $this->amount, 2);
    }

    public static function formatMoney(float|string $amount): string
    {
        return '£'.number_format((float) $amount, 2);
    }
}
