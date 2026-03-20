<template>
    <div class="min-h-screen bg-[#FDFDFD] text-black font-sans antialiased selection:bg-red-600 selection:text-white relative overflow-x-hidden">
        <!-- Abstract Background Mesh -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-red-50/60 blur-[120px]"></div>
            <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] rounded-full bg-red-50/40 blur-[100px]"></div>
        </div>

        <!-- Sidebar -->
        <aside class="fixed top-4 left-4 bottom-4 w-[280px] bg-white/70 backdrop-blur-2xl border border-white/20 z-50 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.04)] transition-all duration-500 group">
            <div class="flex flex-col h-full">
                <!-- Logo Area -->
                <div class="h-24 flex items-center px-10">
                    <Link href="/student/dashboard" class="flex items-center gap-3 group/logo">
                        <div class="w-11 h-11 bg-red-600 rounded-2xl flex items-center justify-center transition-all group-hover/logo:scale-110 group-hover/logo:rotate-3 duration-500 shadow-xl shadow-red-600/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-2xl font-black tracking-tighter uppercase italic">LMS.</span>
                    </Link>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            $page.component === item.component
                                ? 'bg-red-600 text-white shadow-2xl shadow-red-600/20 scale-[1.02]'
                                : 'text-neutral-500 hover:text-red-600 hover:bg-red-50/50 hover:translate-x-1',
                            'group flex items-center px-6 py-4 text-sm font-bold rounded-2xl transition-all duration-500'
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                $page.component === item.component ? 'text-white' : 'text-neutral-400 group-hover:text-red-600',
                                'mr-4 h-5 w-5 transition-colors duration-500'
                            ]"
                            stroke-width="2.5"
                        />
                        {{ item.name }}
                    </Link>
                </nav>

                <!-- Footer User Info -->
                <div class="p-6">
                    <button
                        @click="logout"
                        class="w-full flex items-center px-6 py-4 text-sm font-bold text-neutral-400 hover:text-red-600 hover:bg-red-50/50 rounded-2xl transition-all duration-500 group"
                    >
                        <LogOut class="mr-4 h-5 w-5 transition-colors" stroke-width="2.5" />
                        Sign Out
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="pl-[312px] pr-8 min-h-screen relative z-10">
            <!-- Header -->
            <header class="h-20 bg-white/40 backdrop-blur-md border border-white/40 sticky top-4 z-40 px-10 rounded-[24px] shadow-sm flex items-center justify-between mb-8 transition-all duration-500">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-neutral-400">
                        <span class="hover:text-black cursor-pointer">Portal</span>
                        <ChevronRight class="w-3 h-3" />
                        <span class="text-red-600">{{ currentPageName }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-4 group cursor-pointer p-1.5 pr-4 rounded-2xl hover:bg-white/80 transition-all duration-500">
                        <div class="relative">
                            <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white text-[10px] font-black shadow-lg shadow-red-600/20 group-hover:rotate-12 transition-transform duration-500">
                                {{ initials }}
                            </div>
                            <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-xs font-black leading-none group-hover:translate-x-1 transition-transform duration-500">{{ $page.props.auth.user.name }}</p>
                            <p class="text-[9px] text-neutral-400 font-bold uppercase tracking-widest mt-1">Student ID #2024</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="animate-in fade-in zoom-in-95 duration-700">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    BookOpen,
    User,
    LogOut,
    ChevronRight,
    Target
} from 'lucide-vue-next';

const page = usePage();

const navigation = [
    { name: 'Dashboard', href: '/student/dashboard', icon: LayoutDashboard, component: 'Student/Dashboard' },
    { name: 'Courses', href: '/student/courses', icon: BookOpen, component: 'Student/Courses' },
    { name: 'My Goals', href: '/student/goals', icon: Target, component: 'Student/Goals' },
    { name: 'My Profile', href: '/student/profile', icon: User, component: 'Student/Profile' },
];

const currentPageName = computed(() => {
    const active = navigation.find(item => page.component === item.component);
    return active ? active.name : 'Overview';
});

const initials = computed(() => {
    const name = page.props.auth.user.name;
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
});

const logout = () => {
    router.post('/student/logout');
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Space Grotesk', sans-serif;
}

.animate-in {
    animation-duration: 0.8s;
    animation-fill-mode: both;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes zoom-in {
    from { opacity: 0; transform: scale(0.98) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.fade-in {
    animation-name: fade-in;
}

.zoom-in-95 {
    animation-name: zoom-in;
}
</style>
