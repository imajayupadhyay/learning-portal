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
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Status</th>
                                <th class="text-left px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Joined</th>
                                <th class="text-right px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students" :key="student.id"
                                :class="[!student.is_approved ? 'bg-amber-50/40' : '']"
                                class="border-b border-neutral-50 hover:bg-red-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div :class="student.is_approved ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600'"
                                            class="w-10 h-10 rounded-xl flex items-center justify-center text-[10px] font-black shrink-0">
                                            {{ student.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2) }}
                                        </div>
                                        <span class="font-bold text-sm">{{ student.name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-neutral-500">{{ student.email }}</td>
                                <td class="px-8 py-5">
                                    <span v-if="student.is_approved"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                        <CheckCircle class="w-3 h-3" /> Approved
                                    </span>
                                    <span v-else
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                        <Clock class="w-3 h-3" /> Pending
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-neutral-400">{{ student.created_at }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <button v-if="!student.is_approved" @click="approveStudent(student.id)"
                                            class="flex items-center gap-1.5 px-3 py-2 bg-green-50 hover:bg-green-100 rounded-xl text-[9px] font-black uppercase tracking-widest text-green-700 transition-colors">
                                            <CheckCircle class="w-3.5 h-3.5" /> Approve
                                        </button>
                                        <button v-else @click="rejectStudent(student.id)"
                                            class="flex items-center gap-1.5 px-3 py-2 bg-neutral-50 hover:bg-amber-50 rounded-xl text-[9px] font-black uppercase tracking-widest text-neutral-400 hover:text-amber-700 transition-colors">
                                            <XCircle class="w-3.5 h-3.5" /> Revoke
                                        </button>
                                        <button @click="viewStudent(student.id)" class="p-2.5 hover:bg-blue-50 rounded-xl transition-colors" title="View Details">
                                            <Eye class="w-4 h-4 text-neutral-400 hover:text-blue-600" />
                                        </button>
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

            <!-- Student Detail Modal -->
            <Teleport to="body">
                <div v-if="showDetailModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="closeDetailModal"></div>
                    <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl overflow-y-auto max-h-[90vh]">
                        <!-- Loading -->
                        <div v-if="loadingDetail" class="p-16 text-center">
                            <div class="w-10 h-10 border-4 border-red-200 border-t-red-600 rounded-full animate-spin mx-auto mb-4"></div>
                            <p class="text-neutral-400 text-sm font-bold">Loading student details...</p>
                        </div>

                        <!-- Content -->
                        <div v-else-if="viewingStudent">
                            <!-- Header -->
                            <div class="p-8 pb-0 flex items-start justify-between">
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-red-50 flex items-center justify-center shrink-0">
                                        <img v-if="viewingStudent.detail?.avatar" :src="viewingStudent.detail.avatar" class="w-full h-full object-cover" />
                                        <span v-else class="text-red-600 text-lg font-black">
                                            {{ viewingStudent.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-black tracking-tight">{{ viewingStudent.name }}</h2>
                                        <p class="text-neutral-500 text-sm font-medium mt-1">{{ viewingStudent.email }}</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span v-if="viewingStudent.is_approved" class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                                <CheckCircle class="w-3 h-3" /> Approved
                                            </span>
                                            <span v-else class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                                <Clock class="w-3 h-3" /> Pending
                                            </span>
                                            <span class="text-[9px] font-bold text-neutral-400 tracking-wide">Joined {{ viewingStudent.created_at }}</span>
                                        </div>
                                    </div>
                                </div>
                                <button @click="closeDetailModal" class="p-2 hover:bg-neutral-100 rounded-xl transition-colors shrink-0">
                                    <X class="w-5 h-5 text-neutral-400" />
                                </button>
                            </div>

                            <!-- Stats -->
                            <div class="px-8 pt-6">
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="bg-red-50 rounded-2xl p-4 text-center">
                                        <p class="text-2xl font-black text-red-600">{{ viewingStudent.stats.enrolled_courses }}</p>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-neutral-400 mt-1">Courses</p>
                                    </div>
                                    <div class="bg-red-50 rounded-2xl p-4 text-center">
                                        <p class="text-2xl font-black text-red-600">{{ viewingStudent.stats.completed_lessons }}</p>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-neutral-400 mt-1">Lessons</p>
                                    </div>
                                    <div class="bg-red-50 rounded-2xl p-4 text-center">
                                        <p class="text-2xl font-black text-red-600">{{ viewingStudent.stats.total_todos }}</p>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-neutral-400 mt-1">Todos</p>
                                    </div>
                                    <div class="bg-red-50 rounded-2xl p-4 text-center">
                                        <p class="text-2xl font-black text-red-600">{{ viewingStudent.stats.completed_todos }}</p>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-neutral-400 mt-1">Done</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="p-8 space-y-6">
                                <!-- Bio -->
                                <div v-if="viewingStudent.detail?.bio">
                                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em] mb-2">Bio</p>
                                    <p class="text-sm text-neutral-600 leading-relaxed bg-neutral-50 rounded-2xl p-4">{{ viewingStudent.detail.bio }}</p>
                                </div>

                                <!-- Personal Info -->
                                <div>
                                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em] mb-3">Personal Information</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div v-if="viewingStudent.detail?.phone" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <Phone class="w-4 h-4 text-red-400 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600">{{ viewingStudent.detail.phone }}</span>
                                        </div>
                                        <div v-if="viewingStudent.detail?.date_of_birth" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <Clock class="w-4 h-4 text-red-400 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600">{{ viewingStudent.detail.date_of_birth }}</span>
                                        </div>
                                        <div v-if="viewingStudent.detail?.gender" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <Users class="w-4 h-4 text-red-400 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600 capitalize">{{ viewingStudent.detail.gender }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <Mail class="w-4 h-4 text-red-400 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600 truncate">{{ viewingStudent.email }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Education -->
                                <div v-if="viewingStudent.detail?.education || viewingStudent.detail?.institution">
                                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em] mb-3">Education</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div v-if="viewingStudent.detail?.education" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <GraduationCap class="w-4 h-4 text-red-400 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600">{{ viewingStudent.detail.education }}</span>
                                        </div>
                                        <div v-if="viewingStudent.detail?.institution" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <BookOpen class="w-4 h-4 text-red-400 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600">{{ viewingStudent.detail.institution }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Interests -->
                                <div v-if="viewingStudent.detail?.interests">
                                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em] mb-3">Interests & Skills</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="interest in viewingStudent.detail.interests.split(',')" :key="interest"
                                            class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold">
                                            {{ interest.trim() }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div v-if="viewingStudent.detail?.address || viewingStudent.detail?.city">
                                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em] mb-3">Address</p>
                                    <div class="flex items-start gap-3 bg-neutral-50 rounded-xl p-3">
                                        <MapPin class="w-4 h-4 text-red-400 shrink-0 mt-0.5" />
                                        <span class="text-sm font-medium text-neutral-600">
                                            {{ [viewingStudent.detail.address, viewingStudent.detail.city, viewingStudent.detail.state, viewingStudent.detail.country, viewingStudent.detail.pincode].filter(Boolean).join(', ') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Social Links -->
                                <div v-if="viewingStudent.detail?.linkedin || viewingStudent.detail?.github">
                                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em] mb-3">Social Links</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div v-if="viewingStudent.detail?.linkedin" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <Linkedin class="w-4 h-4 text-blue-600 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600 truncate">{{ viewingStudent.detail.linkedin }}</span>
                                        </div>
                                        <div v-if="viewingStudent.detail?.github" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <Github class="w-4 h-4 text-neutral-800 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600 truncate">{{ viewingStudent.detail.github }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enrolled Courses -->
                                <div v-if="viewingStudent.courses.length > 0">
                                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em] mb-3">Enrolled Courses</p>
                                    <div class="space-y-2">
                                        <div v-for="course in viewingStudent.courses" :key="course.id" class="flex items-center gap-3 bg-neutral-50 rounded-xl p-3">
                                            <ListChecks class="w-4 h-4 text-red-400 shrink-0" />
                                            <span class="text-sm font-medium text-neutral-600">{{ course.title }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- No details message -->
                                <div v-if="!viewingStudent.detail" class="text-center py-6">
                                    <p class="text-neutral-400 text-sm font-bold">This student hasn't filled in their profile details yet.</p>
                                </div>
                            </div>
                        </div>
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
import { UserPlus, Search, Pencil, Trash2, Users, X, CheckCircle, XCircle, Clock, Eye, Phone, Mail, MapPin, GraduationCap, BookOpen, ListChecks, Github, Linkedin } from 'lucide-vue-next';

const props = defineProps({
    students: Array,
    search: String,
});

const showModal = ref(false);
const showDetailModal = ref(false);
const viewingStudent = ref(null);
const loadingDetail = ref(false);
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

const viewStudent = async (id) => {
    loadingDetail.value = true;
    showDetailModal.value = true;
    try {
        const response = await fetch(`/admin/students/${id}`);
        viewingStudent.value = await response.json();
    } catch (e) {
        viewingStudent.value = null;
    }
    loadingDetail.value = false;
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    viewingStudent.value = null;
};

const approveStudent = (id) => {
    router.patch(`/admin/students/${id}/approve`, {}, { preserveScroll: true });
};

const rejectStudent = (id) => {
    if (confirm('Revoke this student\'s access? They will not be able to log in.')) {
        router.patch(`/admin/students/${id}/reject`, {}, { preserveScroll: true });
    }
};

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(`/admin/students/${id}`, { preserveScroll: true });
    }
};
</script>
