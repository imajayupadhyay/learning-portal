<template>
    <StudentLayout>
        <div class="space-y-12 pb-12">
            <!-- Glass Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-10">
                <div class="max-w-2xl">
                    <h1 class="text-5xl font-black tracking-tighter italic mb-6">Course Library.</h1>
                    <p class="text-neutral-500 text-lg font-medium leading-relaxed">
                        Precision-engineered curriculum for the next generation of 
                        <span class="text-black font-bold border-b-2 border-black/5">DevOps professionals.</span>
                    </p>
                </div>
                <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md p-2 rounded-[24px] border border-white shadow-sm">
                    <button class="px-8 py-3 bg-black text-white rounded-[20px] text-[10px] font-black uppercase tracking-widest shadow-xl shadow-black/10 transition-all">All Modules</button>
                    <button class="px-8 py-3 text-neutral-400 hover:text-black rounded-[20px] text-[10px] font-black uppercase tracking-widest transition-all">Latest</button>
                </div>
            </div>

            <!-- Glass Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <div v-for="course in courses" :key="course.id"
                    class="group bg-white/60 backdrop-blur-xl border border-white rounded-[40px] overflow-hidden hover:shadow-[0_32px_64px_-12px_rgba(0,0,0,0.1)] transition-all duration-700 flex flex-col h-full ring-1 ring-black/[0.02]">
                    
                    <!-- Media Area -->
                    <div class="aspect-[16/11] overflow-hidden relative">
                        <img :src="course.image" class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60 group-hover:opacity-20 transition-opacity"></div>
                        
                        <div class="absolute top-6 left-6">
                            <div class="px-4 py-2 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl shadow-black/5 border border-white">
                                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-black">
                                    {{ course.lessons_count }} Lessons
                                </span>
                            </div>
                        </div>

                        <div class="absolute bottom-6 right-6 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-2xl">
                                <Play class="w-5 h-5 fill-black" />
                            </div>
                        </div>
                    </div>

                    <!-- Description Area -->
                    <div class="p-10 flex-1 flex flex-col justify-between relative">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <span class="w-2 h-2 rounded-full bg-black group-hover:animate-ping"></span>
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-neutral-400 group-hover:text-black transition-colors">Core Curriculum</span>
                            </div>
                            <h3 class="text-3xl font-black tracking-tighter leading-[1.1] mb-6 group-hover:translate-x-1 transition-transform duration-500">{{ course.title }}</h3>
                            <p class="text-neutral-500 text-sm font-medium line-clamp-2 leading-relaxed mb-8">{{ course.description }}</p>
                        </div>

                        <div class="space-y-8 pt-6 border-t border-black/5">
                            <div v-if="course.progress > 0">
                                <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-[0.3em] mb-3">
                                    <span class="text-neutral-400 italic text-[10px]">Syncing...</span>
                                    <span>{{ course.progress }}%</span>
                                </div>
                                <div class="h-1.5 bg-neutral-100/50 rounded-full overflow-hidden p-[2px] ring-1 ring-black/[0.03]">
                                    <div class="h-full bg-black rounded-full transition-all duration-1000" :style="{ width: course.progress + '%' }"></div>
                                </div>
                            </div>

                            <Link :href="`/student/courses/${course.id}`" class="w-full flex items-center justify-center gap-4 py-5 bg-black text-white rounded-[24px] text-[10px] font-black uppercase tracking-[0.4em] hover:bg-neutral-800 hover:shadow-2xl hover:shadow-black/20 transition-all duration-500 group-hover:-translate-y-1">
                                {{ course.progress > 0 ? 'Resume' : 'Start' }}
                                <ArrowRight class="w-4 h-4" />
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
