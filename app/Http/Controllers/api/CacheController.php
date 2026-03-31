<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CacheRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Cache::class, 'cache');
    }

    public function clear(CacheRequest $request)
    {
        try {
            $type = $request->input('type', 'all');
            $messages = [];
            
            switch ($type) {
                case 'all':
                    Artisan::call('optimize:clear');
                    $messages[] = 'تم مسح جميع الكاش';
                    break;
                    
                case 'config':
                    Artisan::call('config:clear');
                    $messages[] = 'تم مسح كاش الإعدادات';
                    break;
                    
                case 'route':
                    Artisan::call('route:clear');
                    $messages[] = 'تم مسح كاش المسارات';
                    break;
                    
                case 'view':
                    Artisan::call('view:clear');
                    $messages[] = 'تم مسح كاش العروض';
                    break;
                    
                case 'Permission':
                    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                    $messages[] = 'تم مسح كاش الصلاحيات';
                    break;
            }
            
            return response()->json([
                'success' => true,
                'message' => implode(', ', $messages)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل مسح الكاش: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stats()
    {
        $stats = [
            'config_cached' => file_exists(base_path('bootstrap/cache/config.php')),
            'routes_cached' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'views_cached' => count(glob(storage_path('framework/views/*.php'))),
            'permissions_cached' => Cache::has('spatie.permission.cache'),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
