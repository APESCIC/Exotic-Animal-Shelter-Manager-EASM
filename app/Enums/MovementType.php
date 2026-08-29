<?php

namespace App\Enums;

enum MovementType: string
{
    case Intake = 'intake';
    case Hold = 'hold';
    case Quarantine = 'quarantine';
    case Foster = 'foster';
    case TrialAdoption = 'trial_adoption';
    case Adoption = 'adoption';
    case Reclaim = 'reclaim';
    case Transfer = 'transfer';
    case Deceased = 'deceased';

    public function label(): string
    {
        return match ($this) {
            self::Intake => 'Intake',
            self::Hold => 'Hold',
            self::Quarantine => 'Quarantine',
            self::Foster => 'Foster',
            self::TrialAdoption => 'Trial adoption',
            self::Adoption => 'Adoption',
            self::Reclaim => 'Reclaim',
            self::Transfer => 'Transfer',
            self::Deceased => 'Deceased',
        };
    }

    public function typicallyNeedsPerson(): bool
    {
        return match ($this) {
            self::Foster, self::TrialAdoption, self::Adoption, self::Reclaim, self::Transfer => true,
            self::Intake, self::Hold, self::Quarantine, self::Deceased => false,
        };
    }
}
