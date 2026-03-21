import { ref, watch, onMounted } from 'vue';

const isDark = ref(false);

export function useDarkMode() {
    const toggle = () => {
        isDark.value = !isDark.value;
    };

    const apply = () => {
        if (isDark.value) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('lms-dark-mode', isDark.value ? 'true' : 'false');
    };

    const init = () => {
        const stored = localStorage.getItem('lms-dark-mode');
        if (stored !== null) {
            isDark.value = stored === 'true';
        } else {
            isDark.value = false;
        }
        apply();
    };

    watch(isDark, apply);

    onMounted(init);

    return { isDark, toggle };
}
