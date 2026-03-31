<?php

namespace App\Providers;

use App\Models\Grade;
use App\Models\MonthlyGrade;
use App\Observers\GradeObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission; // أضف هذا السطر

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
{
    $this->app->alias(\Spatie\Permission\Models\Permission::class, 'permission');
}

    public function boot(): void
    {
        // ========== الكود الموجود (نحتفظ به) ==========
        try {
            $grades = Grade::all();
            View::share('grades', $grades);
        } catch (\Exception $e) {
            View::share('grades', []);
        }

        View::composer('layouts.header', function ($view) {
            $navigationItems = [
                [
                    'title' => 'الرئيسية',
                    'route' => 'dashboard',
                    'active' => 'dashboard',
                    'icon' => 'fas fa-home',
                    'children' => [],
                ],
                [
                    'title' => 'شؤون الطلاب',
                    'route' => 'students.index',
                    'active' => 'students.*',
                    'icon' => 'fas fa-user-graduate',
                    'children' => [
                        ['title' => 'عرض كل الطلاب', 'route' => 'students.index', 'active' => 'students.index'],
                        ['title' => 'إضافة طالب جديد', 'route' => 'students.create', 'active' => 'students.create'],
                        ['title' => 'تقارير الطلاب', 'route' => 'students.reports', 'active' => 'students.reports'],
                    ],
                ],
                [
                    'title' => 'المعلمون',
                    'route' => 'teachers.index',
                    'active' => 'teachers.*',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'children' => [
                        ['title' => 'عرض كل المعلمين', 'route' => 'teachers.index', 'active' => 'teachers.index'],
                        ['title' => 'إضافة معلم جديد', 'route' => 'teachers.create', 'active' => 'teachers.create'],
                    ],
                ],
                [
                    'title' => 'أولياء الأمور',
                    'route' => 'parents.index',
                    'active' => 'parents.*',
                    'icon' => 'fas fa-user-friends',
                    'children' => [
                        ['title' => 'عرض أولياء الأمور', 'route' => 'parents.index', 'active' => 'parents.index'],
                        ['title' => 'إضافة ولي أمر', 'route' => 'parents.create', 'active' => 'parents.create'],
                    ],
                ],
                [
                    'title' => 'المستخدمون',
                    'route' => 'users.index',
                    'active' => 'users.*',
                    'icon' => 'fas fa-users-cog',
                    'children' => [
                        ['title' => 'عرض المستخدمين', 'route' => 'users.index', 'active' => 'users.index'],
                        ['title' => 'إضافة مستخدم', 'route' => 'users.create', 'active' => 'users.create'],
                    ],
                ],
                [
                    'title' => 'الدعم الفني',
                    'route' => 'support.index',
                    'active' => 'support.*',
                    'icon' => 'fas fa-headset',
                    'children' => [],
                ],
            ];

            $view->with('navigationItems', $navigationItems);
        });
        
        // ========== إضافة Observer للدرجات الشهرية (جديد) ==========
        try {
            MonthlyGrade::observe(GradeObserver::class);
        } catch (\Exception $e) {
            // تجاهل الأخطاء أثناء التثبيت أو الترحيل
        }
    }
}