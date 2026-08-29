<?php

namespace App\Enums;

enum AnimalSex: string
{
    case Male = 'male';
    case Female = 'female';
    case Unknown = 'unknown';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Male',
            self::Female => 'Female',
            self::Unknown => 'Unknown',
            self::Mixed => 'Mixed',
        };
    }
}
