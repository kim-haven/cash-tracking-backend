<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tip extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'store_id',
        'initials',
        'cash_tip_amount',
        'end_of_pay_period_total',
        'cash_balance',
        'date',
        'cash_tip',
        'credit_tips',
        'ach_tips',
        'debit_tips',
        'total',
        'note',
        'expense_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'cash_tip_amount' => 'decimal:2',
            'end_of_pay_period_total' => 'decimal:2',
            'cash_balance' => 'decimal:2',
            'cash_tip' => 'decimal:2',
            'credit_tips' => 'decimal:2',
            'ach_tips' => 'decimal:2',
            'debit_tips' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expenses::class, 'expense_id');
    }
}
