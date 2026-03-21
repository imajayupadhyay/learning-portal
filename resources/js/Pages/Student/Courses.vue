<template>
    <StudentLayout>
        <div class="space-y-12 pb-12">
            <!-- Glass Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-10">
                <div class="max-w-2xl">
                    <h1 class="text-5xl font-black tracking-tighter italic mb-6">Course Library.</h1>
                    <p class="text-neutral-500 text-lg font-medium leading-relaxed">
                        Precision-engineered curriculum for the next generation of 
                        <span class="text-red-600 font-bold border-b-2 border-red-200">DevOps professionals.</span>
                    </p>
                </div>
                <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md p-2 rounded-[24px] border border-white shadow-sm">
                    <button class="px-8 py-3 bg-red-600 text-white rounded-[20px] text-[10px] font-black uppercase tracking-widest shadow-xl shadow-red-600/20 transition-all">All Modules</button>
                    <button class="px-8 py-3 text-neutral-400 hover:text-black rounded-[20px] text-[10px] font-black uppercase tracking-widest transition-all">Latest</button>
                </div>
            </div>

            <!-- Glass Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                <div v-for="course in courses" :key="course.id"
                    class="group bg-white/60 backdrop-blur-xl border border-white rounded-[24px] overflow-hidden hover:shadow-[0_20px_40px_-12px_rgba(0,0,0,0.1)] transition-all duration-700 flex flex-col h-full ring-1 ring-black/[0.02]">

                    <!-- Media Area -->
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img :src="course.image" class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60 group-hover:opacity-20 transition-opacity"></div>

                        <div class="absolute top-3 left-3">
                            <div class="px-2.5 py-1 bg-white/90 backdrop-blur-xl rounded-lg shadow-sm border border-white">
                                <span class="text-[8px] font-black uppercase tracking-[0.2em] text-black">
                                    {{ course.lessons_count }} Lessons
                                </span>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                            <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-xl">
                                <Play class="w-3.5 h-3.5 fill-black" />
                            </div>
                        </div>
                    </div>

                    <!-- Description Area -->
                    <div class="p-4 flex-1 flex flex-col justify-between relative">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 group-hover:animate-ping"></span>
                                <span class="text-[8px] font-black uppercase tracking-[0.2em] text-neutral-400 group-hover:text-red-600 transition-colors">Core Curriculum</span>
                            </div>
                            <h3 class="text-sm font-black tracking-tight leading-snug mb-2 group-hover:translate-x-0.5 transition-transform duration-500">{{ course.title }}</h3>
                            <p class="text-neutral-500 text-xs font-medium line-clamp-2 leading-relaxed mb-4">{{ course.description }}</p>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-black/5">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[8px] font-black uppercase tracking-[0.2em]" :class="course.progress >= 100 ? 'text-emerald-600' : course.progress > 0 ? 'text-red-600' : 'text-neutral-400'">
                                        {{ course.progress >= 100 ? 'Completed' : course.progress > 0 ? 'In Progress' : 'Not Started' }}
                                    </span>
                                    <span class="text-[9px] font-black" :class="course.progress >= 100 ? 'text-emerald-600' : 'text-neutral-600'">{{ course.progress }}%</span>
                                </div>
                                <div class="h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000"
                                        :class="course.progress >= 100 ? 'bg-emerald-500' : 'bg-red-600'"
                                        :style="{ width: (course.progress || 0) + '%' }"></div>
                                </div>
                            </div>

                            <Link :href="`/student/courses/${course.id}`" class="w-full flex items-center justify-center gap-2 py-3 bg-red-600 text-white rounded-xl text-[9px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all duration-500 group-hover:-translate-y-0.5">
                                {{ course.progress >= 100 ? 'Review' : course.progress > 0 ? 'Resume' : 'Start' }}
                                <ArrowRight class="w-3.5 h-3.5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Play } from 'lucide-vue-next';

defineProps({
    courses: Array,
});
</script>
