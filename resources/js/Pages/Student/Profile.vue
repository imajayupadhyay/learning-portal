<template>
    <StudentLayout>
        <div class="max-w-4xl space-y-8">
            <div>
                <h1 class="text-2xl font-bold text-black tracking-tight">Profile Settings</h1>
                <p class="text-neutral-500 mt-1">Manage your account information and preferences.</p>
            </div>

            <div class="bg-white border border-neutral-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-8 border-b border-neutral-100 flex items-center gap-6">
                    <div class="w-20 h-20 bg-red-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-red-600/20">
                        {{ initials }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-black">{{ user.name }}</h2>
                        <p class="text-neutral-500">{{ user.email }}</p>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-400 uppercase tracking-widest">Full Name</label>
                            <input type="text" :value="user.name" class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all" readonly />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" :value="user.email" class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all" readonly />
                        </div>
                    </div>

                    <div class="pt-6 border-t border-neutral-100 flex justify-end">
                        <button class="px-8 py-3 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { computed } from 'vue';
import StudentLayout from '@/Layouts/StudentLayout.vue';

const props = defineProps({
    user: Object,
});

const initials = computed(() => {
    return props.user.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
});
</script>
