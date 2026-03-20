<template>
    <div class="min-h-screen bg-white flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-600 rounded-2xl mb-6 shadow-lg shadow-red-600/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-black tracking-tight">LMS Admin</h1>
                <p class="text-neutral-500 mt-2 text-sm">Sign in to admin panel</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-black mb-2">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        required
                        :class="[
                            'w-full px-4 py-3 bg-neutral-50 border rounded-xl text-black placeholder-neutral-400 outline-none transition-all duration-200',
                            form.errors.email ? 'border-red-400 focus:ring-2 focus:ring-red-100' : 'border-neutral-200 focus:border-red-600 focus:ring-2 focus:ring-red-100'
                        ]"
                        placeholder="admin@example.com"
                    />
                    <p v-if="form.errors.email" class="mt-2 text-sm text-red-500">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-black mb-2">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="current-password"
                            required
                            class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-xl text-black placeholder-neutral-400 outline-none transition-all duration-200 focus:border-red-600 focus:ring-2 focus:ring-red-100 pr-12"
                            placeholder="Enter your password"
                        />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-red-600 transition-colors">
                            <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="form.errors.password" class="mt-2 text-sm text-red-500">{{ form.errors.password }}</p>
                </div>

                <div class="flex items-center">
                    <input id="remember" v-model="form.remember" type="checkbox"
                        class="w-4 h-4 rounded border-neutral-300 text-red-600 focus:ring-red-600 accent-red-600" />
                    <label for="remember" class="ml-2 text-sm text-neutral-600">Remember me</label>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full py-3 px-4 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-red-600/20">
                    <span v-if="!form.processing">Sign In</span>
                    <span v-else class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Signing in...
                    </span>
                </button>
            </form>

            <p class="text-center text-neutral-400 text-xs mt-10">LMS Admin Panel</p>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/lms-secure-login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
