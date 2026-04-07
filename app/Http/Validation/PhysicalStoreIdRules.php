<?php

namespace App\Http\Validation;

use Illuminate\Validation\Rule;

final class PhysicalStoreIdRules
{
    /**
     * Query string: `?store_id=` for listing and aggregate endpoints (excludes "All Stores" row).
     *
     * @return array<string, array<int, mixed>>
     */
    public static function queryParameter(): array
    {
        return [
            'store_id' => [
                'required',
                'integer',
                Rule::exists('stores', 'id')->where(fn ($q) => $q->where('is_all_stores', false)),
            ],
        ];
    }

    /**
     * Optional query: omit or null = all physical stores; set = filter to one store.
     *
     * @return array<string, array<int, mixed>>
     */
    public static function optionalQueryParameter(): array
    {
        return [
            'store_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('stores', 'id')->where(fn ($q) => $q->where('is_all_stores', false)),
            ],
        ];
    }

    /**
     * Required body/query field on create.
     *
     * @return array<string, array<int, mixed>>
     */
    public static function requiredAttribute(): array
    {
        return [
            'store_id' => [
                'required',
                'integer',
                Rule::exists('stores', 'id')->where(fn ($q) => $q->where('is_all_stores', false)),
            ],
        ];
    }

    /**
     * Partial update: only physical stores, when present.
     *
     * @return array<string, array<int, mixed>>
     */
    public static function optionalAttribute(): array
    {
        return [
            'store_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('stores', 'id')->where(fn ($q) => $q->where('is_all_stores', false)),
            ],
        ];
    }
}
