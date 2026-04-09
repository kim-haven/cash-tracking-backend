<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserByAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminOrSuperAdmin() ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $actor = $this->user();
        $assignable = $actor?->isSuperAdmin()
            ? [UserRole::Admin, UserRole::Manager, UserRole::User]
            : [UserRole::Manager, UserRole::User];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(array_map(fn (UserRole $r) => $r->value, $assignable))],
        ];
    }
}
