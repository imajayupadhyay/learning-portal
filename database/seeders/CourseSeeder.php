<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'DevOps Mastery: CI/CD Pipelines',
                'description' => 'Learn how to build and deploy high-performance CI/CD pipelines from scratch.',
                'instructor' => 'Ajay Upadhyay',
                'image' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=60',
                'lessons' => [
                    ['title' => 'Introduction to DevOps', 'duration' => '10:05', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Setting up Jenkins', 'duration' => '15:20', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Building Docker Images', 'duration' => '20:45', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Deploying to Staging', 'duration' => '12:30', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Pipeline Orchestration', 'duration' => '18:10', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Monitoring & Alerts', 'duration' => '14:55', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                ],
            ],
            [
                'title' => 'Kubernetes Administration',
                'description' => 'Master K8s orchestration, deployments, and cluster management in the real world.',
                'instructor' => 'Ajay Upadhyay',
                'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&auto=format&fit=crop&q=60',
                'lessons' => [
                    ['title' => 'K8s Architecture Overview', 'duration' => '12:00', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Pods and Deployments', 'duration' => '18:30', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Services and Networking', 'duration' => '22:15', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'ConfigMaps and Secrets', 'duration' => '16:40', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Helm Charts', 'duration' => '20:00', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Cluster Monitoring with Prometheus', 'duration' => '25:10', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'RBAC and Security', 'duration' => '19:45', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Auto-scaling Strategies', 'duration' => '17:20', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                ],
            ],
            [
                'title' => 'Docker for Beginners',
                'description' => 'Containerize your applications and understand the power of Docker ecosystem.',
                'instructor' => 'Ajay Upadhyay',
                'image' => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?w=800&auto=format&fit=crop&q=60',
                'lessons' => [
                    ['title' => 'What is Docker?', 'duration' => '8:30', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Installing Docker', 'duration' => '6:15', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Your First Container', 'duration' => '12:00', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Dockerfile Deep Dive', 'duration' => '18:45', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Docker Compose', 'duration' => '15:30', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                ],
            ],
            [
                'title' => 'Linux System Administration',
                'description' => 'Master Linux from the command line to enterprise server management.',
                'instructor' => 'Ajay Upadhyay',
                'image' => 'https://images.unsplash.com/photo-1629654297299-c8506221ca97?w=800&auto=format&fit=crop&q=60',
                'lessons' => [
                    ['title' => 'Linux File System', 'duration' => '14:00', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'User & Permission Management', 'duration' => '16:30', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Shell Scripting Basics', 'duration' => '20:15', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Process Management', 'duration' => '11:45', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Networking Fundamentals', 'duration' => '19:00', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                    ['title' => 'Systemd and Services', 'duration' => '13:20', 'video_id' => '1A2B3C4D5E6F7G8H9I0J'],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $lessons = $courseData['lessons'];
            unset($courseData['lessons']);

            $course = Course::create($courseData);

            foreach ($lessons as $index => $lessonData) {
                $course->lessons()->create(array_merge($lessonData, [
                    'sort_order' => $index + 1,
                ]));
            }
        }
    }
}
