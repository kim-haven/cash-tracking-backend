<?php

namespace App\Http\Requests\Concerns;

use Carbon\Carbon;
use Throwable;

trait NormalizesDropSafeInput
{
    protected function mergeNormalizedDropSafeInput(): void
    {
        $merge = [];

        foreach (['prepared_time', 'courier_time'] as $field) {
            if (! $this->has($field)) {
                continue;
            }
            $value = $this->input($field);
            if ($value === '' || $value === null) {
                $merge[$field] = null;
            } elseif (is_string($value)) {
                $merge[$field] = self::normalizeTime(trim($value));
            }
        }

        foreach (['courier_date', 'courier_given_by', 'courier_received_by', 'courier_amount'] as $field) {
            if ($this->has($field) && $this->input($field) === '') {
                $merge[$field] = null;
            }
        }

        if ($this->has('prepared_time') && $this->input('prepared_time') === '') {
            $merge['prepared_time'] = null;
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    private static function normalizeTime(string $value): string
    {
        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (Throwable) {
            return self::padTimeString($value);
        }
    }

    private static function padTimeString(string $value): string
    {
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $value, $m)) {
            return sprintf('%02d:%02d:00', (int) $m[1], (int) $m[2]);
        }

        if (preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $value, $m)) {
            return sprintf('%02d:%02d:%02d', (int) $m[1], (int) $m[2], (int) $m[3]);
        }

        return $value;
    }
}
