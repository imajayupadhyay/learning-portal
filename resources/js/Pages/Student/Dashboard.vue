<template>
    <StudentLayout>
        <div class="space-y-10 pb-12">
            <!-- Hero Glass Panel -->
            <div class="relative overflow-hidden bg-black rounded-[40px] p-16 text-white shadow-2xl shadow-black/20">
                <div class="relative z-10 max-w-3xl">
                    <div class="flex items-center gap-3 mb-8">
                        <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md text-[10px] font-black uppercase tracking-[0.3em] rounded-full ring-1 ring-white/20">Learning Path 2024</span>
                    </div>
                    <h1 class="text-6xl font-black tracking-tighter leading-[0.9] mb-8 italic">
                        Accelerate your <br/>
                        <span class="text-neutral-500">Engineering.</span>
                    </h1>
                    <p class="text-xl text-neutral-400 font-medium leading-relaxed max-w-xl mb-12">
                        Welcome back, <span class="text-white underline underline-offset-8 decoration-neutral-700">{{ user.name }}</span>. 
                        Your DevOps track is waiting. Ready to push to production?
                    </p>
                    <div class="flex items-center gap-6">
                        <Link href="/student/courses" class="group px-10 py-5 bg-white text-black font-black rounded-2xl hover:bg-neutral-200 transition-all duration-500 flex items-center gap-3">
                            Resume Now
                            <ArrowRight class="w-5 h-5 group-hover:translate-x-2 transition-transform" />
                        </Link>
                        <div class="flex items-center gap-4 pl-6 border-l border-white/10">
                            <div class="flex -space-x-3">
                                <div v-for="i in 3" :key="i" class="w-10 h-10 rounded-full border-2 border-black bg-neutral-800 ring-2 ring-white/10"></div>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">1.2k+ Peers Active</p>
                        </div>
                    </div>
                </div>
                <!-- Abstract Glass Elements -->
                <div class="absolute top-[-20%] right-[-10%] w-[500px] h-[500px] bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
                <div class="absolute bottom-[10%] right-[10%] w-32 h-32 bg-white/5 rounded-3xl rotate-12 backdrop-blur-3xl ring-1 ring-white/20"></div>
            </div>

            <!-- Modern Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="stat in stats" :key="stat.label"
                    class="bg-white/60 backdrop-blur-md border border-white rounded-[32px] p-10 shadow-sm hover:shadow-xl hover:shadow-black/5 transition-all duration-500 group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-black rounded-2xl flex items-center justify-center text-white shadow-lg shadow-black/10">
                            <Activity class="w-5 h-5" v-if="stat.label === 'Enrolled'" />
                            <CheckCircle class="w-5 h-5" v-else-if="stat.label === 'Completed'" />
                            <Clock class="w-5 h-5" v-else />
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Real-time</span>
                    </div>
                    <p class="text-5xl font-black tracking-tighter mb-2 italic">{{ stat.value }}</p>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-neutral-400">{{ stat.label }}</p>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Activity Column -->
                <div class="lg:col-span-2 space-y-10">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-3xl font-black tracking-tighter italic">Recent Lab</h2>
                        <Link href="/student/courses" class="text-[10px] font-black uppercase tracking-widest text-neutral-400 hover:text-black transition-colors underline underline-offset-8">Explore All</Link>
                    </div>

                    <div v-for="course in recent_courses" :key="course.id"
                        class="group relative bg-white border border-neutral-100 rounded-[40px] overflow-hidden hover:shadow-2xl hover:shadow-black/5 transition-all duration-700">
                        <div class="flex flex-col md:flex-row h-full">
                            <div class="md:w-80 h-72 md:h-auto overflow-hidden relative">
                                <img :src="course.image" class="w-full h-full object-cover grayscale transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-700"></div>
                            </div>
                            <div class="p-12 flex-1 flex flex-col justify-between bg-white/40 backdrop-blur-xl">
                                <div>
                                    <div class="flex items-center gap-3 mb-6">
                                        <span class="px-3 py-1 bg-black text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Level: Pro</span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></div>
                                    </div>
                                    <h3 class="text-3xl font-black tracking-tight leading-tight mb-6 group-hover:translate-x-2 transition-transform duration-500">{{ course.title }}</h3>
                                </div>
                                
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[0.2em]">
                                        <span class="text-neutral-400">Sync Progress</span>
                                        <span class="text-black">{{ course.progress }}%</span>
                                    </div>
                                    <div class="h-2 bg-neutral-100 rounded-full overflow-hidden p-0.5 ring-1 ring-black/5">
                                        <div class="h-full bg-black rounded-full transition-all duration-1000 ease-out" :style="{ width: course.progress + '%' }"></div>
                                    </div>
                                    <Link :href="`/student/courses/${course.id}`" class="inline-flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.3em] group/link">
                                        Jump Back In
                                        <ChevronRight class="w-4 h-4 group-hover/link:translate-x-2 transition-transform" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar column -->
                <div class="space-y-10">
                    <h2 class="text-3xl font-black tracking-tighter italic px-2">Feed</h2>
                    <div class="bg-white border border-neutral-100 rounded-[40px] p-10 space-y-10 shadow-sm">
                        <div v-for="i in 3" :key="i" class="flex gap-6 group cursor-pointer">
                            <div class="w-14 h-14 bg-neutral-50 rounded-[20px] flex-shrink-0 flex items-center justify-center border border-neutral-100 group-hover:bg-black group-hover:text-white transition-all duration-500 shadow-sm">
                                <MessageSquare class="w-6 h-6" />
                            </div>
                            <div class="pt-1">
                                <h4 class="text-sm font-bold leading-tight group-hover:underline underline-offset-8 decoration-2 decoration-black/10">New deployment strategies added to K8s course</h4>
                                <p class="text-[10px] font-black uppercase tracking-widest text-neutral-400 mt-3 flex items-center gap-2">
                                    <div class="w-1 h-1 rounded-full bg-neutral-300"></div>
                                    12m ago
                                </p>
                            </div>
                        </div>
                        <button class="w-full py-5 bg-neutral-50 border border-neutral-100 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-black hover:text-white transition-all duration-500 shadow-sm">
                            View Network
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link } from '@inertiajs/vue3';
import { 
    ArrowRight, 
    ChevronRight, 
    Activity, 
    CheckCircle, 
    Clock, 
    MessageSquare 
} from 'lucide-vue-next';

defineProps({
    user: Object,
    recent_courses: Array,
    stats: Array,
});
</script>
