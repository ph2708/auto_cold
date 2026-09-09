<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Exibir formulário de personalização de cores, logos e dados da oficina.
     */
    public function edit()
    {
        $settings = Setting::allFormatted();
        return view('settings.edit', compact('settings'));
    }

    /**
     * Salvar alterações de configurações.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name'     => 'required|string|max:100',
            'company_subtitle' => 'nullable|string|max:150',
            'owner_name'       => 'nullable|string|max:100',
            'whatsapp'         => 'nullable|string|max:30',
            'phone'            => 'nullable|string|max:30',
            'slogan'           => 'nullable|string|max:255',
            'address'          => 'nullable|string|max:255',
            'city'             => 'nullable|string|max:150',
            'latitude'         => 'nullable|string|max:50',
            'longitude'        => 'nullable|string|max:50',
            'maps_link'        => 'nullable|string|max:500',
            'primary_color'    => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color'  => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'logo_height'      => 'required|integer|min:24|max:200',
            'logo_dark_file'   => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'logo_light_file'  => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        Setting::set('company_name', $validated['company_name']);
        Setting::set('company_subtitle', $validated['company_subtitle'] ?? '');
        Setting::set('owner_name', $validated['owner_name'] ?? '');
        
        // Limpar WhatsApp para manter apenas números
        $cleanWhatsapp = preg_replace('/\D/', '', $validated['whatsapp'] ?? '');
        Setting::set('whatsapp', $cleanWhatsapp);
        Setting::set('phone', $validated['phone'] ?? '');
        Setting::set('slogan', $validated['slogan'] ?? '');
        Setting::set('address', $validated['address'] ?? '');
        Setting::set('city', $validated['city'] ?? '');
        Setting::set('latitude', $validated['latitude'] ?? '-18.4079971');
        Setting::set('longitude', $validated['longitude'] ?? '-49.2249619');
        Setting::set('maps_link', $validated['maps_link'] ?? '');
        Setting::set('primary_color', $validated['primary_color']);
        Setting::set('secondary_color', $validated['secondary_color']);
        Setting::set('logo_height', $validated['logo_height']);

        // Upload de Logo Escuro (usado no Navbar escuro e Landing Page)
        if ($request->hasFile('logo_dark_file')) {
            $path = $request->file('logo_dark_file')->store('logos', 'public');
            // Garantir cópia para diretório acessível em hospedagem compartilhada
            try {
                if (!file_exists(public_path('storage/logos'))) {
                    @mkdir(public_path('storage/logos'), 0777, true);
                }
                @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
            } catch (\Exception $e) {}
            Setting::set('logo_dark', 'storage/' . $path);
        }

        // Upload de Logo Claro (usado em impressões e fundos brancos)
        if ($request->hasFile('logo_light_file')) {
            $path = $request->file('logo_light_file')->store('logos', 'public');
            try {
                if (!file_exists(public_path('storage/logos'))) {
                    @mkdir(public_path('storage/logos'), 0777, true);
                }
                @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
            } catch (\Exception $e) {}
            Setting::set('logo_light', 'storage/' . $path);
        }

        return redirect()->route('settings.edit')->with('success', 'Configurações visuais e informações atualizadas com sucesso!');
    }
}
