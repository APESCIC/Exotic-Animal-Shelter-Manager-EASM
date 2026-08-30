<?php

namespace App\Enums;

enum LostFoundType: string
{
    case Lost = 'lost';
    case Found = 'found';

    public function label(): string
    {
        return match ($this) {
            self::Lost => 'Lost',
            self::Found => 'Found',
        };
    }
}
