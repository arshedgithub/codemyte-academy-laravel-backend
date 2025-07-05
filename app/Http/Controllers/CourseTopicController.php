<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use Illuminate\Http\Request;

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
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course); // Only admin can add topics to courses
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'recording_url' => 'nullable|url|max:500',
            'class_link' => 'nullable|url|max:500',
            'content' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['course_id'] = $course->id;
        
        $topic = CourseTopic::create($validated);
        
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
    public function update(Request $request, Course $course, CourseTopic $topic)
    {
        $this->authorize('update', $course); // Only admin can update topics
        
        if ($topic->course_id !== $course->id) {
            return response()->json(['message' => 'Topic not found for this course'], 404);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'recording_url' => 'nullable|url|max:500',
            'class_link' => 'nullable|url|max:500',
            'content' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $topic->update($validated);
        
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
