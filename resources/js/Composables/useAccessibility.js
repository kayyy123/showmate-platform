import { ref, watch } from 'vue';

const STORAGE_KEY = 'showmate-a11y-prefs';

const fontSize = ref(100);
const highContrast = ref(false);
let initialized = false;

function applyPrefs() {
    document.documentElement.style.setProperty('--a11y-font-scale', fontSize.value / 100);
    if (highContrast.value) {
        document.documentElement.setAttribute('data-a11y-high-contrast', '');
    } else {
        document.documentElement.removeAttribute('data-a11y-high-contrast');
    }
    localStorage.setItem(STORAGE_KEY, JSON.stringify({
        fontSize: fontSize.value,
        highContrast: highContrast.value,
    }));
}

export function useAccessibility() {
    if (!initialized) {
        initialized = true;
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (stored) {
                const prefs = JSON.parse(stored);
                fontSize.value = prefs.fontSize ?? 100;
                highContrast.value = prefs.highContrast ?? false;
            }
        } catch {}
        applyPrefs();
    }

    function increaseFont() {
        fontSize.value = Math.min(140, fontSize.value + 10);
        applyPrefs();
    }

    function decreaseFont() {
        fontSize.value = Math.max(80, fontSize.value - 10);
        applyPrefs();
    }

    function resetFont() {
        fontSize.value = 100;
        applyPrefs();
    }

    function toggleHighContrast() {
        highContrast.value = !highContrast.value;
        applyPrefs();
    }

    return {
        fontSize,
        highContrast,
        increaseFont,
        decreaseFont,
        resetFont,
        toggleHighContrast,
    };
}
