<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schedules = $this->buildIndexQuery($request)->get();

        // Apply Grouping
        if ($request->filled('group_by')) {
            $groupBy = $request->input('group_by');
            $allowedGroups = ['teacher_id', 'course_id', 'classroom_id', 'day_of_week'];

            if (in_array($groupBy, $allowedGroups)) {
                $grouped = $schedules->groupBy($groupBy);
                return $grouped->map(function ($items) {
                    return ScheduleResource::collection($items);
                });
            }
        }

        return ScheduleResource::collection($schedules);
    }

    public function teacher(Request $request, string $teacher)
    {
        $schedules = $this->buildIndexQuery($request)
            ->where('teacher_id', $teacher)
            ->get();

        return ScheduleResource::collection($schedules);
    }

    public function course(Request $request, string $course)
    {
        $schedules = $this->buildIndexQuery($request)
            ->where('course_id', $course)
            ->get();

        return ScheduleResource::collection($schedules);
    }

    public function classroom(Request $request, string $classroom)
    {
        $schedules = $this->buildIndexQuery($request)
            ->where('classroom_id', $classroom)
            ->get();

        return ScheduleResource::collection($schedules);
    }

    private function buildIndexQuery(Request $request): Builder
    {
        $query = Schedule::with(['classroom', 'course', 'teacher']);

        // Apply Search
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Apply Filters
        $filterable = [
            'id' => 'id',
            'classroom_id' => 'classroom_id',
            'course_id' => 'course_id',
            'teacher_id' => 'teacher_id',
            'day_of_week' => 'day_of_week',
            'period' => 'period',
            'start_time' => 'start_time',
            'end_time' => 'end_time',
            'is_deleted' => 'is_deleted',
            'is_synced' => 'is_synced',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        foreach ($filterable as $param => $column) {
            if ($param === 'is_deleted' || $param === 'is_synced') {
                if ($request->has($param)) {
                    $query->where($column, (bool) $request->input($param));
                }
                continue;
            }

            if ($request->filled($param)) {
                $query->where($column, $request->input($param));
            }
        }

        return $query;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request)
    {
        $schedule = Schedule::create($request->validated());
        return new ScheduleResource($schedule->load(['classroom', 'course', 'teacher']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::with(['classroom', 'course', 'teacher'])->findOrFail($id);
        return new ScheduleResource($schedule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->validated());
        return new ScheduleResource($schedule->load(['classroom', 'course', 'teacher']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Schedule::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
