<template>
    <StudentLayout>
        <div class="space-y-10 pb-12 relative">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-3 flex-wrap">
                <button v-for="f in filters" :key="f.key"
                    @click="changeFilter(f.key)"
                    :class="[
                        currentFilter === f.key
                            ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20'
                            : 'bg-white/60 text-stone-500 hover:text-indigo-500 hover:bg-indigo-50/50 border border-indigo-100/20',
                        'px-6 py-3 rounded-2xl text-[10px] font-bold uppercase tracking-[0.2em] transition-all duration-300 flex items-center gap-2'
                    ]"
                >
                    {{ f.label }}
                    <span :class="[
                        currentFilter === f.key ? 'bg-white/20 text-white' : 'bg-stone-100 text-stone-500',
                        'px-2 py-0.5 rounded-lg text-[9px] font-bold'
                    ]">{{ counts[f.key] }}</span>
                </button>
            </div>

            <!-- Todo List -->
            <div v-if="todos.length > 0" class="space-y-4">
                <div v-for="todo in todos" :key="todo.id"
                    :class="[
                        'group bg-white/60 backdrop-blur-md border rounded-[24px] p-6 md:p-8 transition-all duration-500 hover:shadow-lg relative overflow-hidden',
                        todo.is_completed ? 'border-stone-100 opacity-70' : todo.is_overdue ? 'border-red-200 hover:shadow-red-100/50' : 'border-indigo-100/20 hover:shadow-indigo-500/5'
                    ]"
                >
                    <!-- Priority left accent -->
                    <div :class="[
                        'absolute left-0 top-0 bottom-0 w-1 rounded-l-[24px]',
                        todo.is_completed ? 'bg-stone-200' :
                        todo.priority === 'high' ? 'bg-red-500' :
                        todo.priority === 'medium' ? 'bg-amber-500' : 'bg-teal-500'
                    ]"></div>

                    <div class="flex items-start gap-5 ml-3">
                        <!-- Checkbox -->
                        <button @click="toggleTodo(todo.id)"
                            :class="[
                                'w-7 h-7 rounded-full border-2 flex items-center justify-center flex-shrink-0 mt-1 transition-all duration-300',
                                todo.is_completed
                                    ? 'bg-indigo-500 border-indigo-500'
                                    : 'border-stone-300 hover:border-indigo-500 hover:bg-indigo-50'
                            ]"
                            :title="todo.is_completed ? 'Mark incomplete' : 'Mark complete'"
                        >
                            <Check v-if="todo.is_completed" class="w-4 h-4 text-white" />
                        </button>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h3 :class="[
                                        'text-lg font-semibold tracking-tight leading-tight',
                                        todo.is_completed ? 'line-through text-stone-400' : 'text-stone-800'
                                    ]">{{ todo.title }}</h3>
                                    <p v-if="todo.description" class="text-stone-400 text-sm mt-1.5 line-clamp-2">{{ todo.description }}</p>
                                </div>
                                <!-- Actions -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <button @click="toggleTodo(todo.id)"
                                        :class="[
                                            'flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all duration-300',
                                            todo.is_completed
                                                ? 'bg-stone-100 text-stone-400 hover:bg-amber-50 hover:text-amber-600'
                                                : 'bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white'
                                        ]"
                                    >
                                        <CheckCircle2 class="w-3.5 h-3.5" />
                                        {{ todo.is_completed ? 'Undo' : 'Done' }}
                                    </button>
                                    <button @click="openEdit(todo)" class="p-2.5 hover:bg-stone-100 rounded-xl transition-colors">
                                        <Pencil class="w-4 h-4 text-stone-400" />
                                    </button>
                                    <button @click="deleteTodo(todo.id)" class="p-2.5 hover:bg-red-50 rounded-xl transition-colors">
                                        <Trash2 class="w-4 h-4 text-stone-400 hover:text-red-500" />
                                    </button>
                                </div>
                            </div>

                            <!-- Meta Row -->
                            <div class="flex items-center gap-3 mt-4 flex-wrap">
                                <!-- Due Date -->
                                <span :class="[
                                    'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest',
                                    todo.is_completed ? 'bg-stone-50 text-stone-400' :
                                    todo.is_overdue ? 'bg-red-50 text-red-500' :
                                    todo.due_date_display === 'Today' ? 'bg-amber-50 text-amber-600' :
                                    'bg-stone-50 text-stone-500'
                                ]">
                                    <AlertCircle v-if="todo.is_overdue && !todo.is_completed" class="w-3 h-3" />
                                    <CalendarDays v-else class="w-3 h-3" />
                                    {{ todo.due_date_display }}
                                </span>

                                <!-- Priority -->
                                <span :class="[
                                    'px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest',
                                    todo.priority === 'high' ? 'bg-red-50 text-red-500' :
                                    todo.priority === 'medium' ? 'bg-amber-50 text-amber-600' :
                                    'bg-teal-50 text-teal-600'
                                ]">{{ todo.priority }}</span>

                                <!-- Course Link -->
                                <span v-if="todo.course" class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-500 rounded-lg text-[10px] font-bold uppercase tracking-widest">
                                    <BookOpen class="w-3 h-3" />
                                    {{ todo.course.title }}
                                </span>

                                <!-- Lesson Link -->
                                <span v-if="todo.lesson" class="flex items-center gap-1.5 px-3 py-1.5 bg-stone-50 text-stone-500 rounded-lg text-[10px] font-bold tracking-wide">
                                    <PlayCircle class="w-3 h-3" />
                                    {{ todo.lesson.title }}
                                </span>

                                <!-- Completed at -->
                                <span v-if="todo.is_completed && todo.completed_at" class="text-[10px] font-bold text-stone-400 tracking-wide">
                                    Done {{ todo.completed_at }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="w-24 h-24 bg-indigo-50 rounded-[28px] flex items-center justify-center mx-auto mb-8 border border-indigo-100/30">
                    <ListTodo class="w-12 h-12 text-indigo-300" />
                </div>
                <h3 class="text-2xl font-heading font-extrabold tracking-tight mb-3 text-stone-800">
                    {{ currentFilter === 'all' ? 'No Todos Yet' : 'Nothing Here' }}
                </h3>
                <p class="text-stone-500 font-medium max-w-md mx-auto mb-8">
                    {{ currentFilter === 'all'
                        ? 'Create your first todo to start planning your learning journey.'
                        : 'No todos match this filter. Try a different one or create a new todo.' }}
                </p>
                <button v-if="currentFilter === 'all'" @click="openCreate"
                    class="inline-flex items-center gap-3 px-8 py-4 bg-indigo-500 text-white rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 transition-all">
                    <Plus class="w-4 h-4" />
                    Create Todo
                </button>
            </div>

            <!-- Modal Overlay - scoped to content area -->
            <div v-if="showModal" class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 60; left: 312px;">
                <!-- Backdrop (only covers content area) -->
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm rounded-l-[24px]" @click="closeModal"></div>

                <!-- Modal Content -->
                <div class="relative bg-white rounded-[32px] w-full max-w-lg shadow-2xl shadow-black/20 max-h-[90vh] overflow-y-auto">
                    <!-- Header -->
                    <div class="p-8 pb-0 flex items-center justify-between">
                        <h2 class="text-2xl font-heading font-extrabold tracking-tight text-stone-800">{{ editingTodo ? 'Edit Todo' : 'New Todo' }}</h2>
                        <button @click="closeModal" class="p-2 hover:bg-stone-100 rounded-xl transition-colors">
                            <X class="w-5 h-5 text-stone-400" />
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitForm" class="p-8 space-y-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">Title *</label>
                            <input v-model="form.title" type="text" required
                                class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl text-stone-800 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="What do you need to do?" />
                            <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">Description</label>
                            <textarea v-model="form.description" rows="3"
                                class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl text-stone-800 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all resize-none"
                                placeholder="Add some details (optional)"></textarea>
                        </div>

                        <!-- Date & Priority Row -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">Due Date *</label>
                                <input v-model="form.due_date" type="date" required
                                    class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl text-stone-800 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" />
                                <p v-if="form.errors.due_date" class="text-sm text-red-500">{{ form.errors.due_date }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">Priority</label>
                                <div class="flex gap-2 h-[56px]">
                                    <button v-for="p in ['low', 'medium', 'high']" :key="p" type="button"
                                        @click="form.priority = p"
                                        :class="[
                                            'flex-1 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all duration-300',
                                            form.priority === p
                                                ? p === 'high' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20'
                                                    : p === 'medium' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20'
                                                    : 'bg-teal-500 text-white shadow-lg shadow-teal-500/20'
                                                : 'bg-stone-50 border border-stone-200 text-stone-400 hover:border-stone-300'
                                        ]"
                                    >{{ p }}</button>
                                </div>
                            </div>
                        </div>

                        <!-- Course Select -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">Link to Course</label>
                            <select v-model="form.course_id" @change="onCourseChange"
                                class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl text-stone-800 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none">
                                <option :value="null">No course (standalone todo)</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">{{ course.title }}</option>
                            </select>
                        </div>

                        <!-- Lesson Select (conditional) -->
                        <div v-if="form.course_id && selectedCourseLessons.length > 0" class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">Link to Lesson</label>
                            <select v-model="form.lesson_id"
                                class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl text-stone-800 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none">
                                <option :value="null">All lessons / General</option>
                                <option v-for="lesson in selectedCourseLessons" :key="lesson.id" :value="lesson.id">{{ lesson.title }}</option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" :disabled="form.processing"
                                class="flex-1 py-4 bg-indigo-500 text-white rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-500 disabled:opacity-50">
                                {{ form.processing ? 'Saving...' : editingTodo ? 'Save Changes' : 'Create Todo' }}
                            </button>
                            <button type="button" @click="closeModal"
                                class="px-8 py-4 bg-stone-100 text-stone-500 rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-stone-200 transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';
import {
    Plus, Check, X, Pencil, Trash2,
    CalendarDays, AlertCircle, BookOpen,
    PlayCircle, ListTodo, CheckCircle2
} from 'lucide-vue-next';

const props = defineProps({
    todos: Array,
    courses: Array,
    currentFilter: String,
    counts: Object,
});

const filters = [
    { key: 'all', label: 'All' },
    { key: 'today', label: 'Today' },
    { key: 'upcoming', label: 'Upcoming' },
    { key: 'overdue', label: 'Overdue' },
    { key: 'completed', label: 'Completed' },
];

const showModal = ref(false);
const editingTodo = ref(null);

const form = useForm({
    title: '',
    description: '',
    due_date: '',
    priority: 'medium',
    course_id: null,
    lesson_id: null,
});

const selectedCourseLessons = computed(() => {
    if (!form.course_id) return [];
    const course = props.courses.find(c => c.id === form.course_id);
    return course ? course.lessons : [];
});

const changeFilter = (filter) => {
    router.get('/student/todos', { filter }, { preserveState: true, preserveScroll: true });
};

const openCreate = () => {
    editingTodo.value = null;
    form.reset();
    form.clearErrors();
    form.due_date = new Date().toISOString().split('T')[0];
    showModal.value = true;
};

const openEdit = (todo) => {
    editingTodo.value = todo;
    form.title = todo.title;
    form.description = todo.description || '';
    form.due_date = todo.due_date;
    form.priority = todo.priority;
    form.course_id = todo.course?.id || null;
    form.lesson_id = todo.lesson?.id || null;
    form.clearErrors();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingTodo.value = null;
};

const onCourseChange = () => {
    form.lesson_id = null;
};

const submitForm = () => {
    if (editingTodo.value) {
        form.put(`/student/todos/${editingTodo.value.id}`, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/student/todos', {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const toggleTodo = (id) => {
    router.patch(`/student/todos/${id}/toggle`, {}, { preserveScroll: true });
};

const deleteTodo = (id) => {
    router.delete(`/student/todos/${id}`, { preserveScroll: true });
};
</script>
