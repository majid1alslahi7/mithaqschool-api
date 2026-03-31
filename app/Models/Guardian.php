<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Guardian extends Model
{
    use HasFactory, Searchable;

    protected $table = 'parents';

    protected $fillable = [
        'enrollment_number',
        'user_id',
        'f_name',
        'l_name',
        'gender',
        'address',
        'avatar_path',
    ];

    protected $appends = ['avatar_url'];

    /**
     * الحقول القابلة للبحث
     * (مطابقة لأسلوب Student)
     */
    protected $searchableFields = [
        
        'enrollment_number',
       
        'f_name',
        'l_name',
        'address',
        'students.f_name',
        'students.l_name',
    ];

    /**
     * رقم ولي الأمر يبدأ من 555000 إذا لم يوجد أي رقم سابق
     */
    private const STARTING_ENROLLMENT_NUMBER = 555000;

    protected static function booted()
    {
        static::creating(function ($guardian) {
            if (empty($guardian->enrollment_number)) {
                $maxEnrollment = DB::table('parents')->max('enrollment_number');
                $guardian->enrollment_number = $maxEnrollment
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
     * علاقة الأبناء
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    /**
     * صورة ولي الأمر
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path
            ? Storage::url($this->avatar_path)
            : null;
    }

    /**
     * فلترة عامة (للوحات التحكم)
     */
    public function scopeApplyFilters(Builder $query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($q, $searchTerm) {

            if (is_numeric($searchTerm)) {
                $q->where('enrollment_number', 'like', "%{$searchTerm}%")
                  ->orWhere('id', $searchTerm);
            } else {
                $q->where(function ($sub) use ($searchTerm) {
                    $sub->where('f_name', 'like', "%{$searchTerm}%")
                        ->orWhere('l_name', 'like', "%{$searchTerm}%")
                        ->orWhere('address', 'like', "%{$searchTerm}%")
                        ->orWhereHas('students', fn($s) =>
                            $s->where('f_name', 'like', "%{$searchTerm}%")
                              ->orWhere('l_name', 'like', "%{$searchTerm}%")
                        );
                });
            }
        });
    }

    /**
     * فلترة + ترتيب للـ api
     */
    public function scopeApplyapiFiltersAndSort(Builder $query, $request)
    {
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('f_name', 'like', "%{$searchTerm}%")
                    ->orWhere('l_name', 'like', "%{$searchTerm}%")
                    ->orWhere('address', 'like', "%{$searchTerm}%")
                    ->orWhereHas('students', fn($s) =>
                        $s->where('f_name', 'like', "%{$searchTerm}%")
                          ->orWhere('l_name', 'like', "%{$searchTerm}%")
                    );
            });
        }

        // Sorting
        if ($request->filled('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortDirection = $request->input('sort_direction', 'asc');

            $sortable = ['id', 'f_name', 'l_name', 'created_at'];

            if (in_array($sortBy, $sortable)) {
                $query->orderBy($sortBy, $sortDirection);
            }
        } else {
            $query->latest();
        }

        return $query;
    }
}