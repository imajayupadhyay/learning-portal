<template>
    <div class="min-h-screen bg-neutral-50">
        <!-- Navbar -->
        <nav class="bg-white border-b border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-black rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-black tracking-tight">LMS</span>
                        <span class="text-xs font-medium text-neutral-400 bg-neutral-100 px-2 py-0.5 rounded-md uppercase tracking-wider">Admin</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-black">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-neutral-500">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ initials }}
                        </div>
                        <form @submit.prevent="logout">
                            <button
                                type="submit"
                                class="ml-2 px-4 py-2 text-sm font-medium text-neutral-600 hover:text-black border border-neutral-200 rounded-lg hover:border-neutral-400 transition-all duration-200"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();

const initials = computed(() => {
    const name = page.props.auth.user.name;
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
});

const logout = () => {
    router.post('/admin/logout');
};
</script>
