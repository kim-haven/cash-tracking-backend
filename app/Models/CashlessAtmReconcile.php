<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CashlessAtmReconcile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'date',
        'debit_total_sales',
        'blaze_total_cash_less_sales',
        'total_cashless_atm_tendered',
        'total_cash_less_atm_change',
        'total_cash_back',
        'notes',
        'is_deleted',
        'deleted_at',
        'deleted_by',
        'delete_reason',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('not_deleted', function (Builder $builder): void {
            $builder->where('is_deleted', false)->whereNull('deleted_at');
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'debit_total_sales' => 'decimal:2',
            'blaze_total_cash_less_sales' => 'decimal:2',
            'total_cashless_atm_tendered' => 'decimal:2',
            'total_cash_less_atm_change' => 'decimal:2',
            'total_cash_back' => 'decimal:2',
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Blaze cashless sales minus debit terminal total (not persisted).
     */
    public function totalSalesDifference(): string
    {
        return bcsub(
            (string) $this->blaze_total_cash_less_sales,
            (string) $this->debit_total_sales,
            2
        );
    }

    /**
     * Cashless ATM tendered minus cashback (not persisted). Matches pivot: Col I − Col K.
     */
    public function cashbackDifference(): string
    {
        return bcsub(
            (string) $this->total_cashless_atm_tendered,
            (string) $this->total_cash_back,
            2
        );
    }
}
