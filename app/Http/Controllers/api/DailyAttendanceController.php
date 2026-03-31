<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDailyAttendanceRequest;
use App\Http\Requests\UpdateDailyAttendanceRequest;
use App\Http\Resources\DailyAttendanceResource;
use App\Models\DailyAttendance;
use Illuminate\Http\Request;

class DailyAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DailyAttendance::with('student');

        // Filtering
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Searching
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->whereHas('student', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%");
            });
        }

        $dailyAttendances = $query->paginate(15);

        return DailyAttendanceResource::collection($dailyAttendances);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailyAttendanceRequest $request)
    {
        $dailyAttendance = DailyAttendance::create($request->validated());
        return new DailyAttendanceResource($dailyAttendance);
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyAttendance $dailyAttendance)
    {
        return new DailyAttendanceResource($dailyAttendance->load('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailyAttendanceRequest $request, DailyAttendance $dailyAttendance)
    {
        $dailyAttendance->update($request->validated());
        return new DailyAttendanceResource($dailyAttendance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyAttendance $dailyAttendance)
    {
        $dailyAttendance->delete();
        return response()->json(null, 204);
    }
}
