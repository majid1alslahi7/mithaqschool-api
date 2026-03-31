<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory, Searchable;

    protected $searchableFields = [
        'f_name',
        'l_name',
        'enrollment_number',
        'address',
        'user.email',
        'guardian.f_name',
        'guardian.l_name',
        'classroom.name',
        'grade.name',
    ];

    protected static function booted()
    {
        static::creating(function ($student) {
            if (empty($student->enrollment_number)) {
                // Using max() is more efficient than orderBy()->first()
                $maxEnrollment = DB::table('students')->max('enrollment_number');
                $student->enrollment_number = $maxEnrollment ? $maxEnrollment + 1 : 20260000;
            }
        });
    }

    protected $fillable = [
        'f_name',
        'l_name',
        'gender',
        'birth_date',
        'address',
        'parent_id',
        'classroom_id',
        'grade_id',
        'user_id',
        'enrollment_number',
        'avatar_path',
        'attendance_status',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'birth_date' => 'date',
    ];

    protected $appends = ['avatar_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'parent_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar_path) {
            return Storage::url($this->avatar_path);
        }
        return null;
    }

    public function scopeApplyFilters(Builder $query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($q, $searchTerm) {
            // Separate numeric search from text search to prevent type errors.
            if (is_numeric($searchTerm)) {
                $q->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->Where('enrollment_number', 'like', "%{$searchTerm}%")
                             ->orwhere('id', $searchTerm);
                             
                });
            } else {
                $q->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('f_name', 'like', "%{$searchTerm}%")
                        ->orWhere('l_name', 'like', "%{$searchTerm}%")
                        ->orWhere('address', 'like', "%{$searchTerm}%")
                        ->orWhereHas('guardian', fn($g) => $g->where('f_name', 'like', "%{$searchTerm}%")->orWhere('l_name', 'like', "%{$searchTerm}%"))
                        ->orWhereHas('grade', fn($g) => $g->where('name', 'like', "%{$searchTerm}%"))
                        ->orWhereHas('classroom', fn($c) => $c->where('name', 'like', "%{$searchTerm}%"));
                });
            }
        })->when($filters['school_stage'] ?? null, function ($q, $selectedStageId) {
            $q->whereHas('grade', fn($g) => $g->where('stage_id', $selectedStageId));
        })->when($filters['grade'] ?? null, function ($q, $selectedGradeId) {
            $q->where('grade_id', $selectedGradeId);
        });
    }

    /**
     * Scope to apply filters and sorting for api requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplyapiFiltersAndSort(Builder $query, $request)
    {
        // Comprehensive search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('f_name', 'like', "%{$searchTerm}%")
                    ->orWhere('l_name', 'like', "%{$searchTerm}%")
                    ->orWhere('address', 'like', "%{$searchTerm}%")
                    // Search in related models
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('email', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('guardian', function ($guardianQuery) use ($searchTerm) {
                        $guardianQuery->where('f_name', 'like', "%{$searchTerm}%")
                            ->orWhere('l_name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('classroom', function ($classroomQuery) use ($searchTerm) {
                        $classroomQuery->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('grade', function ($gradeQuery) use ($searchTerm) {
                        $gradeQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filtering
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->input('grade_id'));
        }

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->input('classroom_id'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // Sorting
        if ($request->filled('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortDirection = $request->input('sort_direction', 'asc');
            // Add validation for sortable columns to prevent error
            $sortableColumns = ['id', 'f_name', 'l_name', 'birth_date', 'created_at'];
            if (in_array($sortBy, $sortableColumns)) {
                $query->orderBy($sortBy, $sortDirection);
            }
        } else {
            $query->latest(); // Default sort
        }

        return $query;
    }
}
