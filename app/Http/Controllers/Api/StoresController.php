<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertStoreRequest;
use App\Models\Store;
use Illuminate\Http\JsonResponse;

class StoresController extends Controller
{
    /**
     * List stores in canonical order (see {@see Store::NAMES}).
     */
    public function index(): JsonResponse
    {
        $order = array_flip(Store::NAMES);

        $stores = Store::query()
            ->get()
            ->sortBy(fn (Store $store) => $order[$store->name] ?? count(Store::NAMES))
            ->values();

        return response()->json([
            'data' => $stores,
        ]);
    }

    public function store(UpsertStoreRequest $request): JsonResponse
    {
        $store = Store::create($request->validated());

        return response()->json([
            'message' => 'Store created successfully',
            'data' => $store,
        ], 201);
    }

    public function show(Store $store): JsonResponse
    {
        return response()->json([
            'data' => $store,
        ]);
    }

    public function update(UpsertStoreRequest $request, Store $store): JsonResponse
    {
        $store->update($request->validated());

        return response()->json([
            'message' => 'Store updated successfully',
            'data' => $store,
        ]);
    }

    public function destroy(Store $store): JsonResponse
    {
        if ($store->is_all_stores) {
            return response()->json([
                'message' => 'The All Stores option cannot be deleted.',
            ], 422);
        }

        $store->delete();

        return response()->json([
            'message' => 'Store deleted successfully',
        ]);
    }
}
