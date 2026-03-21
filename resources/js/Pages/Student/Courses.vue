<template>
    <Head title="Courses">
        <meta name="description" content="Browse and explore all available courses. Filter by category and track your progress." />
    </Head>
    <StudentLayout>
        <div class="space-y-6 lg:space-y-12 pb-8 lg:pb-12">
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 lg:pl-6 flex items-center pointer-events-none">
                    <Search class="w-4 h-4 lg:w-5 lg:h-5 text-stone-400 dark:text-stone-500" />
                </div>
                <input
                    v-model="searchQuery"
                    @input="onSearch"
                    type="text"
                    placeholder="Search courses..."
                    class="w-full pl-11 lg:pl-14 pr-10 py-3.5 lg:py-4 bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-xl lg:rounded-2xl text-sm lg:text-base text-stone-800 dark:text-stone-100 font-medium placeholder-stone-400 dark:placeholder-stone-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all shadow-sm dark:shadow-stone-950/20"
                />
                <button v-if="searchQuery" @click="clearSearch" class="absolute inset-y-0 right-0 pr-4 lg:pr-6 flex items-center">
                    <XIcon class="w-4 h-4 text-stone-400 dark:text-stone-500 hover:text-stone-600 dark:hover:text-stone-300 transition-colors" />
                </button>
            </div>

            <!-- Category Filter -->
            <div class="flex items-center gap-2 lg:gap-3 flex-nowrap overflow-x-auto pb-2 -mx-1 px-1 lg:flex-wrap lg:overflow-visible lg:pb-0 bg-white/50 dark:bg-stone-900/50 backdrop-blur-md p-2 rounded-xl lg:rounded-[24px] border border-indigo-100/20 dark:border-stone-700/50 shadow-sm dark:shadow-stone-950/20 scrollbar-hide">
                <button @click="filterCategory(null)"
                    :class="!activeCategory ? 'bg-indigo-500 text-white shadow-xl shadow-indigo-500/20' : 'text-stone-400 dark:text-stone-500 hover:text-stone-700 dark:hover:text-stone-300 hover:bg-white/60 dark:hover:bg-stone-800/60'"
                    class="px-4 lg:px-6 py-2 lg:py-2.5 rounded-xl lg:rounded-[18px] text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap flex-shrink-0">
                    All
                </button>
                <template v-for="cat in categories" :key="cat.id">
                    <button @click="filterCategory(cat.slug)"
                        :class="activeCategory === cat.slug ? 'bg-indigo-500 text-white shadow-xl shadow-indigo-500/20' : 'text-stone-400 dark:text-stone-500 hover:text-stone-700 dark:hover:text-stone-300 hover:bg-white/60 dark:hover:bg-stone-800/60'"
                        class="px-4 lg:px-6 py-2 lg:py-2.5 rounded-xl lg:rounded-[18px] text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap flex-shrink-0">
                        {{ cat.name }}
                    </button>
                    <button v-for="child in cat.children" :key="child.id" @click="filterCategory(child.slug)"
                        :class="activeCategory === child.slug ? 'bg-indigo-500 text-white shadow-xl shadow-indigo-500/20' : 'text-stone-500 dark:text-stone-400 hover:text-stone-700 dark:hover:text-stone-300 hover:bg-white/60 dark:hover:bg-stone-800/60'"
                        class="px-3 lg:px-5 py-1.5 lg:py-2 rounded-lg lg:rounded-[16px] text-[9px] font-bold uppercase tracking-widest transition-all border border-stone-100 dark:border-stone-700 whitespace-nowrap flex-shrink-0">
                        {{ child.name }}
                    </button>
                </template>
            </div>

            <!-- Results Info -->
            <div v-if="searchQuery || activeCategory" class="flex items-center justify-between px-1">
                <p class="text-xs lg:text-sm text-stone-500 dark:text-stone-400 font-medium">
                    <span class="font-bold text-stone-800 dark:text-stone-200">{{ courses.total }}</span> course{{ courses.total !== 1 ? 's' : '' }} found
                    <span v-if="searchQuery"> for "<span class="text-indigo-500 dark:text-indigo-400 font-bold">{{ searchQuery }}</span>"</span>
                </p>
                <button v-if="searchQuery || activeCategory" @click="clearAll" class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors">
                    Clear Filters
                </button>
            </div>

            <!-- Glass Course Grid -->
            <div v-if="courses.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-5">
                <div v-for="course in courses.data" :key="course.id"
                    class="group bg-white/60 dark:bg-stone-900/60 backdrop-blur-xl border border-indigo-100/20 dark:border-stone-700/50 rounded-xl lg:rounded-[24px] overflow-hidden hover:shadow-[0_20px_40px_-12px_rgba(99,102,241,0.1)] dark:hover:shadow-[0_20px_40px_-12px_rgba(0,0,0,0.3)] transition-all duration-700 flex flex-col h-full ring-1 ring-indigo-500/[0.02] dark:ring-stone-700/30">

                    <!-- Media Area -->
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img :src="course.image" class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60 group-hover:opacity-20 transition-opacity"></div>

                        <div class="absolute top-2 lg:top-3 left-2 lg:left-3 flex items-center gap-2">
                            <div class="px-2 py-0.5 lg:px-2.5 lg:py-1 bg-white/90 dark:bg-stone-900/90 backdrop-blur-xl rounded-md lg:rounded-lg shadow-sm border border-white dark:border-stone-700">
                                <span class="text-[7px] lg:text-[8px] font-bold uppercase tracking-[0.2em] text-stone-700 dark:text-stone-300">
                                    {{ course.lessons_count }} Lessons
                                </span>
                            </div>
                        </div>

                        <div class="absolute bottom-2 lg:bottom-3 right-2 lg:right-3 translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 hidden md:block">
                            <div class="w-8 h-8 lg:w-9 lg:h-9 bg-white dark:bg-stone-800 rounded-lg lg:rounded-xl flex items-center justify-center shadow-xl">
                                <Play class="w-3 h-3 lg:w-3.5 lg:h-3.5 fill-stone-800 dark:fill-stone-200" />
                            </div>
                        </div>
                    </div>

                    <!-- Description Area -->
                    <div class="p-3 lg:p-4 flex-1 flex flex-col justify-between relative">
                        <div>
                            <div class="flex items-center gap-1.5 lg:gap-2 mb-1.5 lg:mb-2">
                                <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-indigo-500 group-hover:animate-ping"></span>
                                <span class="text-[7px] lg:text-[8px] font-bold uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors truncate">
                                    {{ course.category_name || 'Uncategorized' }}
                                </span>
                            </div>
                            <h3 class="text-xs lg:text-sm font-heading font-extrabold tracking-tight leading-snug mb-1 lg:mb-2 text-stone-800 dark:text-stone-100 line-clamp-2 group-hover:translate-x-0.5 transition-transform duration-500">{{ course.title }}</h3>
                            <p class="text-stone-500 dark:text-stone-400 text-[10px] lg:text-xs font-medium line-clamp-2 leading-relaxed mb-3 lg:mb-4 hidden sm:block">{{ course.description }}</p>
                        </div>

                        <div class="space-y-2 lg:space-y-3 pt-2 lg:pt-3 border-t border-stone-100/50 dark:border-stone-700/50">
                            <div>
                                <div class="flex items-center justify-between mb-1 lg:mb-1.5">
                                    <span class="text-[7px] lg:text-[8px] font-bold uppercase tracking-[0.2em]" :class="course.progress >= 100 ? 'text-teal-500' : course.progress > 0 ? 'text-indigo-500 dark:text-indigo-400' : 'text-stone-400 dark:text-stone-500'">
                                        {{ course.progress >= 100 ? 'Completed' : course.progress > 0 ? 'In Progress' : 'Not Started' }}
                                    </span>
                                    <span class="text-[8px] lg:text-[9px] font-bold" :class="course.progress >= 100 ? 'text-teal-500' : 'text-stone-600 dark:text-stone-400'">{{ course.progress }}%</span>
                                </div>
                                <div class="h-1 lg:h-1.5 bg-stone-100 dark:bg-stone-800 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000"
                                        :class="course.progress >= 100 ? 'bg-teal-500' : 'bg-indigo-500'"
                                        :style="{ width: (course.progress || 0) + '%' }"></div>
                                </div>
                            </div>

                            <Link :href="`/student/courses/${course.id}`" class="w-full flex items-center justify-center gap-1.5 lg:gap-2 py-2.5 lg:py-3 bg-indigo-500 text-white rounded-lg lg:rounded-xl text-[8px] lg:text-[9px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-500 group-hover:-translate-y-0.5">
                                {{ course.progress >= 100 ? 'Review' : course.progress > 0 ? 'Resume' : 'Start' }}
                                <ArrowRight class="w-3 h-3 lg:w-3.5 lg:h-3.5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12 lg:py-20">
                <div class="w-20 h-20 lg:w-24 lg:h-24 bg-indigo-50 dark:bg-indigo-950/50 rounded-2xl lg:rounded-[28px] flex items-center justify-center mx-auto mb-6 lg:mb-8 border border-indigo-100/30 dark:border-indigo-900/50">
                    <BookOpen class="w-10 h-10 lg:w-12 lg:h-12 text-indigo-300 dark:text-indigo-600" />
                </div>
                <h3 class="text-xl lg:text-2xl font-heading font-extrabold tracking-tight mb-3 text-stone-800 dark:text-stone-100">No Courses Found</h3>
                <p class="text-stone-500 dark:text-stone-400 font-medium max-w-md mx-auto text-sm">
                    {{ searchQuery ? 'No courses match your search. Try different keywords.' : 'No courses match this category filter.' }}
                </p>
                <button @click="clearAll" class="mt-6 px-6 lg:px-8 py-3 bg-indigo-500 text-white rounded-xl lg:rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 transition-all">
                    View All Courses
                </button>
            </div>

            <!-- Pagination -->
            <div v-if="courses.last_page > 1" class="flex items-center justify-center gap-2">
                <Link
                    v-for="link in paginationLinks"
                    :key="link.label"
                    :href="link.url || '#'"
                    :class="[
                        link.active
                            ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20'
                            : link.url
                                ? 'bg-white/60 dark:bg-stone-900/60 text-stone-600 dark:text-stone-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 hover:text-indigo-500 dark:hover:text-indigo-400 border border-indigo-100/20 dark:border-stone-700/50'
                                : 'bg-stone-50 dark:bg-stone-800/50 text-stone-300 dark:text-stone-600 cursor-not-allowed border border-stone-100 dark:border-stone-700/50',
                        'min-w-[36px] lg:min-w-[40px] h-9 lg:h-10 px-2.5 lg:px-3 rounded-xl lg:rounded-2xl text-[10px] lg:text-xs font-bold flex items-center justify-center transition-all duration-300'
                    ]"
                    preserve-scroll
                    v-html="link.label"
                />
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowRight, Play, BookOpen, Search, X as XIcon } from 'lucide-vue-next';

const props = defineProps({
    courses: Object,
    categories: Array,
    activeCategory: String,
    search: String,
});

const searchQuery = ref(props.search || '');
let searchTimeout = null;

const paginationLinks = computed(() => {
    return props.courses.links.map(link => ({
        ...link,
        label: link.label
            .replace('&laquo; Previous', '‹')
            .replace('Next &raquo;', '›'),
    }));
});

const buildParams = (overrides = {}) => {
    const params = {};
    const category = overrides.category !== undefined ? overrides.category : props.activeCategory;
    const search = overrides.search !== undefined ? overrides.search : searchQuery.value;
    if (category) params.category = category;
    if (search) params.search = search;
    return params;
};

const onSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/student/courses', buildParams({ search: searchQuery.value }), {
            preserveState: true,
            preserveScroll: true,
        });
    }, 400);
};

const clearSearch = () => {
    searchQuery.value = '';
    router.get('/student/courses', buildParams({ search: null }), {
        preserveState: true,
        preserveScroll: true,
    });
};

const filterCategory = (slug) => {
    router.get('/student/courses', buildParams({ category: slug }), {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearAll = () => {
    searchQuery.value = '';
    router.get('/student/courses', {}, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
