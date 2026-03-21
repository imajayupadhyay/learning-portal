<template>
    <Head title="My Profile">
        <meta name="description" content="Update your personal details, education, and social links." />
    </Head>
    <StudentLayout>
        <div class="max-w-5xl space-y-6 lg:space-y-10 pb-8 lg:pb-12">
            <div>
                <h1 class="text-3xl lg:text-5xl font-heading font-extrabold tracking-tighter text-stone-800 dark:text-stone-100 mb-2 lg:mb-4">My Profile.</h1>
                <p class="text-stone-500 dark:text-stone-400 text-sm lg:text-lg font-medium">Complete your profile to stand out and track your learning journey.</p>
            </div>

            <form @submit.prevent="submitForm">
                <!-- Avatar & Basic Info -->
                <div class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-2xl lg:rounded-[32px] p-5 lg:p-10 shadow-sm dark:shadow-stone-950/20 mb-5 lg:mb-8">
                    <div class="flex flex-col md:flex-row items-start gap-5 lg:gap-8">
                        <div class="shrink-0 mx-auto md:mx-0">
                            <label class="cursor-pointer group relative block">
                                <div class="w-24 h-24 lg:w-28 lg:h-28 rounded-2xl lg:rounded-[24px] overflow-hidden bg-indigo-50 dark:bg-indigo-950/50 border-2 border-dashed border-indigo-200 dark:border-indigo-800 group-hover:border-indigo-400 dark:group-hover:border-indigo-600 transition-colors">
                                    <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex flex-col items-center justify-center gap-2">
                                        <Camera class="w-6 h-6 text-indigo-300 dark:text-indigo-600" />
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-indigo-300 dark:text-indigo-600">Upload</span>
                                    </div>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-indigo-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform">
                                    <Camera class="w-4 h-4" />
                                </div>
                                <input type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="onAvatarChange" />
                            </label>
                        </div>

                        <div class="flex-1 w-full space-y-4 lg:space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Full Name *</label>
                                    <input v-model="form.name" type="text" required
                                        class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                        placeholder="Your full name" />
                                    <p v-if="formErrors.name" class="text-sm text-red-500">{{ formErrors.name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Email</label>
                                    <input :value="user.email" type="email" readonly
                                        class="w-full px-5 py-4 bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-500 dark:text-stone-400 font-medium cursor-not-allowed" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Bio</label>
                                <textarea v-model="form.bio" rows="3"
                                    class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all resize-none"
                                    placeholder="Tell us about yourself..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-2xl lg:rounded-[32px] p-5 lg:p-10 shadow-sm dark:shadow-stone-950/20 mb-5 lg:mb-8">
                    <h2 class="text-xl lg:text-2xl font-heading font-extrabold tracking-tight mb-5 lg:mb-8 flex items-center gap-3 text-stone-800 dark:text-stone-100">
                        <div class="w-9 h-9 lg:w-10 lg:h-10 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl flex items-center justify-center">
                            <UserIcon class="w-4 h-4 lg:w-5 lg:h-5 text-indigo-500 dark:text-indigo-400" />
                        </div>
                        Personal Details
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Phone Number</label>
                            <input v-model="form.phone" type="tel"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="+91 98765 43210" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Date of Birth</label>
                            <input v-model="form.date_of_birth" type="date"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Gender</label>
                            <select v-model="form.gender"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Education & Interests -->
                <div class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-2xl lg:rounded-[32px] p-5 lg:p-10 shadow-sm dark:shadow-stone-950/20 mb-5 lg:mb-8">
                    <h2 class="text-xl lg:text-2xl font-heading font-extrabold tracking-tight mb-5 lg:mb-8 flex items-center gap-3 text-stone-800 dark:text-stone-100">
                        <div class="w-9 h-9 lg:w-10 lg:h-10 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl flex items-center justify-center">
                            <GraduationCap class="w-4 h-4 lg:w-5 lg:h-5 text-indigo-500 dark:text-indigo-400" />
                        </div>
                        Education & Interests
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5 mb-4 lg:mb-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Education Level</label>
                            <select v-model="form.education"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <option value="">Select</option>
                                <option value="High School">High School</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Bachelor's">Bachelor's Degree</option>
                                <option value="Master's">Master's Degree</option>
                                <option value="PhD">PhD</option>
                                <option value="Self-taught">Self-taught</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Institution / University</label>
                            <input v-model="form.institution" type="text"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="e.g. MIT, Stanford, IIT..." />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Interests / Skills</label>
                        <input v-model="form.interests" type="text"
                            class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                            placeholder="e.g. DevOps, Kubernetes, Cloud Computing, Python..." />
                        <p class="text-[10px] text-stone-400 dark:text-stone-500 font-medium mt-1">Separate with commas</p>
                    </div>
                </div>

                <!-- Address -->
                <div class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-2xl lg:rounded-[32px] p-5 lg:p-10 shadow-sm dark:shadow-stone-950/20 mb-5 lg:mb-8">
                    <h2 class="text-xl lg:text-2xl font-heading font-extrabold tracking-tight mb-5 lg:mb-8 flex items-center gap-3 text-stone-800 dark:text-stone-100">
                        <div class="w-9 h-9 lg:w-10 lg:h-10 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl flex items-center justify-center">
                            <MapPin class="w-4 h-4 lg:w-5 lg:h-5 text-indigo-500 dark:text-indigo-400" />
                        </div>
                        Address
                    </h2>
                    <div class="space-y-4 lg:space-y-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Street Address</label>
                            <input v-model="form.address" type="text"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="123 Main Street, Apt 4B" />
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">City</label>
                                <input v-model="form.city" type="text"
                                    class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="Mumbai" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">State</label>
                                <input v-model="form.state" type="text"
                                    class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="Maharashtra" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Country</label>
                                <input v-model="form.country" type="text"
                                    class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="India" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">Pincode</label>
                                <input v-model="form.pincode" type="text"
                                    class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="400001" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white/60 dark:bg-stone-900/60 backdrop-blur-md border border-indigo-100/20 dark:border-stone-700/50 rounded-2xl lg:rounded-[32px] p-5 lg:p-10 shadow-sm dark:shadow-stone-950/20 mb-5 lg:mb-8">
                    <h2 class="text-xl lg:text-2xl font-heading font-extrabold tracking-tight mb-5 lg:mb-8 flex items-center gap-3 text-stone-800 dark:text-stone-100">
                        <div class="w-9 h-9 lg:w-10 lg:h-10 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl flex items-center justify-center">
                            <Globe class="w-4 h-4 lg:w-5 lg:h-5 text-indigo-500 dark:text-indigo-400" />
                        </div>
                        Social Links
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">LinkedIn</label>
                            <input v-model="form.linkedin" type="text"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="linkedin.com/in/yourname" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.2em]">GitHub</label>
                            <input v-model="form.github" type="text"
                                class="w-full px-5 py-4 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-2xl text-stone-800 dark:text-stone-100 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="github.com/yourname" />
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                    <div v-if="saved" class="flex items-center gap-2 text-teal-500">
                        <CheckCircle class="w-5 h-5" />
                        <span class="text-sm font-bold">Profile saved successfully!</span>
                    </div>
                    <div v-else></div>
                    <button type="submit" :disabled="processing"
                        class="w-full sm:w-auto px-8 lg:px-12 py-3.5 lg:py-4 bg-indigo-500 text-white rounded-xl lg:rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/20 transition-all disabled:opacity-50">
                        {{ processing ? 'Saving...' : 'Save Profile' }}
                    </button>
                </div>
            </form>
        </div>
    </StudentLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Camera, UserIcon, GraduationCap, MapPin, Globe, CheckCircle } from 'lucide-vue-next';

