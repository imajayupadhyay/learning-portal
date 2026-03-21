<template>
    <div class="min-h-screen bg-[#FAFAF9] dark:bg-stone-950 text-stone-900 dark:text-stone-100 antialiased selection:bg-indigo-500 selection:text-white relative overflow-x-hidden font-body transition-colors duration-300">
        <!-- Abstract Background Mesh -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-indigo-50/50 dark:bg-indigo-950/30 blur-[120px]"></div>
            <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] rounded-full bg-teal-50/40 dark:bg-teal-950/20 blur-[100px]"></div>
            <div class="absolute bottom-[10%] left-[30%] w-[25%] h-[25%] rounded-full bg-indigo-50/30 dark:bg-indigo-950/20 blur-[100px]"></div>
        </div>

        <!-- Sidebar -->
        <aside class="fixed top-4 left-4 bottom-4 w-[280px] bg-white/70 dark:bg-stone-900/80 backdrop-blur-2xl border border-indigo-100/30 dark:border-stone-700/50 z-50 rounded-[32px] shadow-[0_8px_32px_rgba(99,102,241,0.06)] dark:shadow-[0_8px_32px_rgba(0,0,0,0.3)] transition-all duration-500 group">
            <div class="flex flex-col h-full">
                <!-- Logo Area -->
                <div class="h-24 flex items-center px-10">
                    <Link href="/student/dashboard" class="flex items-center gap-3 group/logo">
                        <div class="w-11 h-11 bg-indigo-500 rounded-2xl flex items-center justify-center transition-all group-hover/logo:scale-110 group-hover/logo:rotate-3 duration-500 shadow-xl shadow-indigo-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-2xl font-heading font-extrabold tracking-tight text-stone-800 dark:text-stone-100">LMS</span>
                    </Link>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1.5">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            isActive(item.component)
                                ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20 scale-[1.02]'
                                : 'text-stone-500 dark:text-stone-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/50 hover:translate-x-1',
                            'group flex items-center px-6 py-3.5 text-sm font-semibold rounded-2xl transition-all duration-500'
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                isActive(item.component) ? 'text-white' : 'text-stone-400 dark:text-stone-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400',
                                'mr-4 h-5 w-5 transition-colors duration-500'
                            ]"
                            stroke-width="2"
                        />
                        {{ item.name }}
                    </Link>
                </nav>

                <!-- Footer User Info -->
                <div class="p-6">
                    <button
                        @click="logout"
                        class="w-full flex items-center px-6 py-3.5 text-sm font-semibold text-stone-400 dark:text-stone-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/50 rounded-2xl transition-all duration-500 group"
                    >
                        <LogOut class="mr-4 h-5 w-5 transition-colors" stroke-width="2" />
                        Sign Out
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="pl-[312px] pr-8 min-h-screen relative z-10">
            <!-- Header -->
            <header class="h-20 bg-white/50 dark:bg-stone-900/50 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 sticky top-4 z-40 px-10 rounded-[24px] shadow-sm dark:shadow-stone-950/20 flex items-center justify-between mb-8 transition-all duration-500">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500">
                        <span class="hover:text-stone-700 dark:hover:text-stone-300 cursor-pointer transition-colors">Portal</span>
                        <ChevronRight class="w-3 h-3" />
                        <span class="text-indigo-500 dark:text-indigo-400 font-semibold">{{ currentPageName }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button
                        @click="toggle"
                        class="p-2.5 rounded-xl hover:bg-stone-100 dark:hover:bg-stone-800 transition-all duration-300"
                        :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    >
                        <Sun v-if="isDark" class="w-5 h-5 text-amber-400" />
                        <Moon v-else class="w-5 h-5 text-stone-400" />
                    </button>

                    <div class="flex items-center gap-4 group cursor-pointer p-1.5 pr-4 rounded-2xl hover:bg-white/80 dark:hover:bg-stone-800/80 transition-all duration-500">
                        <div class="relative">
                            <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white text-[10px] font-bold shadow-lg shadow-indigo-500/20 group-hover:rotate-12 transition-transform duration-500">
                                {{ initials }}
                            </div>
                            <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-teal-400 border-2 border-white dark:border-stone-900 rounded-full"></div>
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-xs font-semibold leading-none text-stone-800 dark:text-stone-200 group-hover:translate-x-1 transition-transform duration-500">{{ $page.props.auth.user.name }}</p>
                            <p class="text-[9px] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-widest mt-1">Student</p>
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
    Target,
    ListTodo,
    Sun,
    Moon
} from 'lucide-vue-next';
import { useDarkMode } from '@/composables/useDarkMode';

const { isDark, toggle } = useDarkMode();
const page = usePage();

const navigation = [
    { name: 'Dashboard', href: '/student/dashboard', icon: LayoutDashboard, component: 'Student/Dashboard' },
    { name: 'Courses', href: '/student/courses', icon: BookOpen, component: 'Student/Courses' },
    { name: 'My Goals', href: '/student/goals', icon: Target, component: 'Student/Goals' },
    { name: 'Todos', href: '/student/todos', icon: ListTodo, component: 'Student/Todos' },
    { name: 'My Profile', href: '/student/profile', icon: User, component: 'Student/Profile' },
];

const isActive = (component) => {
    if (component === 'Student/Courses') {
        return page.component === 'Student/Courses' || page.component === 'Student/CourseShow';
    }
    return page.component === component;
};

const currentPageName = computed(() => {
    const active = navigation.find(item => isActive(item.component));
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
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap');

.font-heading {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.font-body {
    font-family: 'DM Sans', sans-serif;
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
