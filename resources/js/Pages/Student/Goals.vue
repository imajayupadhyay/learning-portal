<template>
    <Head title="My Goals">
        <meta name="description" content="Set and track your learning goals. Stay motivated by monitoring your course progress." />
    </Head>
    <StudentLayout>
        <div class="space-y-6 lg:space-y-12 pb-8 lg:pb-12">
            <!-- Active Goals Section -->
            <div v-if="goalCourses.length > 0" class="space-y-4 lg:space-y-8">
                <div class="flex items-center justify-between px-1 lg:px-2">
                    <h2 class="text-xl lg:text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100">Tracking</h2>
                    <span class="text-[9px] lg:text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">Your active goals</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-8">
                    <div v-for="course in goalCourses" :key="course.id"
                        class="group bg-white/60 dark:bg-stone-900/60 backdrop-blur-xl border border-indigo-100/20 dark:border-stone-700/50 rounded-xl lg:rounded-[32px] overflow-hidden hover:shadow-xl hover:shadow-indigo-500/5 dark:hover:shadow-stone-950/30 transition-all duration-500 ring-1 ring-indigo-100/50 dark:ring-stone-700/30">

                        <!-- Course Image -->
                        <div class="h-36 lg:h-48 overflow-hidden relative">
                            <img :src="course.image" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <div class="absolute top-3 right-3 lg:top-4 lg:right-4">
                                <button @click="removeGoal(course.id)"
                                    class="w-8 h-8 lg:w-10 lg:h-10 bg-white/90 dark:bg-stone-900/90 backdrop-blur-xl rounded-lg lg:rounded-xl flex items-center justify-center hover:bg-red-50 dark:hover:bg-red-950/50 transition-all shadow-lg"
                                    title="Remove from goals">
                                    <X class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-stone-500 dark:text-stone-400 hover:text-red-500" />
                                </button>
                            </div>
                            <div v-if="course.progress === 100" class="absolute top-3 left-3 lg:top-4 lg:left-4">
                                <div class="px-3 py-1.5 lg:px-4 lg:py-2 bg-teal-500 text-white rounded-lg lg:rounded-xl text-[9px] lg:text-[10px] font-bold uppercase tracking-widest shadow-lg">
                                    Completed
                                </div>
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="p-4 lg:p-8 space-y-4 lg:space-y-6">
                            <div>
                                <div class="flex items-center gap-2 lg:gap-3 mb-2 lg:mb-3">
                                    <span class="text-[8px] lg:text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400 dark:text-stone-500">{{ course.total_lessons }} Lessons</span>
                                    <span class="text-[8px] lg:text-[9px] font-bold uppercase tracking-[0.3em] text-indigo-500 dark:text-indigo-400">{{ course.completed_lessons }} Done</span>
                                </div>
                                <h3 class="text-base lg:text-xl font-heading font-extrabold tracking-tight leading-tight text-stone-800 dark:text-stone-100 group-hover:translate-x-1 transition-transform duration-500">{{ course.title }}</h3>
                                <p class="text-stone-500 dark:text-stone-400 text-xs lg:text-sm mt-1.5 lg:mt-2 line-clamp-2 hidden sm:block">{{ course.description }}</p>
                            </div>

                            <!-- Progress Section -->
                            <div class="space-y-2 lg:space-y-3">
                                <div class="flex items-center justify-between text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.2em]">
                                    <span class="text-stone-400 dark:text-stone-500">Progress</span>
                                    <span :class="course.progress === 100 ? 'text-teal-500' : 'text-indigo-500 dark:text-indigo-400'">{{ course.progress }}%</span>
                                </div>
                                <div class="h-1.5 lg:h-2.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-full overflow-hidden ring-1 ring-indigo-100/50 dark:ring-indigo-900/50">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                        :class="course.progress === 100 ? 'bg-teal-500' : 'bg-indigo-500'"
                                        :style="{ width: course.progress + '%' }">
                                    </div>
                                </div>
                            </div>

                            <!-- Action -->
                            <Link :href="`/student/courses/${course.id}`"
                                class="w-full flex items-center justify-center gap-2 lg:gap-3 py-3 lg:py-4 bg-indigo-500 text-white rounded-xl lg:rounded-2xl text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-500">
                                {{ course.progress === 100 ? 'Review Course' : course.progress > 0 ? 'Continue Learning' : 'Start Course' }}
                                <ArrowRight class="w-3.5 h-3.5 lg:w-4 lg:h-4" />
                            </Link>
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

            <!-- Available Courses Section -->
            <div v-if="availableCourses.length > 0" class="space-y-4 lg:space-y-8">
                <div class="flex items-center justify-between px-1 lg:px-2">
                    <h2 class="text-xl lg:text-3xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100">Course Catalog</h2>
                    <span class="text-[9px] lg:text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">Select to add as goal</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-8">
                    <div v-for="course in availableCourses" :key="course.id"
                        class="group bg-white/40 dark:bg-stone-900/40 backdrop-blur-md border border-stone-100 dark:border-stone-700/50 rounded-xl lg:rounded-[32px] overflow-hidden hover:shadow-lg hover:border-indigo-100/30 dark:hover:border-indigo-900/50 transition-all duration-500">

                        <div class="h-28 lg:h-40 overflow-hidden relative">
                            <img :src="course.image" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>

                        <div class="p-3 lg:p-6 space-y-3 lg:space-y-4">
                            <div>
                                <span class="text-[8px] lg:text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400 dark:text-stone-500">{{ course.total_lessons }} Lessons</span>
                                <h3 class="text-xs lg:text-lg font-heading font-extrabold tracking-tight leading-tight mt-1 lg:mt-2 text-stone-800 dark:text-stone-100 line-clamp-2">{{ course.title }}</h3>
                                <p class="text-stone-500 dark:text-stone-400 text-[10px] lg:text-xs mt-1 lg:mt-2 line-clamp-2 hidden sm:block">{{ course.description }}</p>
                            </div>

                            <button @click="addGoal(course.id)"
                                class="w-full flex items-center justify-center gap-1.5 lg:gap-3 py-2.5 lg:py-3.5 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100/30 dark:border-indigo-900/50 text-indigo-500 dark:text-indigo-400 rounded-lg lg:rounded-2xl text-[8px] lg:text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all duration-500">
                                <Plus class="w-3.5 h-3.5 lg:w-4 lg:h-4" />
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
import { Head, Link, router } from '@inertiajs/vue3';
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
