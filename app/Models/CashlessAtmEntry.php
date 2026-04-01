<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CashlessAtmEntry extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'date',
        'employee',
        'terminal',
        'debit_terminal_total_dispensed',
        'total_tips',
        'debit_total_sales',
        'total_cash_back',
        'blaze_total_cash_less_sales',
        'total_cash_less_atm_change',
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
            'debit_terminal_total_dispensed' => 'decimal:2',
            'total_tips' => 'decimal:2',
            'debit_total_sales' => 'decimal:2',
            'total_cash_back' => 'decimal:2',
            'blaze_total_cash_less_sales' => 'decimal:2',
            'total_cash_less_atm_change' => 'decimal:2',
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Blaze cashless sales minus debit total sales (not persisted).
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
     * Total cashless ATM change minus total cash back (not persisted).
     */
    public function cashbackDifference(): string
    {
        return bcsub(
            (string) $this->total_cash_less_atm_change,
            (string) $this->total_cash_back,
            2
        );
    }
}
