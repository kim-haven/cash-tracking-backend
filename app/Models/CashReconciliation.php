<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashReconciliation extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'store_id',
        'date',
        'controller',
        'cash_in',
        'cash_refunds',
        'cashless_atm_cash_back',
        'reported_cash_collected',
        'cash_collected',
        'cash_difference',
        'credit_difference',
        'cashless_atm_difference',
        'cash_vs_cashless_atm_difference',
        'reason_notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'cash_in' => 'decimal:2',
            'cash_refunds' => 'decimal:2',
            'cashless_atm_cash_back' => 'decimal:2',
            'reported_cash_collected' => 'decimal:2',
            'cash_collected' => 'decimal:2',
            'cash_difference' => 'decimal:2',
            'credit_difference' => 'decimal:2',
            'cashless_atm_difference' => 'decimal:2',
            'cash_vs_cashless_atm_difference' => 'decimal:2',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
