<template>
    <Head title="Login">
        <meta name="description" content="Sign in to your student portal to access courses and track your learning progress." />
    </Head>
    <div class="min-h-screen bg-[#FAFAF9] dark:bg-stone-950 flex items-center justify-center px-4 font-body transition-colors duration-300">
        <div class="w-full max-w-md">
            <!-- Dark Mode Toggle -->
            <div class="flex justify-end mb-6">
                <button @click="toggle" class="p-2.5 rounded-xl hover:bg-stone-100 dark:hover:bg-stone-800 transition-all" :title="isDark ? 'Light mode' : 'Dark mode'">
                    <svg v-if="isDark" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg v-else class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>

            <!-- Brand -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-500 rounded-2xl mb-6 shadow-lg shadow-indigo-500/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1 class="text-3xl font-heading font-extrabold text-stone-800 dark:text-stone-100 tracking-tight">LMS Student</h1>
                <p class="text-stone-500 dark:text-stone-400 mt-2 text-sm">Access your learning portal</p>
            </div>

            <!-- Registration Pending Message -->
            <div v-if="registeredMessage" class="mb-6 p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Registration Pending</p>
                        <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">{{ registeredMessage }}</p>
                    </div>
                </div>
            </div>

            <!-- Approval Error Message -->
            <div v-if="loginForm.errors.approval" class="mb-6 p-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-red-800 dark:text-red-300">Account Not Approved</p>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">{{ loginForm.errors.approval }}</p>
                    </div>
                </div>
            </div>

            <!-- Tab Switcher -->
            <div class="flex bg-indigo-50 dark:bg-stone-800 rounded-xl p-1 mb-8">
                <button
                    @click="activeTab = 'login'"
                    :class="[
                        'flex-1 py-2.5 text-sm font-medium rounded-lg transition-all duration-200',
                        activeTab === 'login'
                            ? 'bg-indigo-500 text-white shadow-sm'
                            : 'text-stone-500 dark:text-stone-400 hover:text-indigo-500 dark:hover:text-indigo-400'
                    ]"
                >
                    Sign In
                </button>
                <button
                    @click="activeTab = 'register'"
                    :class="[
                        'flex-1 py-2.5 text-sm font-medium rounded-lg transition-all duration-200',
                        activeTab === 'register'
                            ? 'bg-indigo-500 text-white shadow-sm'
                            : 'text-stone-500 dark:text-stone-400 hover:text-indigo-500 dark:hover:text-indigo-400'
                    ]"
                >
                    Register
                </button>
            </div>

            <!-- Login Form -->
            <form v-if="activeTab === 'login'" @submit.prevent="submitLogin" class="space-y-5">
                <div>
                    <label for="login-email" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Email</label>
                    <input
                        id="login-email"
                        v-model="loginForm.email"
                        type="email"
                        autocomplete="email"
                        required
                        :class="[
                            'w-full px-4 py-3 bg-stone-50 dark:bg-stone-800 border rounded-xl text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-600 outline-none transition-all duration-200',
                            loginForm.errors.email ? 'border-red-400 focus:ring-2 focus:ring-red-100 dark:focus:ring-red-900' : 'border-stone-200 dark:border-stone-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900'
                        ]"
                        placeholder="you@example.com"
                    />
                    <p v-if="loginForm.errors.email" class="mt-2 text-sm text-red-500">{{ loginForm.errors.email }}</p>
                </div>

                <div>
                    <label for="login-password" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Password</label>
                    <div class="relative">
                        <input
                            id="login-password"
                            v-model="loginForm.password"
                            :type="showLoginPassword ? 'text' : 'password'"
                            autocomplete="current-password"
                            required
                            class="w-full px-4 py-3 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-600 outline-none transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900 pr-12"
                            placeholder="Enter your password"
                        />
                        <button type="button" @click="showLoginPassword = !showLoginPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 dark:text-stone-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors">
                            <svg v-if="!showLoginPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="loginForm.errors.password" class="mt-2 text-sm text-red-500">{{ loginForm.errors.password }}</p>
                </div>

                <div class="flex items-center">
                    <input id="remember" v-model="loginForm.remember" type="checkbox"
                        class="w-4 h-4 rounded border-stone-300 dark:border-stone-600 text-indigo-500 focus:ring-indigo-500 accent-indigo-500" />
                    <label for="remember" class="ml-2 text-sm text-stone-600 dark:text-stone-400">Remember me</label>
                </div>

                <button type="submit" :disabled="loginForm.processing"
                    class="w-full py-3 px-4 bg-indigo-500 text-white font-medium rounded-xl hover:bg-indigo-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-500/20">
                    <span v-if="!loginForm.processing">Sign In</span>
                    <span v-else class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Signing in...
                    </span>
                </button>
            </form>

            <!-- Register Form -->
            <form v-if="activeTab === 'register'" @submit.prevent="submitRegister" class="space-y-5">
                <div>
                    <label for="reg-name" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Full Name</label>
                    <input
                        id="reg-name"
                        v-model="registerForm.name"
                        type="text"
                        autocomplete="name"
                        required
                        :class="[
                            'w-full px-4 py-3 bg-stone-50 dark:bg-stone-800 border rounded-xl text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-600 outline-none transition-all duration-200',
                            registerForm.errors.name ? 'border-red-400 focus:ring-2 focus:ring-red-100 dark:focus:ring-red-900' : 'border-stone-200 dark:border-stone-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900'
                        ]"
                        placeholder="John Doe"
                    />
                    <p v-if="registerForm.errors.name" class="mt-2 text-sm text-red-500">{{ registerForm.errors.name }}</p>
                </div>

                <div>
                    <label for="reg-email" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Email</label>
                    <input
                        id="reg-email"
                        v-model="registerForm.email"
                        type="email"
                        autocomplete="email"
                        required
                        :class="[
                            'w-full px-4 py-3 bg-stone-50 dark:bg-stone-800 border rounded-xl text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-600 outline-none transition-all duration-200',
                            registerForm.errors.email ? 'border-red-400 focus:ring-2 focus:ring-red-100 dark:focus:ring-red-900' : 'border-stone-200 dark:border-stone-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900'
                        ]"
                        placeholder="you@example.com"
                    />
                    <p v-if="registerForm.errors.email" class="mt-2 text-sm text-red-500">{{ registerForm.errors.email }}</p>
                </div>

                <div>
                    <label for="reg-password" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Password</label>
                    <div class="relative">
                        <input
                            id="reg-password"
                            v-model="registerForm.password"
                            :type="showRegPassword ? 'text' : 'password'"
                            autocomplete="new-password"
                            required
                            class="w-full px-4 py-3 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-600 outline-none transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900 pr-12"
                            placeholder="Min. 8 characters"
                        />
                        <button type="button" @click="showRegPassword = !showRegPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 dark:text-stone-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors">
                            <svg v-if="!showRegPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="registerForm.errors.password" class="mt-2 text-sm text-red-500">{{ registerForm.errors.password }}</p>
                </div>

                <div>
                    <label for="reg-password-confirm" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Confirm Password</label>
                    <input
                        id="reg-password-confirm"
                        v-model="registerForm.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="w-full px-4 py-3 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-600 outline-none transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900"
                        placeholder="Confirm your password"
                    />
                </div>

                <button type="submit" :disabled="registerForm.processing"
                    class="w-full py-3 px-4 bg-indigo-500 text-white font-medium rounded-xl hover:bg-indigo-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-500/20">
                    <span v-if="!registerForm.processing">Create Account</span>
                    <span v-else class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Creating account...
                    </span>
                </button>
            </form>

            <p class="text-center text-stone-400 dark:text-stone-600 text-xs mt-10">LMS &mdash; Learning Management System</p>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useDarkMode } from '@/composables/useDarkMode';

const { isDark, toggle } = useDarkMode();
const page = usePage();
const activeTab = ref('login');
const registeredMessage = computed(() => page.props.flash?.registered || null);
const showLoginPassword = ref(false);
const showRegPassword = ref(false);

const loginForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submitLogin = () => {
    loginForm.post('/login', {
        onFinish: () => loginForm.reset('password'),
    });
};

const submitRegister = () => {
    registerForm.post('/register', {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
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
</style>
