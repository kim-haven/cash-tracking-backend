<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashlessAtmReconcileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'date' => $this->date?->format('Y-m-d'),
            'debit_total_sales' => $this->debit_total_sales,
            'blaze_total_cash_less_sales' => $this->blaze_total_cash_less_sales,
            'total_cashless_atm_tendered' => $this->total_cashless_atm_tendered,
            'total_cash_less_atm_change' => $this->total_cash_less_atm_change,
            'total_cash_back' => $this->total_cash_back,
            'total_sales_difference' => $this->totalSalesDifference(),
            'cashback_difference' => $this->cashbackDifference(),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
