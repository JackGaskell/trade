<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'client_id', 'title', 'description', 'address', 'scheduled_date', 'status', 'notes'])]
class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    protected $table = 'trade_jobs';

    public const STATUS_ENQUIRY = 'enquiry';

    public const STATUS_QUOTED = 'quoted';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_ENQUIRY,
        self::STATUS_QUOTED,
        self::STATUS_ACCEPTED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public const STATUS_LABELS = [
        self::STATUS_ENQUIRY => 'Enquiry',
        self::STATUS_QUOTED => 'Quoted',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }
}
