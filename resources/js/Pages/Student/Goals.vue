<template>
    <StudentLayout>
        <div class="space-y-12 pb-12">
            <!-- Active Goals Section -->
            <div v-if="goalCourses.length > 0" class="space-y-8">
                <div class="flex items-center justify-between px-2">
                    <h2 class="text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100">Tracking</h2>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">Your active goals</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="course in goalCourses" :key="course.id"
                        class="group bg-white/60 dark:bg-stone-900/60 backdrop-blur-xl border border-indigo-100/20 dark:border-stone-700/50 rounded-[32px] overflow-hidden hover:shadow-xl hover:shadow-indigo-500/5 dark:hover:shadow-stone-950/30 transition-all duration-500 ring-1 ring-indigo-100/50 dark:ring-stone-700/30">

                        <!-- Course Image -->
                        <div class="h-48 overflow-hidden relative">
                            <img :src="course.image" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <div class="absolute top-4 right-4">
                                <button @click="removeGoal(course.id)"
                                    class="w-10 h-10 bg-white/90 dark:bg-stone-900/90 backdrop-blur-xl rounded-xl flex items-center justify-center hover:bg-red-50 dark:hover:bg-red-950/50 transition-all shadow-lg"
                                    title="Remove from goals">
                                    <X class="w-4 h-4 text-stone-500 dark:text-stone-400 hover:text-red-500" />
                                </button>
                            </div>
                            <div v-if="course.progress === 100" class="absolute top-4 left-4">
                                <div class="px-4 py-2 bg-teal-500 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg">
                                    Completed
                                </div>
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="p-8 space-y-6">
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400 dark:text-stone-500">{{ course.total_lessons }} Lessons</span>
                                    <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-indigo-500 dark:text-indigo-400">{{ course.completed_lessons }} Done</span>
                                </div>
                                <h3 class="text-xl font-heading font-extrabold tracking-tight leading-tight text-stone-800 dark:text-stone-100 group-hover:translate-x-1 transition-transform duration-500">{{ course.title }}</h3>
                                <p class="text-stone-500 dark:text-stone-400 text-sm mt-2 line-clamp-2">{{ course.description }}</p>
                            </div>

                            <!-- Progress Section -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.2em]">
                                    <span class="text-stone-400 dark:text-stone-500">Progress</span>
                                    <span :class="course.progress === 100 ? 'text-teal-500' : 'text-indigo-500 dark:text-indigo-400'">{{ course.progress }}%</span>
                                </div>
                                <div class="h-2.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-full overflow-hidden ring-1 ring-indigo-100/50 dark:ring-indigo-900/50">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                        :class="course.progress === 100 ? 'bg-teal-500' : 'bg-indigo-500'"
                                        :style="{ width: course.progress + '%' }">
                                    </div>
                                </div>
                            </div>

                            <!-- Action -->
                            <Link :href="`/student/courses/${course.id}`"
                                class="w-full flex items-center justify-center gap-3 py-4 bg-indigo-500 text-white rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-500">
                                {{ course.progress === 100 ? 'Review Course' : course.progress > 0 ? 'Continue Learning' : 'Start Course' }}
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="w-24 h-24 bg-indigo-50 dark:bg-indigo-950/50 rounded-[28px] flex items-center justify-center mx-auto mb-8 border border-indigo-100/30 dark:border-indigo-900/50">
                    <Target class="w-12 h-12 text-indigo-300 dark:text-indigo-600" />
                </div>
                <h3 class="text-2xl font-heading font-extrabold tracking-tight mb-3 text-stone-800 dark:text-stone-100">No Goals Set Yet</h3>
                <p class="text-stone-500 dark:text-stone-400 font-medium max-w-md mx-auto">Pick courses from the catalog below to start tracking your learning journey.</p>
            </div>

            <!-- Available Courses Section -->
            <div v-if="availableCourses.length > 0" class="space-y-8">
                <div class="flex items-center justify-between px-2">
                    <h2 class="text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100">Course Catalog</h2>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">Select to add as goal</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="course in availableCourses" :key="course.id"
                        class="group bg-white/40 dark:bg-stone-900/40 backdrop-blur-md border border-stone-100 dark:border-stone-700/50 rounded-[32px] overflow-hidden hover:shadow-lg hover:border-indigo-100/30 dark:hover:border-indigo-900/50 transition-all duration-500">

                        <div class="h-40 overflow-hidden relative">
                            <img :src="course.image" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400 dark:text-stone-500">{{ course.total_lessons }} Lessons</span>
                                <h3 class="text-lg font-heading font-extrabold tracking-tight leading-tight mt-2 text-stone-800 dark:text-stone-100">{{ course.title }}</h3>
                                <p class="text-stone-500 dark:text-stone-400 text-xs mt-2 line-clamp-2">{{ course.description }}</p>
                            </div>

                            <button @click="addGoal(course.id)"
                                class="w-full flex items-center justify-center gap-3 py-3.5 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100/30 dark:border-indigo-900/50 text-indigo-500 dark:text-indigo-400 rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all duration-500">
                                <Plus class="w-4 h-4" />
                                Add to Goals
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ArrowRight, X, Plus, Target } from 'lucide-vue-next';

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
