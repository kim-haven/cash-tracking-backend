<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterDropResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date?->format('Y-m-d'),
            'register' => $this->register,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'time_out' => $this->time_end,
            'action' => $this->action,
            'cash_in' => $this->cash_in,
            'initials' => $this->initials,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
