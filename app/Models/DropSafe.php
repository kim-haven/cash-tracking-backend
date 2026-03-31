<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DropSafe extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'bag_no',
        'prepared_date',
        'prepared_time',
        'prepared_by',
        'prepared_amount',
        'courier_date',
        'courier_time',
        'courier_given_by',
        'courier_received_by',
        'courier_amount',
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
            'prepared_date' => 'date',
            'prepared_amount' => 'decimal:2',
            'courier_date' => 'date',
            'courier_amount' => 'decimal:2',
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }
}
