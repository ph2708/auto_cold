<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Obter o valor de uma configuração com fallback padrão.
     */
    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('app_settings', function () {
            try {
                return self::all()->pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Salvar ou atualizar uma configuração.
     */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('app_settings');
    }

    /**
     * Retorna todas as configurações com padrões automáticos.
     */
    public static function allFormatted(): array
    {
        return [
            'company_name'     => self::get('company_name', 'AUTO COLD'),
            'company_subtitle' => self::get('company_subtitle', 'Elétrica e Ar Condicionado'),
            'owner_name'       => self::get('owner_name', 'JULIANO RIBEIRO'),
            'whatsapp'         => self::get('whatsapp', '64993295596'),
            'phone'            => self::get('phone', '(64) 99329-5596'),
            'slogan'           => self::get('slogan', 'CONFIANÇA E QUALIDADE: A COMBINAÇÃO PERFEITA PARA O SEU VEÍCULO.'),
            'address'          => self::get('address', 'R. Monte Alegre, 271 - Jardim Liberdade'),
            'city'             => self::get('city', 'Itumbiara - GO, 75510-090'),
            'latitude'         => self::get('latitude', '-18.4079971'),
            'longitude'        => self::get('longitude', '-49.2249619'),
            'maps_link'        => self::get('maps_link', 'https://www.google.com/maps/place/R.+Monte+Alegre,+271+-+Jardim+Liberdade,+Itumbiara+-+GO,+75510-090/@-18.4079971,-49.2256056,238m/data=!3m2!1e3!4b1!4m6!3m5!1s0x94a10d0d1df7616d:0x234bb98b2302d442!8m2!3d-18.4079971!4d-49.2249619!16s%2Fg%2F11wvgpf2r5?entry=ttu'),
            'primary_color'    => self::get('primary_color', '#06b6d4'), // Cyan-500
            'secondary_color'  => self::get('secondary_color', '#2563eb'), // Blue-600
            'logo_dark'        => self::get('logo_dark', 'images/logo-dark.png'),
            'logo_light'       => self::get('logo_light', 'images/logo-light.png'),
            'logo_height'      => self::get('logo_height', '48'), // em pixels
        ];
    }
}
