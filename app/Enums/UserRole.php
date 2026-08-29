<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Staff = 'staff';
    case Volunteer = 'volunteer';
    case Readonly = 'readonly';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Staff => 'Staff',
            self::Volunteer => 'Volunteer',
            self::Readonly => 'Read only',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
