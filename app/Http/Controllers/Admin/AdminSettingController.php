<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'premium_mise_en_avant_price' => 'required|numeric|min:0',
            'premium_newsletter_price' => 'required|numeric|min:0',
            'premium_reseaux_price' => 'required|numeric|min:0',
            'vip_price' => 'nullable|numeric|min:0',
            'vip_duration_days' => 'nullable|integer|min:1',
            'vip_page_title' => 'nullable|string|max:255',
            'vip_advantages_text' => 'nullable|string',
            'maintenance_mode' => 'boolean',
            'google_maps_key' => 'nullable|string',
            'cinetpay_api_key' => 'nullable|string',
            'cinetpay_site_id' => 'nullable|string',
        ]);

        // Set defaults for VIP settings if not provided
        $validated['vip_price'] = $validated['vip_price'] ?? 5000;
        $validated['vip_duration_days'] = $validated['vip_duration_days'] ?? 30;
        $validated['vip_page_title'] = $validated['vip_page_title'] ?? 'Devenez VIP Eledji';
        $validated['vip_advantages_text'] = $validated['vip_advantages_text'] ?? 'Accédez à la Marketplace exclusive, ajoutez un badge VIP sur votre profil et bien plus encore!';

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        clear_settings_cache();

        return back()->with('success', 'Paramètres enregistrés');
    }
}