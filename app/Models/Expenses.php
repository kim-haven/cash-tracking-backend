<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'date',
        'paid_by',
        'pay_to',
        'approved_by',
        'receipt_uploaded',
        'type',
        'description',
        'cash_in',
        'cash_out',
    ];

    protected $casts = [
        'date' => 'date',
        'receipt_uploaded' => 'boolean',
        'cash_in' => 'decimal:2',
        'cash_out' => 'decimal:2',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function tips(): HasMany
    {
        return $this->hasMany(Tip::class, 'expense_id');
    }
}
