<template>
    <Head title="Admin Dashboard">
        <meta name="description" content="Admin dashboard — manage courses, students, and platform overview." />
    </Head>
    <AdminLayout>
        <div class="space-y-10 pb-12">
            <!-- Hero Welcome Panel -->
            <div class="relative overflow-hidden bg-gradient-to-br from-red-600 to-red-700 rounded-[40px] p-14 text-white shadow-2xl shadow-red-600/30">
                <div class="relative z-10 max-w-3xl">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md text-[10px] font-black uppercase tracking-[0.3em] rounded-full ring-1 ring-white/20">Admin Panel</span>
                    </div>
                    <h1 class="text-5xl font-black tracking-tighter leading-[0.9] mb-6 italic">
                        Welcome back, <br/>
                        <span class="text-red-200">{{ user.name }}.</span>
                    </h1>
                    <p class="text-lg text-white/70 font-medium leading-relaxed max-w-xl">
                        Manage your courses, track student progress, and keep your LMS running smoothly.
                    </p>
                </div>
                <!-- Abstract Glass Elements -->
                <div class="absolute top-[-20%] right-[-10%] w-[500px] h-[500px] bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
                <div class="absolute bottom-[10%] right-[10%] w-32 h-32 bg-white/5 rounded-3xl rotate-12 backdrop-blur-3xl ring-1 ring-white/20"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/60 backdrop-blur-md border border-white rounded-[32px] p-10 shadow-sm hover:shadow-xl hover:shadow-black/5 transition-all duration-500 group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-600/20">
                            <BookOpen class="w-5 h-5" />
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Courses</span>
                    </div>
                    <p class="text-5xl font-black tracking-tighter mb-2 italic">{{ stats.total_courses }}</p>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-neutral-400">Total Courses</p>
                </div>

                <div class="bg-white/60 backdrop-blur-md border border-white rounded-[32px] p-10 shadow-sm hover:shadow-xl hover:shadow-black/5 transition-all duration-500 group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-600/20">
                            <PlayCircle class="w-5 h-5" />
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Lessons</span>
                    </div>
                    <p class="text-5xl font-black tracking-tighter mb-2 italic">{{ stats.total_lessons }}</p>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-neutral-400">Total Lessons</p>
                </div>

                <div class="bg-white/60 backdrop-blur-md border border-white rounded-[32px] p-10 shadow-sm hover:shadow-xl hover:shadow-black/5 transition-all duration-500 group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-600/20">
                            <Users class="w-5 h-5" />
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Students</span>
                    </div>
                    <p class="text-5xl font-black tracking-tighter mb-2 italic">{{ stats.total_students }}</p>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-neutral-400">Registered Students</p>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Courses Column -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-3xl font-black tracking-tighter italic">Courses</h2>
                        <span class="text-[10px] font-black uppercase tracking-widest text-neutral-400">{{ courses.length }} Total</span>
                    </div>

                    <div v-if="courses.length > 0" class="space-y-4">
                        <div v-for="course in courses" :key="course.id"
                            class="group bg-white/60 backdrop-blur-md border border-white rounded-[28px] p-6 hover:shadow-xl hover:shadow-black/5 transition-all duration-500 flex items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0 bg-neutral-100">
                                <img v-if="course.image" :src="course.image" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <BookOpen class="w-6 h-6 text-neutral-300" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold tracking-tight leading-tight truncate">{{ course.title }}</h3>
                                <p class="text-[10px] font-black uppercase tracking-widest text-neutral-400 mt-1">{{ course.lessons_count }} Lessons</p>
                            </div>
                            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all duration-300 flex-shrink-0">
                                <ChevronRight class="w-5 h-5" />
                            </div>
                        </div>
                    </div>

                    <div v-else class="bg-white/40 border border-neutral-100 rounded-[28px] p-12 text-center">
                        <BookOpen class="w-10 h-10 text-neutral-300 mx-auto mb-4" />
                        <p class="text-neutral-400 font-bold">No courses created yet.</p>
                    </div>
                </div>

                <!-- Recent Students Column -->
                <div class="space-y-8">
                    <h2 class="text-3xl font-black tracking-tighter italic px-2">Students</h2>
                    <div class="bg-white border border-neutral-100 rounded-[40px] p-8 space-y-6 shadow-sm">
                        <div v-if="recentStudents.length > 0">
                            <div v-for="student in recentStudents" :key="student.id" class="flex items-center gap-4 py-4 group">
                                <div class="w-11 h-11 bg-red-50 rounded-[14px] flex items-center justify-center text-red-600 text-[11px] font-black flex-shrink-0 group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                                    {{ student.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold leading-tight truncate">{{ student.name }}</p>
                                    <p class="text-[10px] text-neutral-400 font-bold truncate mt-0.5">{{ student.email }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <Users class="w-8 h-8 text-neutral-300 mx-auto mb-3" />
                            <p class="text-neutral-400 text-sm font-bold">No students yet.</p>
                        </div>
                    </div>

                    <!-- Account Info Card -->
                    <div class="bg-white border border-neutral-100 rounded-[40px] p-8 shadow-sm space-y-5">
                        <h3 class="text-lg font-black tracking-tight">Your Account</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Name</p>
                                <p class="text-sm font-bold mt-1">{{ user.name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Email</p>
                                <p class="text-sm font-bold mt-1">{{ user.email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Role</p>
                                <span class="inline-flex items-center px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-widest mt-1">Administrator</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { BookOpen, PlayCircle, Users, ChevronRight } from 'lucide-vue-next';

defineProps({
    user: Object,
    stats: Object,
    recentStudents: Array,
    courses: Array,
});
</script>
