<template>
    <AdminLayout>
        <div class="space-y-10 pb-12 relative">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div>
                    <h1 class="text-5xl font-black tracking-tighter italic mb-4">Students.</h1>
                    <p class="text-neutral-500 text-lg font-medium">Manage all registered student accounts.</p>
                </div>
                <button @click="openCreate"
                    class="flex items-center gap-3 px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all duration-500 shrink-0">
                    <UserPlus class="w-4 h-4" />
                    Add Student
                </button>
            </div>

            <!-- Search -->
            <div class="flex items-center gap-4">
                <div class="flex-1 relative">
                    <Search class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-neutral-400" />
                    <input v-model="searchQuery" @input="onSearch" type="text" placeholder="Search students by name or email..."
                        class="w-full pl-12 pr-5 py-4 bg-white/60 backdrop-blur-md border border-white rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all" />
                </div>
                <div class="px-6 py-4 bg-red-50 border border-red-100 rounded-2xl shrink-0">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600">{{ students.length }} Students</span>
                </div>
            </div>

            <!-- Students Table -->
            <div v-if="students.length > 0" class="bg-white/60 backdrop-blur-md border border-white rounded-[32px] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral-100">
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Student</th>
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Email</th>
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Joined</th>
                                <th class="text-right px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students" :key="student.id" class="border-b border-neutral-50 hover:bg-red-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600 text-[10px] font-black shrink-0">
                                            {{ student.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2) }}
                                        </div>
                                        <span class="font-bold text-sm">{{ student.name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-neutral-500">{{ student.email }}</td>
                                <td class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-neutral-400">{{ student.created_at }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEdit(student)" class="p-2.5 hover:bg-neutral-100 rounded-xl transition-colors">
                                            <Pencil class="w-4 h-4 text-neutral-400" />
                                        </button>
                                        <button @click="deleteUser(student.id)" class="p-2.5 hover:bg-red-50 rounded-xl transition-colors">
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
                    <Users class="w-12 h-12 text-red-300" />
                </div>
                <h3 class="text-2xl font-black tracking-tight mb-3">No Students Found</h3>
                <p class="text-neutral-500 font-medium max-w-md mx-auto">{{ search ? 'No students match your search.' : 'No student accounts registered yet.' }}</p>
            </div>

            <Teleport to="body">
                <div v-if="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
                    <div class="relative w-full max-w-lg bg-white rounded-[32px] shadow-2xl overflow-y-auto max-h-[90vh]">
                        <div class="p-8 pb-0 flex items-center justify-between">
                            <h2 class="text-2xl font-black tracking-tight">{{ editing ? 'Edit Student' : 'Add Student' }}</h2>
                            <button @click="closeModal" class="p-2 hover:bg-neutral-100 rounded-xl transition-colors">
                                <X class="w-5 h-5 text-neutral-400" />
                            </button>
                        </div>
                        <form @submit.prevent="submitForm" class="p-8 space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Full Name *</label>
                                <input v-model="form.name" type="text" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="John Doe" />
                                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Email *</label>
                                <input v-model="form.email" type="email" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="student@example.com" />
                                <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">
                                    Password {{ editing ? '(leave blank to keep current)' : '*' }}
                                </label>
                                <input v-model="form.password" type="password" :required="!editing"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="Min. 8 characters" />
                                <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
                            </div>
                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" :disabled="form.processing"
                                    class="flex-1 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all disabled:opacity-50">
                                    {{ form.processing ? 'Saving...' : editing ? 'Save Changes' : 'Add Student' }}
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
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { UserPlus, Search, Pencil, Trash2, Users, X } from 'lucide-vue-next';

const props = defineProps({
    students: Array,
    search: String,
});

const showModal = ref(false);
const editing = ref(null);
const searchQuery = ref(props.search || '');
let searchTimeout = null;

const form = useForm({
    name: '',
    email: '',
    password: '',
});

const onSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/admin/students', { search: searchQuery.value }, { preserveState: true, preserveScroll: true });
    }, 300);
};

const openCreate = () => {
    editing.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEdit = (student) => {
    editing.value = student;
    form.name = student.name;
    form.email = student.email;
    form.password = '';
    form.clearErrors();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = null;
};

const submitForm = () => {
    if (editing.value) {
        form.put(`/admin/students/${editing.value.id}`, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/students', {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(`/admin/students/${id}`, { preserveScroll: true });
    }
};
</script>
