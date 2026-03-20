<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalCourses = Course::count();
        $totalLessons = Lesson::count();
        $totalStudents = User::where('role', 'student')->count();

        $recentStudents = User::where('role', 'student')
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'created_at']);

        $courses = Course::withCount('lessons')->latest()->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'lessons_count' => $course->lessons_count,
                'image' => $course->image,
            ];
        });

        return Inertia::render('Admin/Dashboard', [
            'user' => $request->user()->only('name', 'email'),
            'stats' => [
                'total_courses' => $totalCourses,
                'total_lessons' => $totalLessons,
                'total_students' => $totalStudents,
            ],
            'recentStudents' => $recentStudents,
            'courses' => $courses,
        ]);
    }
}
