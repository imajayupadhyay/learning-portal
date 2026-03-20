<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->get('filter', 'all');

        $query = $user->todos()->with(['course:id,title', 'lesson:id,title']);

        switch ($filter) {
            case 'today':
                $query->today();
                break;
            case 'upcoming':
                $query->upcoming();
                break;
            case 'completed':
                $query->completed();
                break;
            case 'overdue':
                $query->overdue();
                break;
        }

        $todos = $query
            ->orderBy('is_completed')
            ->orderBy('due_date')
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->get()
            ->map(function ($todo) {
                return [
                    'id' => $todo->id,
                    'title' => $todo->title,
                    'description' => $todo->description,
                    'due_date' => $todo->due_date->format('Y-m-d'),
                    'due_date_display' => $todo->due_date->isToday()
                        ? 'Today'
                        : ($todo->due_date->isTomorrow()
                            ? 'Tomorrow'
                            : $todo->due_date->format('M d, Y')),
                    'priority' => $todo->priority,
                    'is_completed' => $todo->is_completed,
                    'completed_at' => $todo->completed_at?->diffForHumans(),
                    'is_overdue' => !$todo->is_completed && $todo->due_date->isPast(),
                    'course' => $todo->course ? ['id' => $todo->course->id, 'title' => $todo->course->title] : null,
                    'lesson' => $todo->lesson ? ['id' => $todo->lesson->id, 'title' => $todo->lesson->title] : null,
                ];
            });

        // Courses with lessons for the form dropdown
        $courses = Course::with('lessons:id,course_id,title,sort_order')->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'lessons' => $course->lessons->map(fn ($l) => [
                    'id' => $l->id,
                    'title' => $l->title,
                ]),
            ];
        });

        return Inertia::render('Student/Todos', [
            'todos' => $todos,
            'courses' => $courses,
            'currentFilter' => $filter,
            'counts' => [
                'all' => $user->todos()->count(),
                'today' => $user->todos()->today()->count(),
                'upcoming' => $user->todos()->upcoming()->count(),
                'completed' => $user->todos()->completed()->count(),
                'overdue' => $user->todos()->overdue()->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'course_id' => 'nullable|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
        ]);

        if ($request->lesson_id && !$request->course_id) {
            return back()->withErrors(['lesson_id' => 'A course must be selected when choosing a lesson.']);
        }

        $request->user()->todos()->create($request->only(
            'title', 'description', 'due_date', 'priority', 'course_id', 'lesson_id'
        ));

        return back();
    }

    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'course_id' => 'nullable|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
        ]);

        $todo->update($request->only(
            'title', 'description', 'due_date', 'priority', 'course_id', 'lesson_id'
        ));

        return back();
    }

    public function toggleComplete(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403);
        }

        $todo->update([
            'is_completed' => !$todo->is_completed,
            'completed_at' => !$todo->is_completed ? now() : null,
        ]);

        return back();
    }

    public function destroy(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403);
        }

        $todo->delete();

        return back();
    }
}
