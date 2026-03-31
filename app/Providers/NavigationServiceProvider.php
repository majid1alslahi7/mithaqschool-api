<?php

namespace App\Providers;

use App\Models\Grade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $navigationItems = array(
                array(
                    'title' => 'القائمة الرئيسية',
                    'route' => 'dashboard',
                    'active' => 'dashboard',
                    'icon' => 'fas fa-home',
                    'color' => 'text-blue-500 dark:text-blue-400',
                    'children' => array(),
                ),
                array(
                    'title' => 'الأشخاص',
                    'route' => null,
                    'active' => 'students.*||teachers.*||parents.*',
                    'icon' => 'fas fa-users',
                    'color' => 'text-green-500 dark:text-green-400',
                    'children' => array(
                        array('title' => 'قائمة الطلاب', 'route' => 'students.index', 'active' => 'students.*'),
                        array('title' => 'قائمة المعلمين', 'route' => 'teachers.index', 'active' => 'teachers.*'),
                        array('title' => 'أولياء الأمور', 'route' => 'parents.index', 'active' => 'parents.*'),
                    ),
                ),
                array(
                    'title' => 'المستخدمون والصلاحيات',
                    'route' => null,
                    'active' => 'users.*||permissions.*',
                    'icon' => 'fas fa-user-shield',
                    'color' => 'text-yellow-500 dark:text-yellow-400',
                    'children' => array(
                        array('title' => 'المستخدمين', 'route' => 'users.index', 'active' => 'users.*'),
                        array('title' => 'الصلاحيات', 'route' => 'permissions.index', 'active' => 'permissions.*'),
                    ),
                ),
                array(
                    'title' => 'الهيكل الأكاديمي والمدارس',
                    'route' => null,
                    'active' => 'courses.*||classrooms.*',
                    'icon' => 'fas fa-university',
                    'color' => 'text-indigo-500 dark:text-indigo-400',
                    'children' => array(
                        array('title' => 'الكتب الدراسية', 'route' => 'courses.index', 'active' => 'courses.*'),
                        array('title' => 'الفصول الدراسية', 'route' => 'classrooms.index', 'active' => 'classrooms.*'),
                    ),
                ),
                array(
                    'title' => 'الدرجات الشهرية',
                    'route' => 'monthly_grades.index',
                    'active' => 'monthly_grades.*',
                    'icon' => 'fas fa-clipboard-list',
                    'color' => 'text-purple-500 dark:text-purple-400',
                    'children' => array(
                        array('title' => 'عرض الدرجات', 'route' => 'monthly_grades.index', 'active' => 'monthly_grades.index'),
                        array('title' => 'إضافة درجة', 'route' => 'monthly_grades.create', 'active' => 'monthly_grades.create'),
                    ),
                ),
                array(
                    'title' => 'الرسائل',
                    'route' => 'messages.index',
                    'active' => 'messages.*',
                    'icon' => 'fas fa-envelope',
                    'color' => 'text-pink-500 dark:text-pink-400',
                    'children' => array(),
                ),
                array(
                    'title' => 'الدعم الفني',
                    'route' => 'support.index',
                    'active' => 'support.*',
                    'icon' => 'fas fa-headset',
                    'color' => 'text-teal-500 dark:text-teal-400',
                    'children' => array(),
                ),
            );

            $view->with('navigationItems', $navigationItems)
                 ->with('header', 'لوحة التحكم');
        });
    }
}
