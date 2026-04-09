<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'superadmin';
    case Admin = 'admin';
    case Manager = 'manager';
    case User = 'user';

    /** Human-readable label (aligned with the SPA). */
    public function displayLabel(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super admin',
            self::Admin => 'Admin',
            self::Manager => 'Manager',
            self::User => 'User',
        };
    }
}
