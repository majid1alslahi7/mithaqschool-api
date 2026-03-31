<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SchoolDataSeeder::class,
            GuardianSeeder::class,
            ClassroomSeeder::class,
            GradeSeeder::class,
        ]);

        // Ensure super-admin user is id=1
        $superAdmin = User::unguarded(function () {
            return User::updateOrCreate(
                ['id' => 1],
                [
                    'username' => 'majed',
                    'email' => 'majed1alslahy7@gmail.com',
                    'phone' => '715122500',
                    'password' => Hash::make('Mithaq@123'),
                    'is_active' => true,
                    'is_deleted' => false,
                    'email_verified_at' => now(),
                ]
            );
        });

        $role = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            ['label' => '???? ??????']
        );
        $superAdmin->syncRoles([$role]);

        // Create 50 users, 40 students and 10 teachers
        $users = User::factory(50)->create();
        $studentsUsers = $users->slice(0, 40);
        $teachersUsers = $users->slice(40, 10);


        $this->call(StudentSeeder::class, false, ['users' => $studentsUsers]);
        $this->call(TeacherSeeder::class, false, ['users' => $teachersUsers]);

        $this->call([
        //    CourseSeeder::class,
      //      ExamTypeSeeder::class,
    //        ExamSeeder::class,
//            ExamResultSeeder::class,
  //          AttendanceSeeder::class,
            AcademicYearSeeder::class,
            SemesterSeeder::class,
            GradesScalesSeeder::class,
            SchoolStageSeeder::class,
            TeacherCoursesSeeder::class,
            CourseClassroomTeacherSeeder::class,
            ScheduleSeeder::class,
            HomeworkSeeder::class,
            HomeworkSubmissionSeeder::class,
            BehaviorEvaluationSeeder::class,
            DailyAttendanceSeeder::class,
            MonthlyGradeSeeder::class,
            SemesterGradeSeeder::class,
            FinalyGradesSeeder::class,
            StudentGradeSeeder::class,
            MessageSeeder::class,
            NotificationSeeder::class,
            FeeTypeSeeder::class,
            ClassFeeSeeder::class,
            StudentInvoiceSeeder::class,
            InvoiceItemSeeder::class,
            PaymentSeeder::class,
            AdjustmentSeeder::class,

        ]);
    }
}


