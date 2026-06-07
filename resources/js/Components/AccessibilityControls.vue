<script setup>
import { ref, onMounted, watch } from 'vue';

const STORAGE_KEY = 'showmate_accessibility';

const fontSize = ref('normal');
const highContrast = ref(false);

function loadPreferences() {
    try {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            const parsed = JSON.parse(saved);
            fontSize.value = parsed.fontSize || 'normal';
            highContrast.value = parsed.highContrast || false;
        }
    } catch {
    }
}

function savePreferences() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify({
        fontSize: fontSize.value,
        highContrast: highContrast.value,
    }));
}

function setFontSize(size) {
    fontSize.value = size;
    if (size === 'large') {
        document.documentElement.style.fontSize = '20px';
    } else {
        document.documentElement.style.fontSize = '';
    }
}

function toggleHighContrast() {
    highContrast.value = !highContrast.value;
    document.documentElement.classList.toggle('high-contrast', highContrast.value);
}

const emit = defineEmits(['close']);

watch(fontSize, () => {
    setFontSize(fontSize.value);
    savePreferences();
});

watch(highContrast, () => {
    savePreferences();
});

onMounted(() => {
    loadPreferences();
    setFontSize(fontSize.value);
    if (highContrast.value) {
        document.documentElement.classList.add('high-contrast');
    }
});
</script>

<template>
    <div
        class="bg-surface-container border border-outline rounded-2xl p-6 space-y-6"
        role="dialog"
        aria-label="Pengaturan aksesibilitas"
    >
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-on-surface">Aksesibilitas</h2>
            <button
                @click="emit('close')"
                :aria-label="'Tutup pengaturan'"
                class="flex items-center justify-center w-11 h-11 rounded-lg hover:bg-surface-container-high focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent transition-colors"
            >
                <svg class="w-5 h-5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider">
                Ukuran Font
            </label>
            <div class="flex gap-3">
                <button
                    @click="setFontSize('normal')"
                    :aria-pressed="fontSize === 'normal'"
                    :aria-label="'Ukuran font normal'"
                    class="flex-1 py-3 px-4 rounded-lg font-bold text-sm transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                    :class="fontSize === 'normal' ? 'bg-accent text-on-accent' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'"
                >
                    Normal
                </button>
                <button
                    @click="setFontSize('large')"
                    :aria-pressed="fontSize === 'large'"
                    :aria-label="'Ukuran font besar'"
                    class="flex-1 py-3 px-4 rounded-lg font-bold text-lg transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                    :class="fontSize === 'large' ? 'bg-accent text-on-accent' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'"
                >
                    Besar
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">
                Kontras Tinggi
            </span>
            <button
                @click="toggleHighContrast"
                :aria-pressed="highContrast"
                :aria-label="'Toggle kontras tinggi'"
                role="switch"
                class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                :class="highContrast ? 'bg-accent' : 'bg-surface-container-high'"
            >
                <span
                    class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform"
                    :class="highContrast ? 'translate-x-6' : 'translate-x-1'"
                />
            </button>
        </div>
    </div>
</template>

<style scoped>
.high-contrast :deep(*) {
    --on-surface: #FFFFFF;
    --on-surface-variant: #FFFFFF;
    --outline: #FFFFFF;
}
</style>
