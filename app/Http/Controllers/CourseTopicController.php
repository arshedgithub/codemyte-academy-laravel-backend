<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use App\Http\Requests\CourseTopicsRequests\StoreCourseTopicRequest;
use App\Http\Requests\CourseTopicsRequests\UpdateCourseTopicRequest;

class CourseTopicController extends Controller
{
    /**
     * Display a listing of topics for a specific course.
     */
    public function index(Course $course)
    {
        return response()->json([
            'course' => $course,
            'topics' => $course->topics()->where('is_active', true)->get()
        ]);
    }

    /**
     * Store a newly created topic in storage.
     */
    public function store(StoreCourseTopicRequest $request, Course $course)
    {
        $data = $request->validated();
        $data['course_id'] = $course->id;
        
        $topic = CourseTopic::create($data);
        
        return response()->json(['topic' => $topic], 201);
    }

    /**
     * Display the specified topic.
     */
    public function show(Course $course, CourseTopic $topic)
    {
        if ($topic->course_id !== $course->id) {
            return response()->json(['message' => 'Topic not found for this course'], 404);
        }
        
        return response()->json(['topic' => $topic]);
    }

    /**
     * Update the specified topic in storage.
     */
    public function update(UpdateCourseTopicRequest $request, Course $course, CourseTopic $topic)
    {
        if ($topic->course_id !== $course->id) {
            return response()->json(['message' => 'Topic not found for this course'], 404);
        }
        
        $data = $request->validated();
        $topic->update($data);
        
        return response()->json(['topic' => $topic]);
    }

    /**
     * Remove the specified topic from storage.
     */
    public function destroy(Course $course, CourseTopic $topic)
    {
        $this->authorize('delete', $course); // Only admin can delete topics
        
        if ($topic->course_id !== $course->id) {
            return response()->json(['message' => 'Topic not found for this course'], 404);
        }
        
        $topic->delete();
        
        return response()->json(['message' => 'Topic deleted successfully']);
    }
}
