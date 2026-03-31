<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolStage;
use App\Models\Grade;
use App\Models\Course;

class SchoolDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // مرحلة الطفولة المبكرة
        $earlyChildhood = SchoolStage::updateOrCreate(['name' => 'مرحلة الطفولة المبكرة']);
        $kindergarten = Grade::updateOrCreate(['name' => 'رياض الاطفال'], ['stage_id' => $earlyChildhood->id]);
        Course::updateOrCreate(['name' => 'كتاب 1', 'grade_id' => $kindergarten->id]);
        Course::updateOrCreate(['name' => 'كتاب 2', 'grade_id' => $kindergarten->id]);
        Course::updateOrCreate(['name' => 'كتاب 3', 'grade_id' => $kindergarten->id]);

        // المرحلة الابتدائية
        $primary = SchoolStage::updateOrCreate(['name' => 'المرحلة الابتدائية']);

        // الصف الاول والثاني
        $grade1 = Grade::updateOrCreate(['name' => 'الصف الاول'], ['stage_id' => $primary->id]);
        $grade2 = Grade::updateOrCreate(['name' => 'الصف الثاني'], ['stage_id' => $primary->id]);
        $courses1_2 = ['قران', 'اسلامية', 'لغة عربية', 'رياضيات', 'علوم'];
        foreach ($courses1_2 as $course) {
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade1->id]);
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade2->id]);
        }

        // الصف الثالث والرابع
        $grade3 = Grade::updateOrCreate(['name' => 'الصف الثالث'], ['stage_id' => $primary->id]);
        $grade4 = Grade::updateOrCreate(['name' => 'الصف الرابع'], ['stage_id' => $primary->id]);
        $courses3_4 = ['قران', 'اسلامية', 'لغة عربية', 'رياضيات', 'علوم تربية', 'تربية اجتماعية'];
        foreach ($courses3_4 as $course) {
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade3->id]);
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade4->id]);
        }

        // الصف الخامس والسادس
        $grade5 = Grade::updateOrCreate(['name' => 'الصف الخامس'], ['stage_id' => $primary->id]);
        $grade6 = Grade::updateOrCreate(['name' => 'الصف السادس'], ['stage_id' => $primary->id]);
        $courses5_6 = ['قران', 'اسلامية', 'لغة عربية', 'رياضيات', 'علوم', 'اجتماعيات(تاريخ,جغرافيا,تربية وطنية)'];
        foreach ($courses5_6 as $course) {
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade5->id]);
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade6->id]);
        }

        // الصف السابع والثامن والتاسع
        $grade7 = Grade::updateOrCreate(['name' => 'الصف السابع'], ['stage_id' => $primary->id]);
        $grade8 = Grade::updateOrCreate(['name' => 'الصف الثامن'], ['stage_id' => $primary->id]);
        $grade9 = Grade::updateOrCreate(['name' => 'الصف التاسع'], ['stage_id' => $primary->id]);
        $courses7_9 = ['قران', 'اسلامية', 'لغة عربية', 'رياضيات', 'علوم', 'اجتماعيات(تاريخ,جغرافيا,تربية وطنية)', 'انجليزي'];
        foreach ($courses7_9 as $course) {
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade7->id]);
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade8->id]);
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade9->id]);
        }

        // المرحلة الثانوية
        $secondary = SchoolStage::updateOrCreate(['name' => 'المرحلة الثانوية']);
        $grade10 = Grade::updateOrCreate(['name' => 'الصف الاول الثانوي'], ['stage_id' => $secondary->id]);
        $grade11 = Grade::updateOrCreate(['name' => 'الصف الثاني الثانوي'], ['stage_id' => $secondary->id]);
        $grade12 = Grade::updateOrCreate(['name' => 'الصف الثالث الثانوي'], ['stage_id' => $secondary->id]);
        $courses10_12 = [
            'قران',
            'اسلامية (حديث , فقه ,سيرة نبوية,الايمان)',
            'لغة عربية(القراءة, الادب والنصوص والبلاغة,النحو والصرف)',
            'رياضيات',
            'كيمياء',
            'فيزياء',
            'أحياء',
            'اجتماعيات(تاريخ,جغرافيا, المجتمع اليمني)',
            'انجليزي',
            'الحاسوب'
        ];
        foreach ($courses10_12 as $course) {
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade10->id]);
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade11->id]);
            Course::updateOrCreate(['name' => $course, 'grade_id' => $grade12->id]);
        }
    }
}
// عند تنفيذ السيدر تكررت اسماء المود
