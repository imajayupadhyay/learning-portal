<template>
    <StudentLayout>
        <div class="space-y-10 pb-12">
            <!-- Overall Progress Section -->
            <div class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-[32px] p-10 shadow-sm dark:shadow-stone-950/20">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                            <TrendingUp class="w-6 h-6" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-heading font-extrabold tracking-tight text-stone-800 dark:text-stone-100">Overall Progress</h2>
                            <p class="text-stone-400 dark:text-stone-500 text-xs font-bold uppercase tracking-widest mt-1">{{ completed_lessons }} of {{ total_lessons }} lessons completed</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-heading font-extrabold tracking-tighter" :class="overall_progress === 100 ? 'text-teal-500' : 'text-indigo-500 dark:text-indigo-400'">{{ overall_progress }}%</p>
                    </div>
                </div>
                <div class="h-4 bg-indigo-50 dark:bg-indigo-950/50 rounded-full overflow-hidden ring-1 ring-indigo-100/50 dark:ring-indigo-900/50">
                    <div class="h-full rounded-full transition-all duration-1000 ease-out"
                        :class="overall_progress === 100 ? 'bg-gradient-to-r from-teal-400 to-teal-500' : 'bg-gradient-to-r from-indigo-400 to-indigo-500'"
                        :style="{ width: overall_progress + '%' }">
                    </div>
                </div>
            </div>

            <!-- Modern Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="stat in stats" :key="stat.label"
                    class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-[32px] p-10 shadow-sm dark:shadow-stone-950/20 hover:shadow-xl hover:shadow-indigo-500/5 dark:hover:shadow-stone-950/30 transition-all duration-500 group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-indigo-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                            <Activity class="w-5 h-5" v-if="stat.label === 'Enrolled'" />
                            <CheckCircle class="w-5 h-5" v-else-if="stat.label === 'Completed'" />
                            <Clock class="w-5 h-5" v-else />
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500">Real-time</span>
                    </div>
                    <p class="text-5xl font-heading font-extrabold tracking-tighter mb-2 text-stone-800 dark:text-stone-100">{{ stat.value }}</p>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500">{{ stat.label }}</p>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Activity Column -->
                <div class="lg:col-span-2 space-y-10">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100">Recent Lab</h2>
                        <Link href="/student/courses" class="text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors underline underline-offset-8">Explore All</Link>
                    </div>

                    <div v-for="course in recent_courses" :key="course.id"
                        class="group relative bg-white dark:bg-stone-900 border border-indigo-100/20 dark:border-stone-700/50 rounded-[40px] overflow-hidden hover:shadow-2xl hover:shadow-indigo-500/5 dark:hover:shadow-stone-950/30 transition-all duration-700">
                        <div class="flex flex-col md:flex-row h-full">
                            <div class="md:w-80 h-72 md:h-auto overflow-hidden relative">
                                <img :src="course.image" class="w-full h-full object-cover grayscale transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-700"></div>
                            </div>
                            <div class="p-12 flex-1 flex flex-col justify-between bg-white/40 dark:bg-stone-900/40 backdrop-blur-xl">
                                <div>
                                    <div class="flex items-center gap-3 mb-6">
                                        <span class="px-3 py-1 bg-indigo-500 text-white text-[9px] font-bold uppercase tracking-widest rounded-lg">Level: Pro</span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-ping"></div>
                                    </div>
                                    <h3 class="text-3xl font-heading font-extrabold tracking-tight leading-tight mb-6 text-stone-800 dark:text-stone-100 group-hover:translate-x-2 transition-transform duration-500">{{ course.title }}</h3>
                                </div>

                                <div class="space-y-6">
                                    <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.2em]">
                                        <span class="text-stone-400 dark:text-stone-500">{{ course.completed_lessons }} / {{ course.total_lessons }} Lessons</span>
                                        <span :class="course.progress === 100 ? 'text-teal-500' : 'text-indigo-500 dark:text-indigo-400'">{{ course.progress }}%</span>
                                    </div>
                                    <div class="h-2.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-full overflow-hidden ring-1 ring-indigo-100/50 dark:ring-indigo-900/50">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                            :class="course.progress === 100 ? 'bg-teal-500' : 'bg-indigo-500'"
                                            :style="{ width: course.progress + '%' }">
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <Link :href="`/student/courses/${course.id}`" class="inline-flex items-center gap-4 text-[10px] font-bold uppercase tracking-[0.3em] text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 group/link">
                                            {{ course.progress === 100 ? 'Review' : course.progress > 0 ? 'Continue' : 'Start' }}
                                            <ChevronRight class="w-4 h-4 group-hover/link:translate-x-2 transition-transform" />
                                        </Link>
                                        <span v-if="course.progress === 100" class="text-[9px] font-bold uppercase tracking-widest text-teal-500 flex items-center gap-1">
                                            <CheckCircle class="w-3.5 h-3.5" /> Done
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar column -->
                <div class="space-y-10">
                    <h2 class="text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100 px-2">Feed</h2>
                    <div class="bg-white dark:bg-stone-900 border border-indigo-100/20 dark:border-stone-700/50 rounded-[40px] p-10 space-y-10 shadow-sm dark:shadow-stone-950/20">
                        <div v-for="i in 3" :key="i" class="flex gap-6 group cursor-pointer">
                            <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/50 rounded-[20px] flex-shrink-0 flex items-center justify-center border border-indigo-100/30 dark:border-indigo-900/50 group-hover:bg-indigo-500 group-hover:text-white text-stone-500 dark:text-stone-400 transition-all duration-500 shadow-sm">
                                <MessageSquare class="w-6 h-6" />
                            </div>
                            <div class="pt-1">
                                <h4 class="text-sm font-semibold leading-tight text-stone-700 dark:text-stone-300 group-hover:underline underline-offset-8 decoration-2 decoration-indigo-200 dark:decoration-indigo-800">New deployment strategies added to K8s course</h4>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500 mt-3 flex items-center gap-2">
                                    <div class="w-1 h-1 rounded-full bg-stone-300 dark:bg-stone-600"></div>
                                    12m ago
                                </p>
                            </div>
                        </div>
                        <button class="w-full py-5 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100/30 dark:border-indigo-900/50 rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] text-indigo-500 dark:text-indigo-400 hover:bg-indigo-500 hover:text-white transition-all duration-500 shadow-sm">
                            View Network
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link } from '@inertiajs/vue3';
import {
    ChevronRight,
    Activity,
    CheckCircle,
    Clock,
    MessageSquare,
    TrendingUp
} from 'lucide-vue-next';

defineProps({
    user: Object,
    recent_courses: Array,
    stats: Array,
    overall_progress: Number,
    total_lessons: Number,
    completed_lessons: Number,
});
</script>
