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
            'maintenance_mode' => 'boolean',
            'google_maps_key' => 'nullable|string',
            'cinetpay_api_key' => 'nullable|string',
            'cinetpay_site_id' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Paramètres enregistrés');
    }
}