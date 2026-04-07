<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    /**
     * Canonical store labels in display order (matches frontend).
     * "All Stores" is the aggregate option and is stored with `is_all_stores` = true.
     *
     * @var list<string>
     */
    public const NAMES = [
        'All Stores',
        'Belmont',
        'DTLB',
        'Fresno',
        'Lakewood',
        'Los Alamitos',
        'Maywood',
        'Orange County',
        'Paramount',
        'Porterville',
        'San Bernardino',
        'Hawthorn',
        'Corona',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'is_all_stores',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_all_stores' => 'boolean',
        ];
    }

    public function registerDrops(): HasMany
    {
        return $this->hasMany(RegisterDrop::class);
    }

    public function dropSafes(): HasMany
    {
        return $this->hasMany(DropSafe::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expenses::class);
    }

    public function cashlessAtmEntries(): HasMany
    {
        return $this->hasMany(CashlessAtmEntry::class);
    }

    public function cashlessAtmReconciles(): HasMany
    {
        return $this->hasMany(CashlessAtmReconcile::class);
    }

    public function cashReconciliations(): HasMany
    {
        return $this->hasMany(CashReconciliation::class);
    }

    public function tips(): HasMany
    {
        return $this->hasMany(Tip::class);
    }

    public function blazeAccountingSummaries(): HasMany
    {
        return $this->hasMany(BlazeAccountingSummary::class);
    }
}
