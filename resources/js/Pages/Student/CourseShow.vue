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
                        allow="autoplay; fullscreen"
                        allowfullscreen
                    ></iframe>
                    <div v-else class="w-full h-full flex items-center justify-center">
                        <PlayCircle class="w-20 h-20 text-white/20 animate-pulse" />
                    </div>
                </div>

                <!-- Lesson Info -->
                <div class="bg-white border border-neutral-100 rounded-[32px] p-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Now Playing</span>
                            <span v-if="currentLesson?.completed" class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-1">
                                <CheckCircle2 class="w-3 h-3" /> Completed
                            </span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-tight">{{ currentLesson?.title || course.title }}</h1>
                        <p class="text-neutral-500 mt-4 font-medium flex items-center gap-2">
                            <User class="w-4 h-4" />
                            Instructor: <span class="text-black font-bold">{{ course.instructor }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Mark Complete Button -->
                        <button
                            v-if="currentLesson"
                            @click="toggleComplete(currentLesson.id)"
                            :disabled="toggling"
                            :class="[
                                currentLesson.completed
                                    ? 'bg-green-50 border-green-200 text-green-700 hover:bg-red-50 hover:border-red-200 hover:text-red-600'
                                    : 'bg-red-50 border-red-200 text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600',
                                'flex items-center gap-3 px-8 py-4 border-2 rounded-2xl text-xs font-black uppercase tracking-widest transition-all duration-300 group disabled:opacity-50'
                            ]"
                        >
                            <CheckCircle2 class="w-5 h-5 transition-transform group-hover:scale-110" />
                            {{ currentLesson.completed ? 'Completed' : 'Mark Complete' }}
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
                            <div class="h-1.5 bg-red-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-1000"
                                    :class="progress === 100 ? 'bg-green-500' : 'bg-red-600'"
                                    :style="{ width: progress + '%' }"></div>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <p class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Course Progress</p>
                                <p class="text-[10px] font-black uppercase tracking-widest" :class="progress === 100 ? 'text-green-600' : 'text-red-600'">{{ progress }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                        <button
                            v-for="(lesson, index) in course.lessons"
                            :key="lesson.id"
                            @click="currentLesson = lesson"
                            :class="[
                                currentLesson?.id === lesson.id
                                    ? 'bg-red-600 text-white shadow-2xl shadow-red-600/20 translate-x-2'
                                    : lesson.completed
                                        ? 'bg-green-50/50 text-green-700 hover:bg-green-50'
                                        : 'hover:bg-red-50 text-neutral-500 hover:text-red-600',
                                'w-full flex items-start gap-4 p-5 rounded-[24px] text-left transition-all duration-500 group relative overflow-hidden'
                            ]"
                        >
                            <!-- Lesson Number / Check Icon -->
                            <div :class="[
                                currentLesson?.id === lesson.id
                                    ? 'bg-white/20 text-white'
                                    : lesson.completed
                                        ? 'bg-green-100 text-green-600'
                                        : 'bg-red-50 text-red-400 group-hover:bg-red-600 group-hover:text-white',
                                'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-xs font-black transition-all duration-500'
                            ]">
                                <CheckCircle2 v-if="lesson.completed && currentLesson?.id !== lesson.id" class="w-5 h-5" />
                                <span v-else>{{ (index + 1).toString().padStart(2, '0') }}</span>
                            </div>
                            <div class="flex-1 min-w-0 pt-1">
                                <span class="text-sm font-bold leading-tight block truncate group-hover:whitespace-normal transition-all">{{ lesson.title }}</span>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">
                                        {{ lesson.duration }}
                                    </span>
                                    <span v-if="lesson.completed && currentLesson?.id !== lesson.id" class="text-[9px] font-black uppercase tracking-widest text-green-500">Done</span>
                                    <div v-if="currentLesson?.id === lesson.id" class="flex gap-1">
                                        <div v-for="i in 3" :key="i" class="w-1 h-1 rounded-full bg-white animate-pulse" :style="{ animationDelay: `${i * 200}ms` }"></div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="p-6 border-t border-neutral-50 flex items-center justify-between">
                         <button @click="prevLesson" class="p-4 hover:bg-red-50 rounded-2xl transition-colors text-neutral-400 hover:text-red-600" :disabled="currentLessonIndex <= 0">
                             <ChevronLeft class="w-5 h-5" />
                         </button>
                         <button @click="toggleComplete(currentLesson?.id)" :disabled="toggling || !currentLesson"
                            class="flex items-center gap-2 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all"
                            :class="currentLesson?.completed ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600 hover:bg-red-600 hover:text-white'">
                            <CheckCircle2 class="w-4 h-4" />
                            {{ currentLesson?.completed ? 'Done' : 'Complete' }}
                         </button>
                         <button @click="nextLesson" class="p-4 bg-red-600 text-white rounded-2xl hover:bg-red-700 transition-all" :disabled="currentLessonIndex >= course.lessons.length - 1">
                             <ChevronRight class="w-5 h-5" />
                         </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';
import {
    PlayCircle,
    CheckCircle2,
    ChevronRight,
    ChevronLeft,
    User
} from 'lucide-vue-next';

const props = defineProps({
    course: Object,
    progress: Number,
    completedLessonIds: Array,
});

const currentLesson = ref(props.course.lessons[0]);
const toggling = ref(false);

const currentLessonIndex = computed(() => {
    return props.course.lessons.findIndex(l => l.id === currentLesson.value?.id);
});

const nextLesson = () => {
    const idx = currentLessonIndex.value;
    if (idx < props.course.lessons.length - 1) {
        currentLesson.value = props.course.lessons[idx + 1];
    }
};

const prevLesson = () => {
    const idx = currentLessonIndex.value;
    if (idx > 0) {
        currentLesson.value = props.course.lessons[idx - 1];
    }
};

const toggleComplete = (lessonId) => {
    if (!lessonId || toggling.value) return;
    toggling.value = true;

    router.post('/student/lessons/toggle-complete', {
        lesson_id: lessonId,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            const lesson = props.course.lessons.find(l => l.id === lessonId);
            if (lesson) {
                lesson.completed = !lesson.completed;
            }
            toggling.value = false;
        },
        onError: () => {
            toggling.value = false;
        },
    });
};
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
    background: #DC2626;
}
</style>
