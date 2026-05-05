<?php

namespace App\Http\Controllers\BackEnd\SiteSetting;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SiteSettingController extends Controller
{
    public function siteSetting()
    {
        return Inertia::render('BackEnd/SiteSetting/SiteSetting', [
            'site' => SiteSetting::first(),
        ]);
    }

    public function siteSettingUpdate(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'favaicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,svg,webp|max:1024'
        ]);

        $site = SiteSetting::first() ?? new SiteSetting();

        if ($request->hasFile('logo')) {
            if ($site->logo && Storage::disk('public')->exists($site->logo)) {
                Storage::disk('public')->delete($site->logo);
            }

            $site->logo = $request->file('logo')->store('site/logo', 'public');
        }

        if ($request->hasFile('favaicon')) {
            if ($site->favaicon && Storage::disk('public')->exists($site->favaicon)) {
                Storage::disk('public')->delete($site->favaicon);
            }

            $site->favaicon = $request->file('favaicon')->store('site/favaicon', 'public');
        }

        $site->save();

        return redirect()->back()->with('status', 'Site settings updated successfully.');
    }
}
