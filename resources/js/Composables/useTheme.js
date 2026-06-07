import { ref } from 'vue';

const STORAGE_KEY = 'showmate-theme';

const isDark = ref(true);
let initialized = false;

function applyTheme(dark) {
    if (dark) {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem(STORAGE_KEY, 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem(STORAGE_KEY, 'light');
    }
    isDark.value = dark;
}

export function useTheme() {
    if (!initialized) {
        initialized = true;
        const stored = localStorage.getItem(STORAGE_KEY);
        const dark = stored ? stored === 'dark' : true;
        isDark.value = dark;
        applyTheme(dark);
    }

    function toggleTheme() {
        applyTheme(!isDark.value);
    }

    return {
        isDark,
        toggleTheme,
    };
}
