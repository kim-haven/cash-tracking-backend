<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DropSafeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'bag_no' => $this->bag_no,
            'prepared_date' => $this->prepared_date?->format('Y-m-d'),
            'prepared_time' => $this->prepared_time,
            'prepared_by' => $this->prepared_by,
            'prepared_amount' => $this->prepared_amount,
            'courier_date' => $this->courier_date?->format('Y-m-d'),
            'courier_time' => $this->courier_time,
            'courier_given_by' => $this->courier_given_by,
            'courier_received_by' => $this->courier_received_by,
            'courier_amount' => $this->courier_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
