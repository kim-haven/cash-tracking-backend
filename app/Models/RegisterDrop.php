<?php

namespace App\Models;

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
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'cash_in' => 'decimal:2',
        ];
    }
}
