<template>
    <Head title="Admins">
        <meta name="description" content="Manage admin users and their access." />
    </Head>
    <AdminLayout>
        <div class="space-y-10 pb-12 relative">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div>
                    <h1 class="text-5xl font-black tracking-tighter italic mb-4">Admins.</h1>
                    <p class="text-neutral-500 text-lg font-medium">Manage administrator accounts and access.</p>
                </div>
                <button @click="openCreate"
                    class="flex items-center gap-3 px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all duration-500 shrink-0">
                    <ShieldPlus class="w-4 h-4" />
                    Add Admin
                </button>
            </div>

            <!-- Count Badge -->
            <div class="flex items-center gap-4">
                <div class="px-6 py-4 bg-red-50 border border-red-100 rounded-2xl">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600">{{ admins.length }} Administrators</span>
                </div>
            </div>

            <!-- Admins Grid -->
            <div v-if="admins.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="admin in admins" :key="admin.id"
                    class="group bg-white/60 backdrop-blur-md border border-white rounded-[28px] p-8 hover:shadow-xl hover:shadow-black/5 transition-all duration-500 relative overflow-hidden">

                    <!-- Current Admin Badge -->
                    <div v-if="admin.id === currentAdminId" class="absolute top-6 right-6">
                        <span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[9px] font-black uppercase tracking-widest">You</span>
                    </div>

                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-600 to-red-700 rounded-2xl flex items-center justify-center text-white text-sm font-black shadow-lg shadow-red-600/20 shrink-0">
                            {{ admin.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-xl font-black tracking-tight leading-tight">{{ admin.name }}</h3>
                            <p class="text-neutral-500 text-sm mt-1">{{ admin.email }}</p>
                            <div class="flex items-center gap-3 mt-4">
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[9px] font-black uppercase tracking-widest">Administrator</span>
                                <span class="text-[10px] font-bold text-neutral-400 tracking-wide">Joined {{ admin.created_at }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 mt-6 pt-6 border-t border-neutral-100">
                        <button @click="openEdit(admin)"
                            class="flex-1 flex items-center justify-center gap-2 py-3 bg-neutral-50 hover:bg-neutral-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-neutral-500 transition-all">
                            <Pencil class="w-3.5 h-3.5" /> Edit
                        </button>
                        <button v-if="admin.id !== currentAdminId" @click="deleteAdmin(admin.id)"
                            class="flex-1 flex items-center justify-center gap-2 py-3 bg-red-50 hover:bg-red-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-red-600 transition-all">
                            <Trash2 class="w-3.5 h-3.5" /> Remove
                        </button>
                        <div v-else
                            class="flex-1 flex items-center justify-center gap-2 py-3 bg-neutral-50 rounded-xl text-[10px] font-black uppercase tracking-widest text-neutral-300 cursor-not-allowed">
                            <Trash2 class="w-3.5 h-3.5" /> Can't Remove
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="w-24 h-24 bg-red-50 rounded-[28px] flex items-center justify-center mx-auto mb-8 border border-red-100">
                    <ShieldCheck class="w-12 h-12 text-red-300" />
                </div>
                <h3 class="text-2xl font-black tracking-tight mb-3">No Admins</h3>
                <p class="text-neutral-500 font-medium">Something went wrong - there should be at least one admin.</p>
            </div>

            <Teleport to="body">
                <div v-if="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
                    <div class="relative w-full max-w-lg bg-white rounded-[32px] shadow-2xl overflow-y-auto max-h-[90vh]">
                        <div class="p-8 pb-0 flex items-center justify-between">
                            <h2 class="text-2xl font-black tracking-tight">{{ editing ? 'Edit Admin' : 'Add Admin' }}</h2>
                            <button @click="closeModal" class="p-2 hover:bg-neutral-100 rounded-xl transition-colors">
                                <X class="w-5 h-5 text-neutral-400" />
                            </button>
                        </div>
                        <form @submit.prevent="submitForm" class="p-8 space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Full Name *</label>
                                <input v-model="form.name" type="text" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="Admin Name" />
                                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Email *</label>
                                <input v-model="form.email" type="email" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="admin@example.com" />
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
                                    {{ form.processing ? 'Saving...' : editing ? 'Save Changes' : 'Add Admin' }}
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
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ShieldPlus, ShieldCheck, Pencil, Trash2, X } from 'lucide-vue-next';

const props = defineProps({
    admins: Array,
    currentAdminId: Number,
});

const showModal = ref(false);
const editing = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
});

const openCreate = () => {
    editing.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEdit = (admin) => {
    editing.value = admin;
    form.name = admin.name;
    form.email = admin.email;
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
        form.put(`/admin/admins/${editing.value.id}`, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/admins', {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const deleteAdmin = (id) => {
    if (confirm('Are you sure you want to remove this admin?')) {
        router.delete(`/admin/admins/${id}`, { preserveScroll: true });
    }
};
</script>
