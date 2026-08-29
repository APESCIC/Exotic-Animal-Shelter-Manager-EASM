<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'organisation_name',
        'locale',
        'timezone',
    ];

    /**
     * Single-row shelter settings for this install.
     */
    public static function current(): ?self
    {
        if (! Schema::hasTable('settings')) {
            return null;
        }

        return static::query()->first();
    }

    /**
     * Apply organisation settings to the running app config.
     */
    public function applyToConfig(): void
    {
        config([
            'app.name' => $this->organisation_name,
            'app.locale' => $this->locale,
            'app.fallback_locale' => $this->locale,
            'app.faker_locale' => $this->locale,
            'app.timezone' => $this->timezone,
        ]);

        date_default_timezone_set($this->timezone);
    }
}
