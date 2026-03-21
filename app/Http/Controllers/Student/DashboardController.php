<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $goalCourseIds = $user->goals()->pluck('course_id');

        $goalCourses = Course::whereIn('id', $goalCourseIds)
            ->withCount('lessons')
            ->get();

        $recentCourses = $goalCourses->take(3)->map(function ($course) use ($user) {
            $completedCount = LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->count();
            $progress = $course->lessons_count > 0
                ? round(($completedCount / $course->lessons_count) * 100)
                : 0;

            return [
                'id' => $course->id,
                'title' => $course->title,
                'progress' => $progress,
                'image' => $course->image,
                'total_lessons' => $course->lessons_count,
                'completed_lessons' => $completedCount,
            ];
        })->values();

        $totalLessonsAll = $goalCourses->sum('lessons_count');
        $completedLessonsAll = 0;
        foreach ($goalCourses as $course) {
            $completedLessonsAll += LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->count();
        }
        $overallProgress = $totalLessonsAll > 0
            ? round(($completedLessonsAll / $totalLessonsAll) * 100)
            : 0;

        $completedCourses = $goalCourses->filter(function ($course) use ($user) {
            if ($course->lessons_count === 0) return false;
            $completedCount = LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->count();
            return $completedCount >= $course->lessons_count;
        })->count();

        $inProgressCourses = $goalCourses->filter(function ($course) use ($user) {
            $completedCount = LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->count();
            return $completedCount > 0 && $completedCount < $course->lessons_count;
        })->count();

        return Inertia::render('Student/Dashboard', [
            'user' => $user->only('name', 'email'),
            'recent_courses' => $recentCourses,
            'stats' => [
                ['label' => 'Enrolled', 'value' => (string) $goalCourses->count()],
                ['label' => 'Completed', 'value' => (string) $completedCourses],
                ['label' => 'In Progress', 'value' => (string) $inProgressCourses],
            ],
            'overall_progress' => $overallProgress,
            'total_lessons' => $totalLessonsAll,
            'completed_lessons' => $completedLessonsAll,
        ]);
    }

    public function courses(Request $request)
    {
        $user = $request->user();
        $query = Course::withCount('lessons')->with('category');

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->whereIn('category_id', $category->getAllDescendantIds());
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $paginated = $query->latest()->paginate(12)->withQueryString();

        $courses = $paginated->through(function ($course) use ($user) {
            $completedCount = LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->count();
            $progress = $course->lessons_count > 0
                ? round(($completedCount / $course->lessons_count) * 100)
                : 0;

            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'image' => $course->image,
                'lessons_count' => $course->lessons_count,
                'category_name' => $course->category?->name,
                'progress' => $progress,
            ];
        });

        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->withCount('courses')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'courses_count' => $cat->courses_count,
                    'children' => $cat->children->map(fn ($child) => [
                        'id' => $child->id,
                        'name' => $child->name,
                        'slug' => $child->slug,
                    ]),
                ];
            });

        return Inertia::render('Student/Courses', [
            'courses' => $courses,
            'categories' => $categories,
            'activeCategory' => $request->category,
            'search' => $request->search,
        ]);
    }

    public function courseShow($id)
    {
        $user = request()->user();
        $course = Course::with(['lessons' => function ($q) {
            $q->orderBy('sort_order');
        }])->findOrFail($id);

        $completedLessonIds = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $course->lessons->count();
        $completedCount = count($completedLessonIds);
        $progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        return Inertia::render('Student/CourseShow', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'instructor' => $course->instructor,
                'lessons' => $course->lessons->map(function ($lesson) use ($completedLessonIds) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'duration' => $lesson->duration,
                        'video_id' => $lesson->video_id,
                        'completed' => in_array($lesson->id, $completedLessonIds),
                    ];
                }),
            ],
            'progress' => $progress,
            'completedLessonIds' => $completedLessonIds,
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $detail = $user->studentDetail;

        return Inertia::render('Student/Profile', [
            'user' => $user->only('name', 'email'),
            'detail' => $detail ? [
                'phone' => $detail->phone,
                'date_of_birth' => $detail->date_of_birth?->format('Y-m-d'),
                'gender' => $detail->gender,
                'education' => $detail->education,
                'institution' => $detail->institution,
                'interests' => $detail->interests,
                'bio' => $detail->bio,
                'address' => $detail->address,
                'city' => $detail->city,
                'state' => $detail->state,
                'country' => $detail->country,
                'pincode' => $detail->pincode,
                'linkedin' => $detail->linkedin,
                'github' => $detail->github,
                'avatar' => $detail->avatar,
            ] : null,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'education' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'linkedin' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();
        $user->update(['name' => $request->name]);

        $detailData = $request->only(
            'phone', 'date_of_birth', 'gender', 'education', 'institution',
            'interests', 'bio', 'address', 'city', 'state', 'country',
            'pincode', 'linkedin', 'github'
        );

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $detailData['avatar'] = '/storage/' . $path;
        }

        $user->studentDetail()->updateOrCreate(
            ['user_id' => $user->id],
            $detailData
        );

        return back();
    }
}
