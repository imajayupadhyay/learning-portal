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
                <div class="bg-white dark:bg-stone-900 border border-indigo-100/20 dark:border-stone-700/50 rounded-[32px] p-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-indigo-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full">Now Playing</span>
                            <span v-if="currentLesson?.completed" class="px-3 py-1 bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 text-[10px] font-bold uppercase tracking-widest rounded-full flex items-center gap-1">
                                <CheckCircle2 class="w-3 h-3" /> Completed
                            </span>
                        </div>
                        <h1 class="text-3xl font-heading font-extrabold tracking-tight leading-tight text-stone-800 dark:text-stone-100">{{ currentLesson?.title || course.title }}</h1>
                        <p class="text-stone-500 dark:text-stone-400 mt-4 font-medium flex items-center gap-2">
                            <User class="w-4 h-4" />
                            Instructor: <span class="text-stone-800 dark:text-stone-200 font-bold">{{ course.instructor }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button
                            v-if="currentLesson"
                            @click="toggleComplete(currentLesson.id)"
                            :disabled="toggling"
                            :class="[
                                currentLesson.completed
                                    ? 'bg-teal-50 dark:bg-teal-950/50 border-teal-200 dark:border-teal-800 text-teal-600 dark:text-teal-400 hover:bg-red-50 dark:hover:bg-red-950/50 hover:border-red-200 dark:hover:border-red-800 hover:text-red-500'
                                    : 'bg-indigo-50 dark:bg-indigo-950/50 border-indigo-200 dark:border-indigo-800 text-indigo-500 dark:text-indigo-400 hover:bg-indigo-500 hover:text-white hover:border-indigo-500',
                                'flex items-center gap-3 px-8 py-4 border-2 rounded-2xl text-xs font-bold uppercase tracking-widest transition-all duration-300 group disabled:opacity-50'
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
                <div class="bg-white dark:bg-stone-900 border border-indigo-100/20 dark:border-stone-700/50 rounded-[40px] flex flex-col overflow-hidden shadow-sm dark:shadow-stone-950/20 h-full ring-1 ring-indigo-500/[0.02] dark:ring-stone-700/30">
                    <div class="p-8 border-b border-stone-50 dark:border-stone-800 bg-stone-50/30 dark:bg-stone-800/30">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-heading font-extrabold tracking-tight text-stone-800 dark:text-stone-100">Course Content</h2>
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500">{{ course.lessons.length }} Items</span>
                        </div>
                        <div class="mt-6">
                            <div class="h-1.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-1000"
                                    :class="progress === 100 ? 'bg-teal-500' : 'bg-indigo-500'"
                                    :style="{ width: progress + '%' }"></div>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">Course Progress</p>
                                <p class="text-[10px] font-bold uppercase tracking-widest" :class="progress === 100 ? 'text-teal-500' : 'text-indigo-500 dark:text-indigo-400'">{{ progress }}%</p>
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
                                    ? 'bg-indigo-500 text-white shadow-2xl shadow-indigo-500/20 translate-x-2'
                                    : lesson.completed
                                        ? 'bg-teal-50/50 dark:bg-teal-950/30 text-teal-700 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-950/50'
                                        : 'hover:bg-indigo-50 dark:hover:bg-indigo-950/30 text-stone-500 dark:text-stone-400 hover:text-indigo-500 dark:hover:text-indigo-400',
                                'w-full flex items-start gap-4 p-5 rounded-[24px] text-left transition-all duration-500 group relative overflow-hidden'
                            ]"
                        >
                            <div :class="[
                                currentLesson?.id === lesson.id
                                    ? 'bg-white/20 text-white'
                                    : lesson.completed
                                        ? 'bg-teal-100 dark:bg-teal-900/50 text-teal-600 dark:text-teal-400'
                                        : 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-400 dark:text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white',
                                'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-xs font-bold transition-all duration-500'
                            ]">
                                <CheckCircle2 v-if="lesson.completed && currentLesson?.id !== lesson.id" class="w-5 h-5" />
                                <span v-else>{{ (index + 1).toString().padStart(2, '0') }}</span>
                            </div>
                            <div class="flex-1 min-w-0 pt-1">
                                <span class="text-sm font-semibold leading-tight block truncate group-hover:whitespace-normal transition-all">{{ lesson.title }}</span>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">
                                        {{ lesson.duration }}
                                    </span>
                                    <span v-if="lesson.completed && currentLesson?.id !== lesson.id" class="text-[9px] font-bold uppercase tracking-widest text-teal-500">Done</span>
                                    <div v-if="currentLesson?.id === lesson.id" class="flex gap-1">
                                        <div v-for="i in 3" :key="i" class="w-1 h-1 rounded-full bg-white animate-pulse" :style="{ animationDelay: `${i * 200}ms` }"></div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="p-6 border-t border-stone-50 dark:border-stone-800 flex items-center justify-between">
                         <button @click="prevLesson" class="p-4 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 rounded-2xl transition-colors text-stone-400 dark:text-stone-500 hover:text-indigo-500 dark:hover:text-indigo-400" :disabled="currentLessonIndex <= 0">
                             <ChevronLeft class="w-5 h-5" />
                         </button>
                         <button @click="toggleComplete(currentLesson?.id)" :disabled="toggling || !currentLesson"
                            class="flex items-center gap-2 px-6 py-3 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all"
                            :class="currentLesson?.completed ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400' : 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-500 dark:text-indigo-400 hover:bg-indigo-500 hover:text-white'">
                            <CheckCircle2 class="w-4 h-4" />
                            {{ currentLesson?.completed ? 'Done' : 'Complete' }}
                         </button>
                         <button @click="nextLesson" class="p-4 bg-indigo-500 text-white rounded-2xl hover:bg-indigo-600 transition-all" :disabled="currentLessonIndex >= course.lessons.length - 1">
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
:is(.dark) .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #44403C;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #6366F1;
}
</style>
