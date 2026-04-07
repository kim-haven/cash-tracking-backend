<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlazeAccountingSummaryResource;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\BlazeAccountingSummary;
use Illuminate\Http\Request;

class BlazeAccountingSummariesController extends Controller
{
    /**
     * List Blaze accounting summary rows (paginated).
     */
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;

        $query = BlazeAccountingSummary::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date'));
        }

        if ($request->filled('shop')) {
            $query->where('shop', $request->string('shop'));
        }

        if ($request->filled('company')) {
            $query->where('company', $request->string('company'));
        }

        if ($request->filled('queue_type')) {
            $query->where('queue_type', $request->string('queue_type'));
        }

        return BlazeAccountingSummaryResource::collection($query->paginate(50));
    }
}
