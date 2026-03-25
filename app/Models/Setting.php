<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value',
    ];

    /**
     * @param array|string|null
     * @return array|string|null
     */
    public static function getSetting($keys = null)
    {
        $setting = [];
        switch (gettype($keys)) {
            case 'string':
                $item = self::where('key', $keys)->first();
                $setting = $item['value'] ?? null;
                break;
            case 'array':
                $settings = self::whereIn('key', $keys)->pluck('value', 'key');
                foreach ($keys as $key) {
                    $setting[$key] = $settings[$key] ?? null;
                }
                break;
            default :
                $setting = self::pluck('value', 'key');
        }
        return $setting;
    }

    /**
     * @param array|string
     * @param string|null
     */
    public static function saveSetting($setting, $value = null)
    {
        if (is_array($setting)) {
            foreach ($setting as $key => $value) {
                self::updateOrCreate([
                    'key' => $key,
                ], [
                    'value' => $value,
                ]);
            }
        } else if (gettype($setting) == 'string') {
            self::updateOrCreate([
                'key' => $setting,
            ], [
                'value' => $value,
            ]);
        }
    }
}
