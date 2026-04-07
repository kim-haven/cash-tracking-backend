<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashlessAtmEntryResource extends JsonResource
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
            'employee' => $this->employee,
            'terminal' => $this->terminal,
            'debit_terminal_total_dispensed' => $this->debit_terminal_total_dispensed,
            'total_tips' => $this->total_tips,
            'debit_total_sales' => $this->debit_total_sales,
            'total_cash_back' => $this->total_cash_back,
            'blaze_total_cash_less_sales' => $this->blaze_total_cash_less_sales,
            'total_cash_less_atm_change' => $this->total_cash_less_atm_change,
            'total_sales_difference' => $this->totalSalesDifference(),
            'cashback_difference' => $this->cashbackDifference(),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
