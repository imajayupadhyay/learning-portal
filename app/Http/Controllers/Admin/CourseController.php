<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'nullable|string|max:500',
        ]);

        $data = $request->only('title', 'description', 'instructor');

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('courses', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        Course::create($data);

        return back();
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'instructor' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'nullable|string|max:500',
        ]);

        $data = $request->only('title', 'description', 'instructor');

        if ($request->hasFile('image_file')) {
            // Delete old uploaded image if exists
            if ($course->image && str_starts_with($course->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $course->image));
            }
            $path = $request->file('image_file')->store('courses', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $course->update($data);

        return back();
    }

    public function destroy(Course $course)
    {
        if ($course->image && str_starts_with($course->image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $course->image));
        }

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
