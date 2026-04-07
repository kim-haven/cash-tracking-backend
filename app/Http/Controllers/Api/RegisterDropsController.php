<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTimeOutRequest;
use App\Http\Requests\BulkTimeOutUpdateRequest;
use App\Http\Requests\SoftDeleteRegisterDropRequest;
use App\Http\Requests\StoreRegisterDropRequest;
use App\Http\Requests\UpdateRegisterDropRequest;
use App\Http\Resources\RegisterDropResource;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\RegisterDrop;
use Illuminate\Http\Request;

class RegisterDropsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;

        $query = RegisterDrop::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->orderByDesc('date')
            ->orderByDesc('time_start')
            ->orderByDesc('id');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date'));
        }

        if ($request->filled('register')) {
            $query->where('register', $request->string('register'));
        }

        return RegisterDropResource::collection($query->paginate(50));
    }

    /**
     * Set time_out (persisted as time_end) for a row identified by id.
     */
    public function addTimeOut(AddTimeOutRequest $request)
    {
        $data = $request->validated();

        $registerDrop = RegisterDrop::query()->findOrFail($data['id']);
        $registerDrop->update(['time_end' => $data['time_out']]);

        return new RegisterDropResource($registerDrop->fresh());
    }

    /**
     * Set the same time_out (persisted as time_end) on two or more rows by id.
     */
    public function bulkTimeOutUpdate(BulkTimeOutUpdateRequest $request)
    {
        $data = $request->validated();
        $ids = $data['ids'];

        RegisterDrop::query()->whereIn('id', $ids)->update([
            'time_end' => $data['time_out'],
        ]);

        $updated = RegisterDrop::query()
            ->whereIn('id', $ids)
            ->orderByDesc('date')
            ->orderByDesc('time_start')
            ->orderByDesc('id')
            ->get();

        return RegisterDropResource::collection($updated);
    }

    /**
     * Store a new register drop (payload from the React app).
     */
    public function store(StoreRegisterDropRequest $request)
    {
        $registerDrop = RegisterDrop::create($request->validated());

        return (new RegisterDropResource($registerDrop->fresh()))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RegisterDrop $register_drop)
    {
        return new RegisterDropResource($register_drop);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegisterDropRequest $request, RegisterDrop $register_drop)
    {
        $register_drop->update($request->validated());

        return new RegisterDropResource($register_drop->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SoftDeleteRegisterDropRequest $request, RegisterDrop $register_drop)
    {
        $data = $request->validated();

        $register_drop->update([
            'is_deleted' => true,
            'deleted_at' => now(),
            'deleted_by' => (string) $data['deleted_by'],
            'delete_reason' => $data['delete_reason'] ?? null,
        ]);

        return response()->noContent();
    }
}
