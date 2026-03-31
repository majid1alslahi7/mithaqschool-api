<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingsRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Resources\SettingsResource;
use App\Models\School;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(School::class, 'setting');
    }

    public function general(Request $request)
    {
        $settings = School::first();
        return new SettingsResource($settings);
    }

    public function updateGeneral(UpdateSettingsRequest $request)
    {
        $settings = School::firstOrCreate([]);
        $settings->update($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الإعدادات العامة بنجاح',
            'data' => new SettingsResource($settings)
        ]);
    }

    public function system(Request $request)
    {
        // إعدادات النظام
        $settings = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'sometimes|string|max:255',
            'app_env' => 'sometimes|in:local,production,staging',
            'app_debug' => 'sometimes|boolean',
            'timezone' => 'sometimes|timezone',
            'locale' => 'sometimes|in:ar,en',
        ]);
        
        // تحديث إعدادات النظام (يمكن تخزينها في قاعدة البيانات أو ملف .env)
        foreach ($validated as $key => $value) {
            // تحديث الإعدادات في قاعدة البيانات أو التخزين
            \App\Models\SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات النظام بنجاح'
        ]);
    }
}
