<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlazeAccountingSummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = ['id' => $this->id];

        foreach ($this->resource->getFillable() as $key) {
            if ($key === 'date') {
                $data['date'] = $this->date?->format('Y-m-d');

                continue;
            }
            $data[$key] = $this->{$key};
        }

        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;

        return $data;
    }
}
