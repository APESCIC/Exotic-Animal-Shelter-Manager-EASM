<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class UkDate
{
    /**
     * Format a date for UK staff UI as dd/mm/yyyy.
     */
    public static function format(CarbonInterface|string|null $date): string
    {
        if ($date === null || $date === '') {
            return '';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->timezone(config('app.timezone'))->format('d/m/Y');
    }
}
