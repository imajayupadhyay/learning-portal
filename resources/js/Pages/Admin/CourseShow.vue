<template>
    <Head :title="course.title">
        <meta name="description" :content="`Manage lessons and content for ${course.title}.`" />
    </Head>
    <AdminLayout>
        <div class="space-y-10 pb-12 relative">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div>
                    <Link href="/admin/courses" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-neutral-400 hover:text-red-600 transition-colors mb-6">
                        <ArrowLeft class="w-4 h-4" /> Back to Courses
                    </Link>
                    <h1 class="text-5xl font-black tracking-tighter italic mb-4">{{ course.title }}</h1>
                    <p class="text-neutral-500 text-lg font-medium">Manage lessons and content for this course.</p>
                    <div class="flex items-center gap-3 mt-4">
                        <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ course.instructor }}</span>
                    </div>
                </div>
                <button @click="openCreate"
                    class="flex items-center gap-3 px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all duration-500 shrink-0">
                    <Plus class="w-4 h-4" />
                    Add Lesson
                </button>
            </div>

            <!-- Count Badge -->
            <div class="flex items-center gap-4">
                <div class="px-6 py-4 bg-red-50 border border-red-100 rounded-2xl">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600">{{ lessons.length }} Lessons</span>
                </div>
            </div>

            <!-- Lessons Table -->
            <div v-if="lessons.length > 0" class="bg-white/60 backdrop-blur-md border border-white rounded-[32px] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral-100">
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400 w-16">#</th>
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Lesson Title</th>
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Duration</th>
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Video ID</th>
                                <th class="text-right px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lesson in lessons" :key="lesson.id" class="border-b border-neutral-50 hover:bg-red-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center text-red-600 text-[10px] font-black">
                                        {{ lesson.sort_order }}
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="font-bold text-sm">{{ lesson.title }}</span>
                                </td>
                                <td class="px-8 py-5 text-sm text-neutral-500">{{ lesson.duration || '—' }}</td>
                                <td class="px-8 py-5">
                                    <span v-if="lesson.video_id" class="text-[10px] font-bold text-neutral-400 tracking-wide bg-neutral-50 px-3 py-1.5 rounded-lg">
                                        {{ lesson.video_id.substring(0, 20) }}{{ lesson.video_id.length > 20 ? '...' : '' }}
                                    </span>
                                    <span v-else class="text-sm text-neutral-300">No video</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEdit(lesson)" class="p-2.5 hover:bg-neutral-100 rounded-xl transition-colors">
                                            <Pencil class="w-4 h-4 text-neutral-400" />
                                        </button>
                                        <button @click="deleteLesson(lesson.id)" class="p-2.5 hover:bg-red-50 rounded-xl transition-colors">
                                            <Trash2 class="w-4 h-4 text-neutral-400 hover:text-red-600" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="w-24 h-24 bg-red-50 rounded-[28px] flex items-center justify-center mx-auto mb-8 border border-red-100">
                    <ListVideo class="w-12 h-12 text-red-300" />
                </div>
                <h3 class="text-2xl font-black tracking-tight mb-3">No Lessons Yet</h3>
                <p class="text-neutral-500 font-medium max-w-md mx-auto">Add your first lesson to this course.</p>
            </div>

            <Teleport to="body">
                <div v-if="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
                    <div class="relative w-full max-w-lg bg-white rounded-[32px] shadow-2xl overflow-y-auto max-h-[90vh]">
                        <div class="p-8 pb-0 flex items-center justify-between">
                            <h2 class="text-2xl font-black tracking-tight">{{ editing ? 'Edit Lesson' : 'Add Lesson' }}</h2>
                            <button @click="closeModal" class="p-2 hover:bg-neutral-100 rounded-xl transition-colors">
                                <X class="w-5 h-5 text-neutral-400" />
                            </button>
                        </div>
                        <form @submit.prevent="submitForm" class="p-8 space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Lesson Title *</label>
                                <input v-model="form.title" type="text" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="e.g. Introduction to Containers" />
                                <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Duration</label>
                                <input v-model="form.duration" type="text"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="e.g. 12:30" />
                                <p v-if="form.errors.duration" class="text-sm text-red-500">{{ form.errors.duration }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Google Drive Video ID</label>
                                <input v-model="form.video_id" type="text"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="e.g. 1aBcDeFgHiJkLmNoPqR" />
                                <p v-if="form.errors.video_id" class="text-sm text-red-500">{{ form.errors.video_id }}</p>
                            </div>
                            <div v-if="editing" class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Sort Order</label>
                                <input v-model.number="form.sort_order" type="number" min="0"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="0" />
                                <p v-if="form.errors.sort_order" class="text-sm text-red-500">{{ form.errors.sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" :disabled="form.processing"
                                    class="flex-1 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all disabled:opacity-50">
                                    {{ form.processing ? 'Saving...' : editing ? 'Save Changes' : 'Add Lesson' }}
                                </button>
                                <button type="button" @click="closeModal"
                                    class="px-8 py-4 bg-neutral-100 text-neutral-500 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-neutral-200 transition-all">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, ArrowLeft, ListVideo, Pencil, Trash2, X } from 'lucide-vue-next';

const props = defineProps({
    course: Object,
    lessons: Array,
});

const showModal = ref(false);
const editing = ref(null);

const form = useForm({
    title: '',
    duration: '',
    video_id: '',
    sort_order: 0,
});

const openCreate = () => {
    editing.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEdit = (lesson) => {
    editing.value = lesson;
    form.title = lesson.title;
    form.duration = lesson.duration || '';
    form.video_id = lesson.video_id || '';
    form.sort_order = lesson.sort_order;
    form.clearErrors();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = null;
};

const submitForm = () => {
    if (editing.value) {
        form.put(`/admin/courses/${props.course.id}/lessons/${editing.value.id}`, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(`/admin/courses/${props.course.id}/lessons`, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const deleteLesson = (id) => {
    if (confirm('Are you sure you want to delete this lesson?')) {
        router.delete(`/admin/courses/${props.course.id}/lessons/${id}`, { preserveScroll: true });
    }
};
</script>
