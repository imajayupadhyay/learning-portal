<template>
    <StudentLayout>
        <div class="space-y-8">
            <!-- Welcome -->
            <div class="bg-white border border-neutral-200 rounded-2xl p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-black">Welcome, {{ user.name }}</h1>
                        <p class="text-neutral-500 mt-1">Ready to continue your learning journey?</p>
                    </div>
                    <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stats -->
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

            <!-- Profile -->
            <div class="bg-white border border-neutral-200 rounded-2xl p-8">
                <h2 class="text-lg font-semibold text-black mb-4">My Profile</h2>
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
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';

defineProps({
    user: Object,
});

const stats = [
    {
        label: 'Enrolled Courses',
        value: '0',
        icon: '<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>',
    },
    {
        label: 'Completed',
        value: '0',
        icon: '<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    },
    {
        label: 'In Progress',
        value: '0',
        icon: '<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    },
];
</script>
