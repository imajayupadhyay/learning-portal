<template>
    <AdminLayout>
        <div class="space-y-8">
            <div class="bg-white border border-neutral-200 rounded-2xl p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-black">Welcome back, {{ user.name }}</h1>
                        <p class="text-neutral-500 mt-1">Here's your admin dashboard overview.</p>
                    </div>
                    <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="stat in stats" :key="stat.label"
                    class="bg-white border border-neutral-200 rounded-2xl p-6 hover:border-neutral-300 transition-colors duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-neutral-100 rounded-xl flex items-center justify-center">
                            <component :is="'div'" v-html="stat.icon" />
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-black">{{ stat.value }}</p>
                    <p class="text-sm text-neutral-500 mt-1">{{ stat.label }}</p>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 rounded-2xl p-8">
                <h2 class="text-lg font-semibold text-black mb-4">Account Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Name</p>
                        <p class="text-black mt-1">{{ user.name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Email</p>
                        <p class="text-black mt-1">{{ user.email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    user: Object,
});

const stats = [
    {
        label: 'Total Courses',
        value: '0',
        icon: '<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>',
    },
    {
        label: 'Total Students',
        value: '0',
        icon: '<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
    },
    {
        label: 'Active Sessions',
        value: '1',
        icon: '<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
    },
];
</script>
