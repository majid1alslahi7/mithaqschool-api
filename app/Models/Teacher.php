<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Teacher extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'enrollment_number',
        'user_id',
        'f_name',
        'l_name',
        'gender',
        'birth_date',
        'hire_date',
        'address',
        'avatar_path',
        'grade_id',
        'course_id',
        'classroom_id',
        'last_modified',
        'is_synced',
        'is_active',
    ];

    protected $appends = ['avatar_url'];

    /**
     * الحقول القابلة للبحث
     * (مطابقة لأسلوب Student)
     */
    protected $searchableFields = [
        'f_name',
        'l_name',
        'address',
        'grade.name',
        'course.name',
        'classroom.name',
        'user.username',   // موجود
    ];

    /**
     * رقم المعلم يبدأ من 70707070 إذا لم يوجد أي رقم سابق
     */
    private const STARTING_ENROLLMENT_NUMBER = 70707070;

    protected static function booted()
    {
        static::creating(function ($teacher) {
            if (empty($teacher->enrollment_number)) {
                $maxEnrollment = DB::table('teachers')->max('enrollment_number');
                $teacher->enrollment_number = $maxEnrollment
                    ? $maxEnrollment + 1
                    : self::STARTING_ENROLLMENT_NUMBER;
            }
        });
    }

    /**
     * علاقة المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة الصف
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * علاقة المادة
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * علاقة الفصل
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * صورة المعلم
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path
            ? Storage::url($this->avatar_path)
            : null;
    }

    /**
     * فلترة + ترتيب للـ api
     */
    public function scopeApplyapiFiltersAndSort(Builder $query, $request)
    {
        // البحث العام
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('f_name', 'like', "%{$searchTerm}%")
                    ->orWhere('l_name', 'like', "%{$searchTerm}%")
                    ->orWhere('address', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', fn($u) =>
                        $u->where('username', 'like', "%{$searchTerm}%")
                    )
                    ->orWhereHas('grade', fn($g) =>
                        $g->where('name', 'like', "%{$searchTerm}%")
                    )
                    ->orWhereHas('course', fn($c) =>
                        $c->where('name', 'like', "%{$searchTerm}%")
                    )
                    ->orWhereHas('classroom', fn($cl) =>
                        $cl->where('name', 'like', "%{$searchTerm}%")
                    );
            });
        }

        // فلترة حسب الصف
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->input('grade_id'));
        }

        // فلترة حسب المادة
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->input('course_id'));
        }

        // فلترة حسب الفصل
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->input('classroom_id'));
        }

        // فلترة حسب الجنس
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // الترتيب
        if ($request->filled('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortDirection = $request->input('sort_direction', 'asc');

            $sortable = ['id', 'f_name', 'l_name', 'birth_date', 'hire_date', 'created_at'];

            if (in_array($sortBy, $sortable)) {
                $query->orderBy($sortBy, $sortDirection);
            }
        } else {
            $query->latest();
        }

        return $query;
    }
}