<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserByAdminRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use App\Services\UserNotificationSync;
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
     * Assign or change a user's role. Superadmin can manage all roles; admins only manager/user (and each other’s non-elevated targets).
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        $actor = $request->user();
        if (! $actor) {
            abort(403);
        }

        /** @var UserRole $newRole */
        $newRole = UserRole::from($request->validated('role'));

        if ($user->isSuperAdmin()) {
            throw ValidationException::withMessages([
                'role' => ['The super administrator role cannot be changed.'],
            ]);
        }

        $targetIsElevated = $user->isAdmin();
        if ($targetIsElevated && ! $actor->isSuperAdmin()) {
            throw ValidationException::withMessages([
                'role' => ['Only the super admin can change roles for administrators.'],
            ]);
        }

        $newIsAdmin = $newRole === UserRole::Admin;
        if ($newIsAdmin && ! $actor->isSuperAdmin()) {
            throw ValidationException::withMessages([
                'role' => ['Only the super admin can assign the admin role.'],
            ]);
        }

        if ($user->isAdmin() && $newRole !== UserRole::Admin) {
            $adminCount = User::query()->where('role', UserRole::Admin)->count();
            if ($adminCount <= 1) {
                $superCount = User::query()->where('role', UserRole::SuperAdmin)->count();
                if ($superCount < 1) {
                    throw ValidationException::withMessages([
                        'role' => ['At least one administrator or super administrator is required.'],
                    ]);
                }
            }
        }

        $previousRole = $user->role;
        $user->role = $newRole;
        $user->save();

        if ($previousRole !== $newRole) {
            UserNotificationSync::notifyRoleChanged($user, $newRole);
        }

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

    /**
     * Remove a user (admin only). Cannot delete yourself or the last super admin.
     */
    public function destroy(User $user): JsonResponse
    {
        $current = request()->user();
        if ($current && $current->id === $user->id) {
            throw ValidationException::withMessages([
                'user' => ['You cannot delete your own account.'],
            ]);
        }

        $targetIsElevated = $user->isSuperAdmin() || $user->isAdmin();
        if ($targetIsElevated && $current && ! $current->isSuperAdmin()) {
            throw ValidationException::withMessages([
                'user' => ['Only the super admin can delete administrator accounts.'],
            ]);
        }

        if ($user->isSuperAdmin()) {
            $superCount = User::query()->where('role', UserRole::SuperAdmin)->count();
            if ($superCount <= 1) {
                throw ValidationException::withMessages([
                    'user' => ['At least one super administrator is required.'],
                ]);
            }
        }

        if ($user->isAdmin()) {
            $adminCount = User::query()->where('role', UserRole::Admin)->count();
            if ($adminCount <= 1) {
                $superCount = User::query()->where('role', UserRole::SuperAdmin)->count();
                if ($superCount < 1) {
                    throw ValidationException::withMessages([
                        'user' => ['At least one administrator or super administrator is required.'],
                    ]);
                }
            }
        }

        $user->delete();

        return response()->json(['message' => 'User deleted.']);
    }
}