const props = defineProps({
    user: Object,
    detail: Object,
});

const processing = ref(false);
const saved = ref(false);
const formErrors = ref({});
const avatarFile = ref(null);
const avatarPreview = ref(props.detail?.avatar || null);

const form = reactive({
    name: props.user.name,
    phone: props.detail?.phone || '',
    date_of_birth: props.detail?.date_of_birth || '',
    gender: props.detail?.gender || '',
    education: props.detail?.education || '',
    institution: props.detail?.institution || '',
    interests: props.detail?.interests || '',
    bio: props.detail?.bio || '',
    address: props.detail?.address || '',
    city: props.detail?.city || '',
    state: props.detail?.state || '',
    country: props.detail?.country || '',
    pincode: props.detail?.pincode || '',
    linkedin: props.detail?.linkedin || '',
    github: props.detail?.github || '',
});

const onAvatarChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        avatarFile.value = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
};

const submitForm = () => {
    processing.value = true;
    saved.value = false;
    formErrors.value = {};

    const formData = new FormData();
    Object.keys(form).forEach(key => {
        if (form[key] !== null && form[key] !== undefined) {
            formData.append(key, form[key]);
        }
    });

    if (avatarFile.value) {
        formData.append('avatar', avatarFile.value);
    }

    router.post('/student/profile', formData, {
        preserveScroll: true,
        onSuccess: () => {
            processing.value = false;
            saved.value = true;
            setTimeout(() => saved.value = false, 3000);
        },
        onError: (errors) => {
            formErrors.value = errors;
            processing.value = false;
        },
    });
};
</script>
