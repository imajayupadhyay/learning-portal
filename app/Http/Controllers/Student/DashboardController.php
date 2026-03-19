<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Student/Dashboard', [
            'user' => $request->user()->only('name', 'email'),
            'recent_courses' => [
                [
                    'id' => 1,
                    'title' => 'DevOps Mastery: CI/CD Pipelines',
                    'progress' => 45,
                    'image' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=60',
                ]
            ],
            'stats' => [
                ['label' => 'Enrolled', 'value' => '3'],
                ['label' => 'Completed', 'value' => '0'],
                ['label' => 'In Progress', 'value' => '1'],
            ]
        ]);
    }

    public function courses()
    {
        $courses = [
            [
                'id' => 1,
                'title' => 'DevOps Mastery: CI/CD Pipelines',
                'description' => 'Learn how to build and deploy high-performance CI/CD pipelines from scratch.',
                'image' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=60',
                'lessons_count' => 12,
                'progress' => 45,
            ],
            [
                'id' => 2,
                'title' => 'Kubernetes Administration',
                'description' => 'Master K8s orchestration, deployments, and cluster management in the real world.',
                'image' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=60',
                'lessons_count' => 24,
                'progress' => 10,
            ],
            [
                'id' => 3,
                'title' => 'Docker for Beginners',
                'description' => 'Containerize your applications and understand the power of Docker ecosystem.',
                'image' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=60',
                'lessons_count' => 8,
                'progress' => 0,
            ],
        ];

        return Inertia::render('Student/Courses', [
            'courses' => $courses,
        ]);
    }

    public function courseShow($id)
    {
        $course = [
            'id' => $id,
            'title' => 'DevOps Mastery: CI/CD Pipelines',
            'instructor' => 'Ajay Upadhyay',
            'lessons' => [
                ['id' => 1, 'title' => '01 - Introduction to DevOps', 'duration' => '10:05', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                ['id' => 2, 'title' => '02 - Setting up Jenkins', 'duration' => '15:20', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                ['id' => 3, 'title' => '03 - Building Docker Images', 'duration' => '20:45', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                ['id' => 4, 'title' => '04 - Deploying to Staging', 'duration' => '12:30', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
            ],
        ];

        return Inertia::render('Student/CourseShow', [
            'course' => $course,
        ]);
    }

    public function profile(Request $request)
    {
        return Inertia::render('Student/Profile', [
            'user' => $request->user()->only('name', 'email'),
        ]);
    }
}
