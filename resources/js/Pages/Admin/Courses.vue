<template>
    <Head title="Courses">
        <meta name="description" content="Manage all courses — add, edit, and organize learning content." />
    </Head>
    <AdminLayout>
        <div class="space-y-10 pb-12 relative">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div>
                    <h1 class="text-5xl font-black tracking-tighter italic mb-4">Courses.</h1>
                    <p class="text-neutral-500 text-lg font-medium">Manage courses and their content.</p>
                </div>
                <button @click="openCreate"
                    class="flex items-center gap-3 px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all duration-500 shrink-0">
                    <Plus class="w-4 h-4" />
                    Add Course
                </button>
            </div>

            <!-- Search -->
            <div class="flex items-center gap-4">
                <div class="flex-1 relative">
                    <Search class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-neutral-400" />
                    <input v-model="searchQuery" @input="onSearch" type="text" placeholder="Search courses by title or instructor..."
                        class="w-full pl-12 pr-5 py-4 bg-white/60 backdrop-blur-md border border-white rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all" />
                </div>
                <div class="px-6 py-4 bg-red-50 border border-red-100 rounded-2xl shrink-0">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600">{{ courses.length }} Courses</span>
                </div>
            </div>

            <!-- Courses Grid -->
            <div v-if="courses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div v-for="course in courses" :key="course.id"
                    class="group bg-white/60 backdrop-blur-md border border-white rounded-[24px] overflow-hidden hover:shadow-xl hover:shadow-black/5 transition-all duration-500">

                    <!-- Image -->
                    <div class="aspect-[16/10] overflow-hidden relative bg-neutral-100">
                        <img v-if="course.image" :src="course.image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <BookOpen class="w-10 h-10 text-neutral-300" />
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 bg-white/90 backdrop-blur-md rounded-lg text-[9px] font-black uppercase tracking-widest text-black shadow-sm">
                                {{ course.lessons_count }} Lessons
                            </span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-5">
                        <h3 class="text-base font-black tracking-tight leading-tight mb-1.5">{{ course.title }}</h3>
                        <p class="text-neutral-500 text-xs font-medium line-clamp-2 mb-3">{{ course.description || 'No description provided.' }}</p>

                        <div class="flex items-center gap-2 flex-wrap mb-4">
                            <span class="px-2.5 py-0.5 bg-red-50 text-red-600 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ course.instructor }}</span>
                            <span v-if="course.category_name" class="px-2.5 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ course.category_name }}</span>
                            <span class="text-[9px] font-bold text-neutral-400 tracking-wide">{{ course.created_at }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-1.5 pt-4 border-t border-neutral-100">
                            <Link :href="`/admin/courses/${course.id}/lessons`"
                                class="flex-1 flex items-center justify-center gap-1.5 py-2.5 bg-red-50 hover:bg-red-100 rounded-xl text-[9px] font-black uppercase tracking-widest text-red-600 transition-all">
                                <ListVideo class="w-3 h-3" /> Lessons
                            </Link>
                            <button @click="openEdit(course)"
                                class="flex-1 flex items-center justify-center gap-1.5 py-2.5 bg-neutral-50 hover:bg-neutral-100 rounded-xl text-[9px] font-black uppercase tracking-widest text-neutral-500 transition-all">
                                <Pencil class="w-3 h-3" /> Edit
                            </button>
                            <button @click="deleteCourse(course.id)"
                                class="flex items-center justify-center gap-1.5 py-2.5 px-3 bg-neutral-50 hover:bg-red-50 rounded-xl text-[9px] font-black uppercase tracking-widest text-neutral-400 hover:text-red-600 transition-all">
                                <Trash2 class="w-3 h-3" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="w-24 h-24 bg-red-50 rounded-[28px] flex items-center justify-center mx-auto mb-8 border border-red-100">
                    <BookOpen class="w-12 h-12 text-red-300" />
                </div>
                <h3 class="text-2xl font-black tracking-tight mb-3">No Courses Found</h3>
                <p class="text-neutral-500 font-medium max-w-md mx-auto">{{ search ? 'No courses match your search.' : 'No courses created yet. Add your first course.' }}</p>
            </div>

            <Teleport to="body">
                <div v-if="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
                    <div class="relative w-full max-w-lg bg-white rounded-[32px] shadow-2xl overflow-y-auto max-h-[90vh]">
                        <div class="p-8 pb-0 flex items-center justify-between">
                            <h2 class="text-2xl font-black tracking-tight">{{ editing ? 'Edit Course' : 'Add Course' }}</h2>
                            <button @click="closeModal" class="p-2 hover:bg-neutral-100 rounded-xl transition-colors">
                                <X class="w-5 h-5 text-neutral-400" />
                            </button>
                        </div>
                        <form @submit.prevent="submitForm" class="p-8 space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Course Title *</label>
                                <input v-model="form.title" type="text" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="e.g. Docker Mastery" />
                                <p v-if="formErrors.title" class="text-sm text-red-500">{{ formErrors.title }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Description</label>
                                <textarea v-model="form.description" rows="3"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all resize-none"
                                    placeholder="Brief course description..."></textarea>
                                <p v-if="formErrors.description" class="text-sm text-red-500">{{ formErrors.description }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Instructor *</label>
                                <input v-model="form.instructor" type="text" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="Instructor name" />
                                <p v-if="formErrors.instructor" class="text-sm text-red-500">{{ formErrors.instructor }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Category</label>
                                <select v-model="form.category_id"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all">
                                    <option :value="null">No Category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Course Image</label>
                                <!-- Toggle -->
                                <div class="flex bg-neutral-100 rounded-xl p-1 gap-1">
                                    <button type="button" @click="imageMode = 'upload'"
                                        :class="imageMode === 'upload' ? 'bg-white shadow-sm text-red-600' : 'text-neutral-400 hover:text-neutral-600'"
                                        class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                        <Upload class="w-3.5 h-3.5" /> Upload
                                    </button>
                                    <button type="button" @click="imageMode = 'url'"
                                        :class="imageMode === 'url' ? 'bg-white shadow-sm text-red-600' : 'text-neutral-400 hover:text-neutral-600'"
                                        class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                        <LinkIcon class="w-3.5 h-3.5" /> URL
                                    </button>
                                </div>
                                <!-- Upload -->
                                <div v-if="imageMode === 'upload'">
                                    <div v-if="imagePreview && !form.image_file && editing" class="relative mb-3">
                                        <img :src="imagePreview" class="w-full h-32 object-cover rounded-2xl" />
                                        <span class="absolute top-2 left-2 px-2 py-1 bg-white/90 rounded-lg text-[9px] font-black uppercase tracking-widest text-neutral-500">Current Image</span>
                                    </div>
                                    <label class="block w-full cursor-pointer">
                                        <div v-if="form.image_file" class="relative">
                                            <img :src="imagePreview" class="w-full h-32 object-cover rounded-2xl" />
                                            <button type="button" @click.prevent="removeImage" class="absolute top-2 right-2 p-1.5 bg-white rounded-lg shadow-md hover:bg-red-50 transition-colors">
                                                <X class="w-3.5 h-3.5 text-red-600" />
                                            </button>
                                        </div>
                                        <div v-else class="border-2 border-dashed border-neutral-200 hover:border-red-300 rounded-2xl py-8 flex flex-col items-center gap-3 transition-colors">
                                            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                                                <Upload class="w-5 h-5 text-red-400" />
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs font-bold text-neutral-500">Click to upload image</p>
                                                <p class="text-[10px] text-neutral-400 mt-1">JPG, PNG, WebP (max 2MB)</p>
                                            </div>
                                        </div>
                                        <input type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="onFileChange" />
                                    </label>
                                    <p v-if="formErrors.image_file" class="text-sm text-red-500 mt-1">{{ formErrors.image_file }}</p>
                                </div>
                                <!-- URL -->
                                <div v-else>
                                    <input v-model="form.image_url" type="text"
                                        class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                        placeholder="https://example.com/image.jpg" />
                                    <p v-if="formErrors.image_url" class="text-sm text-red-500 mt-1">{{ formErrors.image_url }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" :disabled="processing"
                                    class="flex-1 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all disabled:opacity-50">
                                    {{ processing ? 'Saving...' : editing ? 'Save Changes' : 'Add Course' }}
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
import { Plus, Search, BookOpen, ListVideo, Pencil, Trash2, X, Upload, LinkIcon } from 'lucide-vue-next';

const props = defineProps({
    courses: Array,
    search: String,
    categories: Array,
});

const showModal = ref(false);
const editing = ref(null);
const searchQuery = ref(props.search || '');
const imageMode = ref('upload'); // 'upload' or 'url'
const imagePreview = ref(null);
const formErrors = ref({});
const processing = ref(false);
let searchTimeout = null;

const form = useForm({
    title: '',
    description: '',
    instructor: '',
    category_id: null,
    image_file: null,
    image_url: '',
});

const onSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/admin/courses', { search: searchQuery.value }, { preserveState: true, preserveScroll: true });
    }, 300);
};

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image_file = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    form.image_file = null;
    imagePreview.value = null;
};

const openCreate = () => {
    editing.value = null;
    form.reset();
    form.clearErrors();
    formErrors.value = {};
    imagePreview.value = null;
    imageMode.value = 'upload';
    showModal.value = true;
};

const openEdit = (course) => {
    editing.value = course;
    form.title = course.title;
    form.description = course.description || '';
    form.instructor = course.instructor;
    form.category_id = course.category_id || null;
    form.image_file = null;
    form.image_url = course.image || '';
    form.clearErrors();
    formErrors.value = {};
    imagePreview.value = course.image || null;
    imageMode.value = course.image && !course.image.startsWith('/storage/') ? 'url' : 'upload';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = null;
    imagePreview.value = null;
};

const submitForm = () => {
    processing.value = true;
    formErrors.value = {};

    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('description', form.description || '');
    formData.append('instructor', form.instructor);
    if (form.category_id) formData.append('category_id', form.category_id);

    if (imageMode.value === 'upload' && form.image_file) {
        formData.append('image_file', form.image_file);
    } else if (imageMode.value === 'url' && form.image_url) {
        formData.append('image_url', form.image_url);
    }

    if (editing.value) {
        formData.append('_method', 'PUT');
        router.post(`/admin/courses/${editing.value.id}`, formData, {
            preserveScroll: true,
            onSuccess: () => { closeModal(); processing.value = false; },
            onError: (errors) => { formErrors.value = errors; processing.value = false; },
        });
    } else {
        router.post('/admin/courses', formData, {
            preserveScroll: true,
            onSuccess: () => { closeModal(); processing.value = false; },
            onError: (errors) => { formErrors.value = errors; processing.value = false; },
        });
    }
};

const deleteCourse = (id) => {
    if (confirm('Are you sure? This will also delete all lessons in this course.')) {
        router.delete(`/admin/courses/${id}`, { preserveScroll: true });
    }
};
</script>
