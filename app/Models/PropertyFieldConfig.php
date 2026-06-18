<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PropertyFieldConfig extends Model
{
    protected $fillable = [
        'field_key',
        'keep_field',
        'mandatory_field',
        'show_on_website',
        'show_after_verification',
    ];

    protected $casts = [
        'keep_field'               => 'boolean',
        'mandatory_field'          => 'boolean',
        'show_on_website'          => 'boolean',
        'show_after_verification'  => 'boolean',
    ];

    // ── Static cache — loaded once per request ────────────────────────────────

    /** @var Collection<string, self>|null */
    private static ?Collection $cache = null;

    /**
     * Return all configs keyed by field_key.
     * Falls back to an empty collection (no exceptions) when the table is empty.
     *
     * @return Collection<string, self>
     */
    public static function allKeyed(): Collection
    {
        if (static::$cache === null) {
            try {
                static::$cache = static::all()->keyBy('field_key');
            } catch (\Throwable) {
                static::$cache = collect();
            }
        }

        return static::$cache;
    }

    /**
     * Get the config for a single field_key.
     * Returns a sensible default (keep=true, mandatory=true, web=false, verif=false)
     * when no config row exists so forms never break.
     */
    public static function forField(string $key): object
    {
        $config = static::allKeyed()->get($key);

        if ($config) {
            return $config;
        }

        // Fallback object — all fields required, nothing hidden
        return (object) [
            'keep_field'              => true,
            'mandatory_field'         => true,
            'show_on_website'         => false,
            'show_after_verification' => false,
        ];
    }

    /**
     * Flush the in-memory cache (useful in tests / seeders).
     */
    public static function flushCache(): void
    {
        static::$cache = null;
    }
}
