<template>
    <StudentLayout>
        <div class="flex flex-col lg:flex-row gap-12 h-[calc(100vh-160px)]">
            <!-- Left Side: Cinematic Player Area -->
            <div class="flex-1 flex flex-col gap-8 min-w-0">
                <!-- Video Player -->
                <div class="bg-black rounded-[40px] overflow-hidden shadow-2xl relative group ring-1 ring-white/10 aspect-video">
                    <iframe
                        v-if="currentLesson"
                        :src="`https://drive.google.com/file/d/${currentLesson.video_id}/preview`"
                        class="w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-700"
                        allow="autoplay"
                    ></iframe>
                    <div v-else class="w-full h-full flex items-center justify-center">
                        <PlayCircle class="w-20 h-20 text-white/20 animate-pulse" />
                    </div>
                </div>

                <!-- Lesson Info -->
                <div class="bg-white border border-neutral-100 rounded-[32px] p-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-black text-white text-[10px] font-black uppercase tracking-widest rounded-full">Now Playing</span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Lesson {{ currentLesson?.id }}</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-tight">{{ currentLesson?.title || course.title }}</h1>
                        <p class="text-neutral-500 mt-4 font-medium flex items-center gap-2">
                            <User class="w-4 h-4" />
                            Instructor: <span class="text-black font-bold">{{ course.instructor }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="flex items-center gap-3 px-8 py-4 border-2 border-neutral-100 rounded-2xl text-xs font-black uppercase tracking-widest hover:border-black transition-all group">
                            <Download class="w-4 h-4 group-hover:-translate-y-1 transition-transform" />
                            Assets
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar Playback -->
            <div class="lg:w-[400px] flex flex-col h-full min-w-0">
                <div class="bg-white border border-neutral-100 rounded-[40px] flex flex-col overflow-hidden shadow-sm h-full ring-1 ring-black/[0.02]">
                    <div class="p-8 border-b border-neutral-50 bg-neutral-50/30">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-black tracking-tight">Course Content</h2>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">{{ course.lessons.length }} Items</span>
                        </div>
                        <!-- Overall Progress -->
                        <div class="mt-6">
                            <div class="h-1 bg-neutral-200 rounded-full overflow-hidden">
                                <div class="h-full bg-black rounded-full w-1/4 transition-all duration-1000"></div>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-neutral-400 mt-3">Course Progress: 25%</p>
                        </div>
                    </div>

                    <!-- Lesson List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                        <button
                            v-for="lesson in course.lessons"
                            :key="lesson.id"
                            @click="currentLesson = lesson"
                            :class="[
                                currentLesson?.id === lesson.id
                                    ? 'bg-black text-white shadow-2xl shadow-black/20 translate-x-2'
                                    : 'hover:bg-neutral-50 text-neutral-500 hover:text-black',
                                'w-full flex items-start gap-4 p-5 rounded-[24px] text-left transition-all duration-500 group relative overflow-hidden'
                            ]"
                        >
                            <div :class="[
                                currentLesson?.id === lesson.id ? 'bg-white/20 text-white' : 'bg-neutral-100 text-neutral-400 group-hover:bg-black group-hover:text-white',
                                'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-xs font-black transition-all duration-500'
                            ]">
                                {{ lesson.id.toString().padStart(2, '0') }}
                            </div>
                            <div class="flex-1 min-w-0 pt-1">
                                <span class="text-sm font-bold leading-tight block truncate group-hover:whitespace-normal transition-all">{{ lesson.title }}</span>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">
                                        {{ lesson.duration }}
                                    </span>
                                    <div v-if="currentLesson?.id === lesson.id" class="flex gap-1">
                                        <div v-for="i in 3" :key="i" class="w-1 h-1 rounded-full bg-white animate-pulse" :style="{ animationDelay: `${i * 200}ms` }"></div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="p-6 border-t border-neutral-50 flex items-center justify-between">
                         <button class="p-4 hover:bg-neutral-50 rounded-2xl transition-colors text-neutral-400 hover:text-black">
                             <ChevronLeft class="w-5 h-5" />
                         </button>
                         <span class="text-xs font-black uppercase tracking-widest">Next Lesson</span>
                         <button class="p-4 bg-black text-white rounded-2xl hover:bg-neutral-800 transition-all">
                             <ChevronRight class="w-5 h-5" />
                         </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { ref } from 'vue';
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { 
    PlayCircle, 
    Download, 
    CheckCircle2, 
    ChevronRight, 
    ChevronLeft,
    User,
    Clock
} from 'lucide-vue-next';

const props = defineProps({
    course: Object,
});

const currentLesson = ref(props.course.lessons[0]);
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #E5E5E5;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #000;
}
</style>
