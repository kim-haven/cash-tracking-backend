<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangeBank extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'store_id',
        'date',
        'count_amount',
        'change_in',
        'change_out',
        'description',
        'deposit',
        'picked_up',
        'sum_of_pickups',
        'balance',
        'difference',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'count_amount' => 'decimal:2',
            'change_in' => 'decimal:2',
            'change_out' => 'decimal:2',
            'deposit' => 'decimal:2',
            'picked_up' => 'decimal:2',
            'sum_of_pickups' => 'decimal:2',
            'balance' => 'decimal:2',
            'difference' => 'decimal:2',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
