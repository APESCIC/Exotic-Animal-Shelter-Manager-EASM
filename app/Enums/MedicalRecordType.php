<?php

namespace App\Enums;

enum MedicalRecordType: string
{
    case Vaccination = 'vaccination';
    case Test = 'test';
    case Treatment = 'treatment';

    public function label(): string
    {
        return match ($this) {
            self::Vaccination => 'Vaccination',
            self::Test => 'Test',
            self::Treatment => 'Treatment',
        };
    }
}
