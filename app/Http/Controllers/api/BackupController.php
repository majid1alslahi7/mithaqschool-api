<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackupRequest;
use App\Http\Resources\BackupResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Backup::class, 'backup');
    }

    public function create(BackupRequest $request)
    {
        try {
            $type = $request->input('type', 'full');
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '_' . Str::random(8) . '.zip';
            
            // تشغيل أمر النسخ الاحتياطي
            Artisan::call('backup:run', [
                '--only-db' => $type === 'database',
                '--only-files' => $type === 'files',
                '--filename' => $filename,
            ]);
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء النسخة الاحتياطية بنجاح',
                'data' => [
                    'filename' => $filename,
                    'type' => $type,
                    'created_at' => now(),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore(BackupRequest $request)
    {
        try {
            $filename = $request->input('filename');
            
            if (!$filename || !Storage::disk('backups')->exists($filename)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الملف غير موجود'
                ], 404);
            }
            
            Artisan::call('backup:restore', [
                '--filename' => $filename,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم استعادة النسخة الاحتياطية بنجاح'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل استعادة النسخة الاحتياطية: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $backups = [];
        $files = Storage::disk('backups')->files();
        
        foreach ($files as $file) {
            $backups[] = [
                'id' => Str::slug($file),
                'filename' => basename($file),
                'size' => Storage::disk('backups')->size($file),
                'created_at' => Storage::disk('backups')->lastModified($file),
                'download_url' => route('backups.download', ['filename' => basename($file)]),
            ];
        }
        
        return BackupResource::collection($backups);
    }

    public function download($filename)
    {
        $path = storage_path("app/backups/{$filename}");
        
        if (!file_exists($path)) {
            return response()->json(['message' => 'الملف غير موجود'], 404);
        }
        
        return response()->download($path, $filename);
    }

    public function destroy($id)
    {
        $filename = $id . '.zip';
        
        if (Storage::disk('backups')->exists($filename)) {
            Storage::disk('backups')->delete($filename);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف النسخة الاحتياطية بنجاح'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'الملف غير موجود'
        ], 404);
    }
}
