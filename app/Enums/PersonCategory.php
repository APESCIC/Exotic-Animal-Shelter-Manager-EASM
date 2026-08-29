<?php

namespace App\Enums;

enum PersonCategory: string
{
    case Adopter = 'adopter';
    case Foster = 'foster';
    case Vet = 'vet';
    case Volunteer = 'volunteer';
    case Staff = 'staff';
    case Donor = 'donor';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::Adopter => 'Adopter',
            self::Foster => 'Foster',
            self::Vet => 'Vet',
            self::Volunteer => 'Volunteer',
            self::Staff => 'Staff',
            self::Donor => 'Donor',
            self::Custom => 'Custom',
        };
    }
}
