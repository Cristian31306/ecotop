<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $closureTime = Setting::where('key', 'system_closure_time')->value('value');
        
        return Inertia::render('Admin/Settings/Index', [
            'system_closure_time' => $closureTime
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'system_closure_time' => 'nullable|date',
        ]);

        Setting::updateOrCreate(
            ['key' => 'system_closure_time'],
            ['value' => $request->system_closure_time]
        );

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
