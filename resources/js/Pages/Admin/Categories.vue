<template>
    <Head title="Categories">
        <meta name="description" content="Manage course categories and subcategories." />
    </Head>
    <AdminLayout>
        <div class="space-y-10 pb-12 relative">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div>
                    <h1 class="text-5xl font-black tracking-tighter italic mb-4">Categories.</h1>
                    <p class="text-neutral-500 text-lg font-medium">Manage hierarchical course categories.</p>
                </div>
                <button @click="openCreate"
                    class="flex items-center gap-3 px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all duration-500 shrink-0">
                    <Plus class="w-4 h-4" />
                    Add Category
                </button>
            </div>

            <!-- Stats -->
            <div class="flex items-center gap-4">
                <div class="px-6 py-4 bg-red-50 border border-red-100 rounded-2xl">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600">{{ categories.length }} Categories</span>
                </div>
                <div class="px-6 py-4 bg-neutral-50 border border-neutral-100 rounded-2xl">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-500">{{ parentCount }} Parent · {{ childCount }} Child</span>
                </div>
            </div>

            <!-- Categories Tree Table -->
            <div v-if="categories.length > 0" class="bg-white/60 backdrop-blur-md border border-white rounded-[28px] overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="text-left px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-neutral-400">Category Name</th>
                            <th class="text-center px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-neutral-400">Courses</th>
                            <th class="text-center px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-neutral-400">Children</th>
                            <th class="text-center px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-neutral-400">Order</th>
                            <th class="text-right px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-neutral-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cat in categories" :key="cat.id"
                            class="border-b border-neutral-50 hover:bg-red-50/30 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3" :style="{ paddingLeft: cat.depth * 28 + 'px' }">
                                    <div v-if="cat.depth > 0" class="flex items-center gap-1.5 text-neutral-300">
                                        <CornerDownRight class="w-3.5 h-3.5" />
                                    </div>
                                    <div :class="cat.depth === 0 ? 'w-8 h-8 bg-red-600 rounded-xl' : 'w-8 h-8 bg-red-100 rounded-xl'"
                                        class="flex items-center justify-center shrink-0">
                                        <FolderTree :class="cat.depth === 0 ? 'w-4 h-4 text-white' : 'w-4 h-4 text-red-400'" />
                                    </div>
                                    <div>
                                        <span class="font-bold text-sm">{{ cat.name }}</span>
                                        <span class="ml-2 text-[9px] font-bold text-neutral-400">{{ cat.slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center px-6 py-4">
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black">{{ cat.courses_count }}</span>
                            </td>
                            <td class="text-center px-6 py-4">
                                <span class="text-sm font-bold text-neutral-500">{{ cat.children_count }}</span>
                            </td>
                            <td class="text-center px-6 py-4">
                                <span class="text-sm font-bold text-neutral-400">{{ cat.sort_order }}</span>
                            </td>
                            <td class="text-right px-8 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit(cat)"
                                        class="p-2 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-all">
                                        <Pencil class="w-3.5 h-3.5 text-neutral-500" />
                                    </button>
                                    <button @click="deleteCategory(cat.id)"
                                        class="p-2 bg-neutral-50 hover:bg-red-50 rounded-xl transition-all">
                                        <Trash2 class="w-3.5 h-3.5 text-neutral-400 hover:text-red-600" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="w-24 h-24 bg-red-50 rounded-[28px] flex items-center justify-center mx-auto mb-8 border border-red-100">
                    <FolderTree class="w-12 h-12 text-red-300" />
                </div>
                <h3 class="text-2xl font-black tracking-tight mb-3">No Categories Yet</h3>
                <p class="text-neutral-500 font-medium max-w-md mx-auto">Create your first category to organize courses.</p>
            </div>

            <!-- Modal -->
            <Teleport to="body">
                <div v-if="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
                    <div class="relative w-full max-w-lg bg-white rounded-[32px] shadow-2xl overflow-y-auto max-h-[90vh]">
                        <div class="p-8 pb-0 flex items-center justify-between">
                            <h2 class="text-2xl font-black tracking-tight">{{ editing ? 'Edit Category' : 'Add Category' }}</h2>
                            <button @click="closeModal" class="p-2 hover:bg-neutral-100 rounded-xl transition-colors">
                                <X class="w-5 h-5 text-neutral-400" />
                            </button>
                        </div>
                        <form @submit.prevent="submitForm" class="p-8 space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Category Name *</label>
                                <input v-model="form.name" type="text" required
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="e.g. Cloud Computing" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Parent Category</label>
                                <select v-model="form.parent_id"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all">
                                    <option :value="null">None (Top Level)</option>
                                    <option v-for="cat in availableParents" :key="cat.id" :value="cat.id">
                                        {{ '—'.repeat(cat.depth) + ' ' + cat.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-neutral-400 uppercase tracking-[0.2em]">Sort Order</label>
                                <input v-model.number="form.sort_order" type="number"
                                    class="w-full px-5 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl text-black font-medium focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition-all"
                                    placeholder="0" />
                            </div>
                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" :disabled="processing"
                                    class="flex-1 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/20 transition-all disabled:opacity-50">
                                    {{ processing ? 'Saving...' : editing ? 'Save Changes' : 'Add Category' }}
                                </button>
                                <button type="button" @click="closeModal"
                                    class="px-8 py-4 bg-neutral-100 text-neutral-500 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-neutral-200 transition-all">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, FolderTree, Pencil, Trash2, X, CornerDownRight } from 'lucide-vue-next';

const props = defineProps({
    categories: Array,
});

const showModal = ref(false);
const editing = ref(null);
const processing = ref(false);

const form = ref({
    name: '',
    parent_id: null,
    sort_order: 0,
});

const parentCount = computed(() => props.categories.filter(c => c.depth === 0).length);
const childCount = computed(() => props.categories.filter(c => c.depth > 0).length);

const availableParents = computed(() => {
    if (!editing.value) return props.categories;
    // Exclude the editing category and its descendants
    const excludeIds = getDescendantIds(editing.value.id);
    return props.categories.filter(c => !excludeIds.includes(c.id));
});

function getDescendantIds(parentId) {
    const ids = [parentId];
    props.categories.forEach(c => {
        if (c.parent_id === parentId) {
            ids.push(...getDescendantIds(c.id));
        }
    });
    return ids;
}

const openCreate = () => {
    editing.value = null;
    form.value = { name: '', parent_id: null, sort_order: 0 };
    showModal.value = true;
};

const openEdit = (cat) => {
    editing.value = cat;
    form.value = { name: cat.name, parent_id: cat.parent_id, sort_order: cat.sort_order };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = null;
};

const submitForm = () => {
    processing.value = true;
    const url = editing.value ? `/admin/categories/${editing.value.id}` : '/admin/categories';
    const method = editing.value ? 'put' : 'post';

    router[method](url, form.value, {
        preserveScroll: true,
        onSuccess: () => { closeModal(); processing.value = false; },
        onError: () => { processing.value = false; },
    });
};

const deleteCategory = (id) => {
    if (confirm('Are you sure? This will also delete all child categories.')) {
        router.delete(`/admin/categories/${id}`, { preserveScroll: true });
    }
};
</script>
