<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoftDeleteDropSafeRequest;
use App\Http\Requests\StoreDropSafeRequest;
use App\Http\Requests\UpdateDropSafeRequest;
use App\Http\Resources\DropSafeResource;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\DropSafe;
use Illuminate\Http\Request;

class DropSafesController extends Controller
{
    /**
     * Display a listing of drop safe entries.
     */
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;

        $query = DropSafe::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->orderByDesc('prepared_date')
            ->orderByDesc('id');

        if ($request->filled('bag_no')) {
            $query->where('bag_no', $request->string('bag_no'));
        }

        if ($request->filled('prepared_date')) {
            $query->whereDate('prepared_date', $request->string('prepared_date'));
        }

        return DropSafeResource::collection($query->paginate(50));
    }

    /**
     * Store a newly created drop safe entry.
     */
    public function store(StoreDropSafeRequest $request)
    {
        $dropSafe = DropSafe::create($request->validated());

        return (new DropSafeResource($dropSafe->fresh()))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified drop safe entry.
     */
    public function show(DropSafe $drop_safe)
    {
        return new DropSafeResource($drop_safe);
    }

    /**
     * Update the specified drop safe entry.
     */
    public function update(UpdateDropSafeRequest $request, DropSafe $drop_safe)
    {
        $drop_safe->update($request->validated());

        return new DropSafeResource($drop_safe->refresh());
    }

    /**
     * Soft-delete the specified drop safe entry.
     */
    public function destroy(SoftDeleteDropSafeRequest $request, DropSafe $drop_safe)
    {
        $data = $request->validated();

        $drop_safe->update([
            'is_deleted' => true,
            'deleted_at' => now(),
            'deleted_by' => (string) $data['deleted_by'],
            'delete_reason' => $data['delete_reason'] ?? null,
        ]);

        return response()->noContent();
    }
}
