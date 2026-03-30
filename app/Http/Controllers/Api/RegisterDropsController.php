<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTimeOutRequest;
use App\Http\Requests\BulkTimeOutUpdateRequest;
use App\Http\Requests\StoreRegisterDropRequest;
use App\Http\Resources\RegisterDropResource;
use App\Models\RegisterDrop;
use Illuminate\Http\Request;

class RegisterDropsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RegisterDrop::query()
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
    public function update(Request $request, RegisterDrop $register_drop)
    {
        $register_drop->update($this->rules($request, sometimes: true));

        return new RegisterDropResource($register_drop->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegisterDrop $register_drop)
    {
        $register_drop->delete();

        return response()->noContent();
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(Request $request, bool $sometimes = false): array
    {
        $prefix = $sometimes ? 'sometimes|' : '';

        return $request->validate([
            'date' => $prefix.'required|date',
            'register' => $prefix.'required|string|max:255',
            'time_start' => $prefix.'required|date_format:H:i:s',
            'time_end' => ($sometimes ? 'sometimes|' : '').'nullable|date_format:H:i:s',
            'action' => $prefix.'required|string|max:255',
            'cash_in' => $prefix.'required|numeric',
            'initials' => $prefix.'required|string|max:16',
            'notes' => ($sometimes ? 'sometimes|' : '').'nullable|string',
        ]);
    }
}
