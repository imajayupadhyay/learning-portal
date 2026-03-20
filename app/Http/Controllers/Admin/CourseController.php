<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $query = Course::withCount('lessons');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest()->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => $course->instructor,
                'image' => $course->image,
                'lessons_count' => $course->lessons_count,
                'created_at' => $course->created_at->format('M d, Y'),
            ];
        });

        return Inertia::render('Admin/Courses', [
            'courses' => $courses,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'instructor' => 'required|string|max:255',
            'image' => 'nullable|string|max:500',
        ]);

        Course::create($request->only('title', 'description', 'instructor', 'image'));

        return back();
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'instructor' => 'required|string|max:255',
            'image' => 'nullable|string|max:500',
        ]);

        $course->update($request->only('title', 'description', 'instructor', 'image'));

        return back();
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return back();
    }

    public function show(Course $course)
    {
        $lessons = $course->lessons()->orderBy('sort_order')->get()->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'duration' => $lesson->duration,
                'video_id' => $lesson->video_id,
                'sort_order' => $lesson->sort_order,
            ];
        });

        return Inertia::render('Admin/CourseShow', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => $course->instructor,
                'image' => $course->image,
            ],
            'lessons' => $lessons,
        ]);
    }

    public function storeLesson(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'nullable|string|max:50',
            'video_id' => 'nullable|string|max:255',
        ]);

        $maxSort = $course->lessons()->max('sort_order') ?? 0;

        $course->lessons()->create([
            'title' => $request->title,
            'duration' => $request->duration,
            'video_id' => $request->video_id,
            'sort_order' => $maxSort + 1,
        ]);

        return back();
    }

    public function updateLesson(Request $request, Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'nullable|string|max:50',
            'video_id' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $lesson->update($request->only('title', 'duration', 'video_id', 'sort_order'));

        return back();
    }

    public function destroyLesson(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(403);
        }

        $lesson->delete();

        return back();
    }
}
