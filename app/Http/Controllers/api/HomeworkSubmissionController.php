<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomeworkSubmissionRequest;
use App\Http\Requests\UpdateHomeworkSubmissionRequest;
use App\Http\Resources\HomeworkSubmissionResource;
use App\Models\HomeworkSubmission;
use Illuminate\Http\Request;

class HomeworkSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HomeworkSubmission::with(['homework', 'student']);

        // Filtering
        if ($request->has('homework_id')) {
            $query->where('homework_id', $request->homework_id);
        }
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->has('submission_date')) {
            $query->whereDate('submission_date', $request->submission_date);
        }

        // Searching
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->whereHas('student', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%");
            });
        }

        $homeworkSubmissions = $query->paginate(15);

        return HomeworkSubmissionResource::collection($homeworkSubmissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHomeworkSubmissionRequest $request)
    {
        $homeworkSubmission = HomeworkSubmission::create($request->validated());
        return new HomeworkSubmissionResource($homeworkSubmission);
    }

    /**
     * Display the specified resource.
     */
    public function show(HomeworkSubmission $homeworkSubmission)
    {
        return new HomeworkSubmissionResource($homeworkSubmission->load(['homework', 'student']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHomeworkSubmissionRequest $request, HomeworkSubmission $homeworkSubmission)
    {
        $homeworkSubmission->update($request->validated());
        return new HomeworkSubmissionResource($homeworkSubmission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeworkSubmission $homeworkSubmission)
    {
        $homeworkSubmission->delete();
        return response()->json(null, 204);
    }
}
