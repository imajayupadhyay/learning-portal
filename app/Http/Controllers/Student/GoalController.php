<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Goal;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $goalCourseIds = $user->goals()->pluck('course_id');

        $allCourses = Course::withCount('lessons')->get();

        $goalCourses = $allCourses->whereIn('id', $goalCourseIds)->map(function ($course) use ($user) {
            $totalLessons = $course->lessons_count;
            $completedLessons = LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->count();

            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => $course->instructor,
                'image' => $course->image,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
                'is_goal' => true,
            ];
        })->values();

        $availableCourses = $allCourses->whereNotIn('id', $goalCourseIds)->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => $course->instructor,
                'image' => $course->image,
                'total_lessons' => $course->lessons_count,
                'is_goal' => false,
            ];
        })->values();

        return Inertia::render('Student/Goals', [
            'goalCourses' => $goalCourses,
            'availableCourses' => $availableCourses,
        ]);
    }

    public function addGoal(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        Goal::firstOrCreate([
            'user_id' => $request->user()->id,
            'course_id' => $request->course_id,
        ]);

        return back();
    }

    public function removeGoal(Request $request, $courseId)
    {
        Goal::where('user_id', $request->user()->id)
            ->where('course_id', $courseId)
            ->delete();

        return back();
    }

    public function toggleLessonComplete(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $existing = LessonCompletion::where('user_id', $request->user()->id)
            ->where('lesson_id', $request->lesson_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $completed = false;
        } else {
            LessonCompletion::create([
                'user_id' => $request->user()->id,
                'lesson_id' => $request->lesson_id,
            ]);
            $completed = true;
        }

        return back()->with('completed', $completed);
    }
}
