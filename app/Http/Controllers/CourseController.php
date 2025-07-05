<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\CourseRequests\StoreCourseRequest;
use App\Http\Requests\CourseRequests\UpdateCourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Course::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        $course = Course::create($data);
        return response()->json(['course' => $course], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return [ "course" => $course ];
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $data = $request->validated();
        $course->update($data);
        return response()->json(['course' => $course]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        
        $course->delete();

        return ['message' => 'The Course was deleted.'];
    }
}
