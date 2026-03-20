<template>
    <div class="min-h-screen bg-gradient-to-br from-neutral-50 via-white to-red-50/30 flex items-center justify-center px-6 py-12 relative overflow-hidden">
        <!-- Background Decorative Elements -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-red-100/40 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-red-50/60 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative text-center max-w-lg mx-auto">
            <!-- Error Code -->
            <div class="relative mb-8">
                <h1 class="text-[180px] md:text-[220px] font-black tracking-tighter leading-none text-transparent bg-clip-text bg-gradient-to-b from-red-600 to-red-200 select-none">
                    {{ status }}
                </h1>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-white/80 backdrop-blur-xl border border-white rounded-[28px] shadow-xl shadow-red-600/10 flex items-center justify-center">
                        <svg v-if="status === 404" class="w-10 h-10 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                        </svg>
                        <svg v-else-if="status === 403" class="w-10 h-10 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <svg v-else class="w-10 h-10 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-4 text-neutral-900">
                {{ title }}
            </h2>

            <!-- Description -->
            <p class="text-neutral-500 text-lg font-medium mb-10 leading-relaxed max-w-md mx-auto">
                {{ description }}
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/"
                    class="flex items-center gap-3 px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all duration-500">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Go Home
                </a>
                <button @click="goBack"
                    class="flex items-center gap-3 px-8 py-4 bg-white/70 backdrop-blur-md border border-neutral-200 text-neutral-600 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white hover:shadow-lg hover:shadow-black/5 transition-all duration-500">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Go Back
                </button>
            </div>

            <!-- Branding -->
            <div class="mt-16">
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-neutral-300">LMS Platform</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: Number,
});

const title = computed(() => {
    const titles = {
        404: 'Page Not Found',
        403: 'Access Forbidden',
        500: 'Server Error',
        503: 'Service Unavailable',
    };
    return titles[props.status] || 'Something Went Wrong';
});

const description = computed(() => {
    const descriptions = {
        404: "The page you're looking for doesn't exist or has been moved. Let's get you back on track.",
        403: "You don't have permission to access this page. Please contact an administrator if you think this is a mistake.",
        500: "Something went wrong on our end. We're working on fixing it. Please try again later.",
        503: "The service is temporarily unavailable. Please try again in a few moments.",
    };
    return descriptions[props.status] || "An unexpected error occurred. Please try again.";
});

const goBack = () => {
    window.history.back();
};
</script>
