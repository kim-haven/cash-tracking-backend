<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserByAdminRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AdminUsersController extends Controller
{
    /**
     * List users (admin only).
     */
    public function index(): JsonResponse
    {
        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        return response()->json([
            'data' => $users->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role->value,
            ]),
        ]);
    }

    /**
     * Create a user with a chosen role (admin only).
     */
    public function store(StoreUserByAdminRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $role = $validated['role'];
        unset($validated['role']);

        $user = User::create([
            ...$validated,
            'role' => $role,
        ]);

        return response()->json([
            'message' => 'User created.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->value,
            ],
        ], 201);
    }

    /**
     * Assign or change a user's role (admin only). Admins can set roles for users and managers (and other admins).
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        /** @var UserRole $newRole */
        $newRole = $request->validated('role');

        if ($user->isAdmin() && $newRole !== UserRole::Admin) {
            $adminCount = User::query()->where('role', UserRole::Admin)->count();
            if ($adminCount <= 1) {
                throw ValidationException::withMessages([
                    'role' => ['At least one administrator is required.'],
                ]);
            }
        }

        $user->role = $newRole;
        $user->save();

        return response()->json([
            'message' => 'Role updated.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->value,
            ],
        ]);
    }
}
