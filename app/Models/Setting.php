<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    // --- Static helpers ---

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::find($key);
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Check if the restaurant is currently open.
     * Returns true when open, false when closed.
     */
    public static function isOpen(): bool
    {
        // Manual override: force open
        if ((bool) static::get('is_force_opened', false)) {
            return true;
        }

        // Manual override: force closed
        if ((bool) static::get('is_manually_closed', false)) {
            return false;
        }

        $openingTime = static::get('opening_time', '13:00');
        $closingTime = static::get('closing_time', '22:00');

        $now = Carbon::now();
        $open = Carbon::today()->setTimeFromTimeString($openingTime);
        $close = Carbon::today()->setTimeFromTimeString($closingTime);

        return $now->between($open, $close);
    }

    /**
     * Get a human-readable closed message with times substituted.
     */
    public static function closedMessage(): string
    {
        $msg = static::get(
            'closed_message',
            'We are currently closed. We open at {opening_time} and close at {closing_time}.'
        );

        $open  = Carbon::today()->setTimeFromTimeString(static::get('opening_time', '13:00'))->format('g:i A');
        $close = Carbon::today()->setTimeFromTimeString(static::get('closing_time', '22:00'))->format('g:i A');

        return str_replace(['{opening_time}', '{closing_time}'], [$open, $close], $msg);
    }
}
