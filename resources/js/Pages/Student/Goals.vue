<template>
    <Head title="My Goals">
        <meta name="description" content="Set and track your learning goals. Stay motivated by monitoring your course progress." />
    </Head>
    <StudentLayout>
        <div class="space-y-6 lg:space-y-12 pb-8 lg:pb-12">
            <!-- Active Goals Section -->
            <div v-if="goalCourses.length > 0" class="space-y-4 lg:space-y-8">
                <div class="flex items-center justify-between px-1 lg:px-2">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div class="w-9 h-9 lg:w-11 lg:h-11 bg-indigo-500 rounded-xl lg:rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <Crosshair class="w-4 h-4 lg:w-5 lg:h-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl lg:text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100">Tracking</h2>
                            <p class="text-[9px] lg:text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500 mt-0.5">{{ goalCourses.length }} active goal{{ goalCourses.length !== 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                    <div v-for="course in goalCourses" :key="course.id"
                        class="group bg-white/60 dark:bg-stone-900/60 backdrop-blur-xl border border-indigo-100/20 dark:border-stone-700/50 rounded-xl lg:rounded-[28px] overflow-hidden hover:shadow-xl hover:shadow-indigo-500/5 dark:hover:shadow-stone-950/30 transition-all duration-500 ring-1 ring-indigo-100/50 dark:ring-stone-700/30">

                        <div class="flex flex-col sm:flex-row">
                            <!-- Image -->
                            <div class="sm:w-40 lg:w-52 h-36 sm:h-auto overflow-hidden relative flex-shrink-0">
                                <img :src="course.image" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-gradient-to-t sm:bg-gradient-to-r from-black/30 via-transparent to-transparent"></div>
                                <div v-if="course.progress === 100" class="absolute top-2.5 left-2.5 lg:top-3 lg:left-3">
                                    <div class="px-2.5 py-1 lg:px-3 lg:py-1.5 bg-teal-500 text-white rounded-lg text-[8px] lg:text-[9px] font-bold uppercase tracking-widest shadow-lg flex items-center gap-1">
                                        <CheckCircle2 class="w-3 h-3" /> Done
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 lg:p-6 flex flex-col justify-between gap-3 lg:gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5 lg:mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[8px] lg:text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500">{{ course.completed_lessons }}/{{ course.total_lessons }} Lessons</span>
                                        </div>
                                        <button @click="removeGoal(course.id)"
                                            class="w-7 h-7 lg:w-8 lg:h-8 rounded-lg lg:rounded-xl flex items-center justify-center hover:bg-red-50 dark:hover:bg-red-950/50 text-stone-400 dark:text-stone-500 hover:text-red-500 transition-all"
                                            title="Remove from goals">
                                            <X class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                    <h3 class="text-sm lg:text-lg font-heading font-extrabold tracking-tight leading-tight text-stone-800 dark:text-stone-100 line-clamp-2">{{ course.title }}</h3>
                                </div>

                                <!-- Progress -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.2em]"
                                            :class="course.progress === 100 ? 'text-teal-500' : course.progress > 0 ? 'text-indigo-500 dark:text-indigo-400' : 'text-stone-400 dark:text-stone-500'">
                                            {{ course.progress === 100 ? 'Completed' : course.progress > 0 ? 'In Progress' : 'Not Started' }}
                                        </span>
                                        <span class="text-[10px] lg:text-xs font-extrabold tabular-nums"
                                            :class="course.progress === 100 ? 'text-teal-500' : 'text-indigo-500 dark:text-indigo-400'">{{ course.progress }}%</span>
                                    </div>
                                    <div class="h-1.5 lg:h-2 bg-indigo-50 dark:bg-indigo-950/50 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                            :class="course.progress === 100 ? 'bg-teal-500' : 'bg-indigo-500'"
                                            :style="{ width: course.progress + '%' }">
                                        </div>
                                    </div>
                                </div>

                                <Link :href="`/student/courses/${course.id}`"
                                    class="w-full flex items-center justify-center gap-2 py-2.5 lg:py-3 bg-indigo-500 text-white rounded-xl lg:rounded-2xl text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-500">
                                    {{ course.progress === 100 ? 'Review' : course.progress > 0 ? 'Continue' : 'Start' }}
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12 lg:py-20">
                <div class="w-20 h-20 lg:w-24 lg:h-24 bg-indigo-50 dark:bg-indigo-950/50 rounded-2xl lg:rounded-[28px] flex items-center justify-center mx-auto mb-6 lg:mb-8 border border-indigo-100/30 dark:border-indigo-900/50">
                    <Target class="w-10 h-10 lg:w-12 lg:h-12 text-indigo-300 dark:text-indigo-600" />
                </div>
                <h3 class="text-xl lg:text-2xl font-heading font-extrabold tracking-tight mb-3 text-stone-800 dark:text-stone-100">No Goals Set Yet</h3>
                <p class="text-stone-500 dark:text-stone-400 font-medium max-w-md mx-auto text-sm">Pick courses from the catalog below to start tracking your learning journey.</p>
            </div>

            <!-- Available Courses — Compact Catalog -->
            <div v-if="availableCourses.length > 0" class="space-y-4 lg:space-y-6">
                <div class="flex items-center justify-between px-1 lg:px-2">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div class="w-9 h-9 lg:w-11 lg:h-11 bg-stone-100 dark:bg-stone-800 rounded-xl lg:rounded-2xl flex items-center justify-center">
                            <BookOpen class="w-4 h-4 lg:w-5 lg:h-5 text-stone-500 dark:text-stone-400" />
                        </div>
                        <div>
                            <h2 class="text-xl lg:text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100">Catalog</h2>
                            <p class="text-[9px] lg:text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500 mt-0.5">{{ availableCourses.length }} available</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-xl lg:rounded-[28px] overflow-hidden shadow-sm dark:shadow-stone-950/20 divide-y divide-stone-100/80 dark:divide-stone-800/80">
                    <div v-for="course in availableCourses" :key="course.id"
                        class="flex items-center gap-3 lg:gap-5 p-3 lg:p-4 hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20 transition-all duration-300 group">

                        <!-- Course Number / Icon -->
                        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl lg:rounded-2xl bg-stone-100 dark:bg-stone-800 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-500 group-hover:shadow-lg group-hover:shadow-indigo-500/20 transition-all duration-500">
                            <PlayCircle class="w-4 h-4 lg:w-5 lg:h-5 text-stone-400 dark:text-stone-500 group-hover:text-white transition-colors duration-500" />
                        </div>

                        <!-- Course Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-xs lg:text-sm font-heading font-bold tracking-tight text-stone-800 dark:text-stone-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ course.title }}</h3>
                            <p class="text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500 mt-0.5">{{ course.total_lessons }} Lessons</p>
                        </div>

                        <!-- Add Button -->
                        <button @click="addGoal(course.id)"
                            class="flex items-center gap-1.5 lg:gap-2 px-3 lg:px-5 py-2 lg:py-2.5 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100/50 dark:border-indigo-900/50 text-indigo-500 dark:text-indigo-400 rounded-lg lg:rounded-xl text-[9px] lg:text-[10px] font-bold uppercase tracking-widest hover:bg-indigo-500 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/20 transition-all duration-300 flex-shrink-0">
                            <Plus class="w-3.5 h-3.5" />
                            <span class="hidden sm:inline">Add</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowRight, X, Plus, Target, Crosshair, BookOpen, PlayCircle, CheckCircle2 } from 'lucide-vue-next';

defineProps({
    goalCourses: Array,
    availableCourses: Array,
});

const addGoal = (courseId) => {
    router.post('/student/goals', { course_id: courseId });
};

const removeGoal = (courseId) => {
    router.delete(`/student/goals/${courseId}`);
};
</script>
