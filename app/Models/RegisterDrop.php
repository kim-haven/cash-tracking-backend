<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RegisterDrop extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'date',
        'register',
        'time_start',
        'time_end',
        'action',
        'cash_in',
        'initials',
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
            'cash_in' => 'decimal:2',
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }
}
