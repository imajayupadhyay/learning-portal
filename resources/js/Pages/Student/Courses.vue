<template>
    <StudentLayout>
        <div class="space-y-12 pb-12">
            <!-- Category Filter -->
            <div class="flex items-center gap-3 flex-wrap bg-white/50 dark:bg-stone-900/50 backdrop-blur-md p-2 rounded-[24px] border border-indigo-100/20 dark:border-stone-700/50 shadow-sm dark:shadow-stone-950/20">
                <button @click="filterCategory(null)"
                    :class="!activeCategory ? 'bg-indigo-500 text-white shadow-xl shadow-indigo-500/20' : 'text-stone-400 dark:text-stone-500 hover:text-stone-700 dark:hover:text-stone-300 hover:bg-white/60 dark:hover:bg-stone-800/60'"
                    class="px-6 py-2.5 rounded-[18px] text-[10px] font-bold uppercase tracking-widest transition-all">
                    All
                </button>
                <template v-for="cat in categories" :key="cat.id">
                    <button @click="filterCategory(cat.slug)"
                        :class="activeCategory === cat.slug ? 'bg-indigo-500 text-white shadow-xl shadow-indigo-500/20' : 'text-stone-400 dark:text-stone-500 hover:text-stone-700 dark:hover:text-stone-300 hover:bg-white/60 dark:hover:bg-stone-800/60'"
                        class="px-6 py-2.5 rounded-[18px] text-[10px] font-bold uppercase tracking-widest transition-all">
                        {{ cat.name }}
                    </button>
                    <button v-for="child in cat.children" :key="child.id" @click="filterCategory(child.slug)"
                        :class="activeCategory === child.slug ? 'bg-indigo-500 text-white shadow-xl shadow-indigo-500/20' : 'text-stone-500 dark:text-stone-400 hover:text-stone-700 dark:hover:text-stone-300 hover:bg-white/60 dark:hover:bg-stone-800/60'"
                        class="px-5 py-2 rounded-[16px] text-[9px] font-bold uppercase tracking-widest transition-all border border-stone-100 dark:border-stone-700">
                        {{ child.name }}
                    </button>
                </template>
            </div>

            <!-- Glass Course Grid -->
            <div v-if="courses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                <div v-for="course in courses" :key="course.id"
                    class="group bg-white/60 dark:bg-stone-900/60 backdrop-blur-xl border border-indigo-100/20 dark:border-stone-700/50 rounded-[24px] overflow-hidden hover:shadow-[0_20px_40px_-12px_rgba(99,102,241,0.1)] dark:hover:shadow-[0_20px_40px_-12px_rgba(0,0,0,0.3)] transition-all duration-700 flex flex-col h-full ring-1 ring-indigo-500/[0.02] dark:ring-stone-700/30">

                    <!-- Media Area -->
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img :src="course.image" class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60 group-hover:opacity-20 transition-opacity"></div>

                        <div class="absolute top-3 left-3 flex items-center gap-2">
                            <div class="px-2.5 py-1 bg-white/90 dark:bg-stone-900/90 backdrop-blur-xl rounded-lg shadow-sm border border-white dark:border-stone-700">
                                <span class="text-[8px] font-bold uppercase tracking-[0.2em] text-stone-700 dark:text-stone-300">
                                    {{ course.lessons_count }} Lessons
                                </span>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                            <div class="w-9 h-9 bg-white dark:bg-stone-800 rounded-xl flex items-center justify-center shadow-xl">
                                <Play class="w-3.5 h-3.5 fill-stone-800 dark:fill-stone-200" />
                            </div>
                        </div>
                    </div>

                    <!-- Description Area -->
                    <div class="p-4 flex-1 flex flex-col justify-between relative">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 group-hover:animate-ping"></span>
                                <span class="text-[8px] font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ course.category_name || 'Uncategorized' }}
                                </span>
                            </div>
                            <h3 class="text-sm font-heading font-extrabold tracking-tight leading-snug mb-2 text-stone-800 dark:text-stone-100 group-hover:translate-x-0.5 transition-transform duration-500">{{ course.title }}</h3>
                            <p class="text-stone-500 dark:text-stone-400 text-xs font-medium line-clamp-2 leading-relaxed mb-4">{{ course.description }}</p>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-stone-100/50 dark:border-stone-700/50">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[8px] font-bold uppercase tracking-[0.2em]" :class="course.progress >= 100 ? 'text-teal-500' : course.progress > 0 ? 'text-indigo-500 dark:text-indigo-400' : 'text-stone-400 dark:text-stone-500'">
                                        {{ course.progress >= 100 ? 'Completed' : course.progress > 0 ? 'In Progress' : 'Not Started' }}
                                    </span>
                                    <span class="text-[9px] font-bold" :class="course.progress >= 100 ? 'text-teal-500' : 'text-stone-600 dark:text-stone-400'">{{ course.progress }}%</span>
                                </div>
                                <div class="h-1.5 bg-stone-100 dark:bg-stone-800 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000"
                                        :class="course.progress >= 100 ? 'bg-teal-500' : 'bg-indigo-500'"
                                        :style="{ width: (course.progress || 0) + '%' }"></div>
                                </div>
                            </div>

                            <Link :href="`/student/courses/${course.id}`" class="w-full flex items-center justify-center gap-2 py-3 bg-indigo-500 text-white rounded-xl text-[9px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-500 group-hover:-translate-y-0.5">
                                {{ course.progress >= 100 ? 'Review' : course.progress > 0 ? 'Resume' : 'Start' }}
                                <ArrowRight class="w-3.5 h-3.5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="w-24 h-24 bg-indigo-50 dark:bg-indigo-950/50 rounded-[28px] flex items-center justify-center mx-auto mb-8 border border-indigo-100/30 dark:border-indigo-900/50">
                    <BookOpen class="w-12 h-12 text-indigo-300 dark:text-indigo-600" />
                </div>
                <h3 class="text-2xl font-heading font-extrabold tracking-tight mb-3 text-stone-800 dark:text-stone-100">No Courses Found</h3>
                <p class="text-stone-500 dark:text-stone-400 font-medium max-w-md mx-auto">No courses match this category filter.</p>
                <button @click="filterCategory(null)" class="mt-6 px-8 py-3 bg-indigo-500 text-white rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 transition-all">
                    View All Courses
                </button>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ArrowRight, Play, BookOpen } from 'lucide-vue-next';

const props = defineProps({
    courses: Array,
    categories: Array,
    activeCategory: String,
});

const filterCategory = (slug) => {
    router.get('/student/courses', slug ? { category: slug } : {}, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>
