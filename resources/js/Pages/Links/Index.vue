<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { confirmDelete } from '@/Composables/useConfirm.js';

const props = defineProps({
    links: Array,
});

const page = usePage();
const catalogUrl = `/catalog/${page.props.auth.user.slug}`;

const showModal = ref(false);
const editingLink = ref(null);


const form = useForm({
    title: '',
    url: '',
    icon: '',
});

const iconOptions = [
    { value: '', label: 'Otomatis (deteksi dari URL)' },
    { value: 'whatsapp', label: 'WhatsApp' },
    { value: 'instagram', label: 'Instagram' },
    { value: 'tiktok', label: 'TikTok' },
    { value: 'shopee', label: 'Shopee' },
    { value: 'tokopedia', label: 'Tokopedia' },
    { value: 'globe', label: 'Website / Lainnya' },
];

function detectIcon(url, icon) {
    if (icon) return icon;
    if (!url) return 'globe';
    const u = url.toLowerCase();
    if (u.includes('wa.me') || u.includes('whatsapp')) return 'whatsapp';
    if (u.includes('instagram')) return 'instagram';
    if (u.includes('tiktok')) return 'tiktok';
    if (u.includes('shopee')) return 'shopee';
    if (u.includes('tokopedia')) return 'tokopedia';
    if (u.includes('youtube') || u.includes('youtu.be')) return 'youtube';
    if (u.includes('facebook') || u.includes('fb.com')) return 'facebook';
    return 'globe';
}

function getIconForLink(link) {
    return detectIcon(link.url, link.icon);
}

const sortedLinks = computed(() => {
    return [...props.links].sort((a, b) => a.sort_order - b.sort_order);
});

function openAddModal() {
    editingLink.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
}

function openEditModal(link) {
    editingLink.value = link;
    form.clearErrors();
    form.title = link.title;
    form.url = link.url;
    form.icon = link.icon || '';
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    editingLink.value = null;
    form.reset();
    form.clearErrors();
}

function submit() {
    if (editingLink.value) {
        form.put(route('links.update', editingLink.value.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('links.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
}

async function destroyLink(link) {
    const confirmed = await confirmDelete();
    if (confirmed) {
        router.delete(route('links.destroy', link.id), {
            preserveScroll: true,
        });
    }
}

function toggleActive(link) {
    router.post(route('links.toggle-active', link.id), {
        preserveScroll: true,
    });
}

function moveUp(link) {
    const sorted = sortedLinks.value;
    const idx = sorted.findIndex(l => l.id === link.id);
    if (idx <= 0) return;
    const above = sorted[idx - 1];
    router.post(route('links.reorder'), {
        links: [
            { id: link.id, sort_order: above.sort_order },
            { id: above.id, sort_order: link.sort_order },
        ],
    }, { preserveScroll: true });
}

function moveDown(link) {
    const sorted = sortedLinks.value;
    const idx = sorted.findIndex(l => l.id === link.id);
    if (idx < 0 || idx >= sorted.length - 1) return;
    const below = sorted[idx + 1];
    router.post(route('links.reorder'), {
        links: [
            { id: link.id, sort_order: below.sort_order },
            { id: below.id, sort_order: link.sort_order },
        ],
    }, { preserveScroll: true });
}

// Preview channels computed from links
const previewChannels = computed(() => {
    return sortedLinks.value.filter(l => l.is_active).map(l => ({
        title: l.title,
        icon: getIconForLink(l),
    }));
});

function formatUrlDisplay(url) {
    if (!url) return '';
    try {
        const u = new URL(url);
        return u.hostname + u.pathname.substring(0, 30) + (u.pathname.length > 30 ? '...' : '');
    } catch {
        return url.substring(0, 40);
    }
}
</script>

<template>
    <Head title="Tautan Saya" />

    <DashboardLayout activeTab="links">
        <template #header>Tautan Saya</template>

        <div class="space-y-6 max-w-3xl">

            <!-- Action bar -->
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-on-surface-variant">
                        {{ sortedLinks.length }} tautan
                        <span v-if="sortedLinks.length">(&#43;{{ sortedLinks.filter(l => !l.is_active).length }} tidak aktif)</span>
                    </p>
                </div>
                <button
                    @click="openAddModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Tautan
                </button>
            </div>

            <!-- Empty state -->
            <div
                v-if="sortedLinks.length === 0"
                class="rounded-2xl border-2 border-dashed border-outline p-12 text-center"
            >
                <svg class="w-16 h-16 mx-auto mb-4 text-on-surface-variant/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <h3 class="text-lg font-bold text-on-surface mb-1">Belum ada tautan</h3>
                <p class="text-sm text-on-surface-variant mb-4">Tambahkan tautan website, marketplace, atau media sosial Anda</p>
                <button
                    @click="openAddModal"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold hover:brightness-110 active:scale-[0.98] transition-all"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Tautan Baru
                </button>
            </div>

            <!-- Links list -->
            <div v-else class="space-y-2">
                <div
                    v-for="(link, index) in sortedLinks"
                    :key="link.id"
                    class="rounded-2xl border border-outline bg-surface p-4 flex items-center gap-3 transition-all hover:border-primary/30"
                    :class="{ 'opacity-50': !link.is_active }"
                >
                    <!-- Sort buttons -->
                    <div class="flex flex-col gap-0.5 shrink-0">
                        <button
                            @click="moveUp(link)"
                            :disabled="index === 0"
                            class="w-6 h-5 rounded flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors disabled:opacity-20 disabled:cursor-not-allowed"
                            aria-label="Pindah ke atas"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                        <button
                            @click="moveDown(link)"
                            :disabled="index === sortedLinks.length - 1"
                            class="w-6 h-5 rounded flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors disabled:opacity-20 disabled:cursor-not-allowed"
                            aria-label="Pindah ke bawah"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Icon -->
                    <div
                        class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center"
                        :class="link.is_active ? 'bg-primary/10 text-primary' : 'bg-surface-container text-on-surface-variant'"
                    >
                        <svg v-if="getIconForLink(link) === 'whatsapp'" class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <svg v-else-if="getIconForLink(link) === 'instagram'" class="w-5 h-5 text-pink-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/>
                        </svg>
                        <svg v-else-if="getIconForLink(link) === 'tiktok'" class="w-5 h-5 text-gray-900 dark:text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                        <svg v-else-if="getIconForLink(link) === 'shopee'" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12.912 2.904c.103.003.206.008.308.017 2.21.18 4.042 1.557 4.904 3.489.327.732.477 1.467.494 2.201.02.852-.126 1.598-.42 2.28-.138.319-.404.576-.597.857a.963.963 0 01-.12.139c-.488.487-1.046.8-1.677.982-.625.18-1.27.23-1.905.107-.554-.107-1.064-.334-1.472-.718-.238-.225-.428-.49-.55-.795a5.27 5.27 0 01-.218-.541c-.146-.43-.457-.657-.892-.66-.528-.003-.905.27-1.067.775l-.003.012c-.145.448-.159.895-.002 1.343.117.334.303.611.59.832.632.487 1.38.699 2.148.714.588.012 1.158-.079 1.715-.278.537-.192 1.043-.457 1.501-.8.144-.108.278-.23.41-.353v.006c.18.111.343.24.483.395.567.627.836 1.366.877 2.218.044.922-.13 1.792-.574 2.58-.375.666-.906 1.18-1.553 1.575-.745.455-1.571.685-2.454.721-.855.035-1.677-.09-2.455-.402-.598-.24-1.116-.59-1.535-1.078-.375-.437-.63-.946-.775-1.518-.158-.625-.127-1.25-.018-1.88.174-1.002.63-1.857 1.3-2.58.664-.718 1.487-1.193 2.44-1.465.07-.02.141-.04.212-.057-.136-.096-.25-.216-.355-.347-.63-.79-.862-1.699-.701-2.699.178-1.106.771-1.98 1.66-2.66.315-.24.667-.418 1.047-.532.33-.1.668-.144 1.01-.137z"/>
                        </svg>
                        <svg v-else-if="getIconForLink(link) === 'tokopedia'" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M5.763 17.487c.003-.05.003-.1.004-.149.008-.828.012-1.659.022-2.487.005-.397-.023-.656-.377-.88-.663-.421-1.324-.846-1.987-1.267-.36-.228-.542-.547-.54-.99.002-.454.178-.78.545-1.01.658-.411 1.321-.814 1.982-1.22.336-.207.372-.486.37-.842-.011-1.101-.005-2.202-.005-3.303H5.761c-.159 0-.315-.013-.466.024-.728.179-1.312-.08-1.59-.759a1.36 1.36 0 01.136-1.282c.243-.37.6-.542 1.03-.523.422.018.836.14 1.215.330.206.104.32.065.433-.112.187-.292.378-.581.567-.872.004-.006.009-.011.014-.017h5.362c.005.006.01.011.014.017.189.291.38.58.567.872.113.177.227.216.433.112.379-.19.793-.312 1.215-.33.43-.019.787.153 1.03.523.238.361.282.792.136 1.282-.278.68-.862.938-1.59.759-.15-.037-.307-.024-.466-.024h-.002c-.002 0-.004.002-.007.005v4.678c-.003.393-.06.737-.455.97-.672.395-1.354.774-2.031 1.16-.348.2-.54.54-.532.937.01.582.004 1.164.004 1.746v2.594c.002.15.014.298.04.444.097.547.024.905-.48 1.207-.294.176-.607.282-.95.289-.407.008-.73-.132-1.007-.43-.301-.324-.385-.72-.237-1.138a1.611 1.611 0 01.275-.487c.207-.254.198-.537.194-.833-.003-.392-.005-.784.003-1.176.001-.061.008-.122.012-.182z"/>
                        </svg>
                        <svg v-else-if="getIconForLink(link) === 'youtube'" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        <svg v-else-if="getIconForLink(link) === 'facebook'" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-on-surface truncate">{{ link.title }}</p>
                        <p class="text-xs text-on-surface-variant truncate">{{ formatUrlDisplay(link.url) }}</p>
                    </div>

                    <!-- Toggle -->
                    <button
                        @click="toggleActive(link)"
                        class="relative w-11 h-6 rounded-full transition-colors shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        :class="link.is_active ? 'bg-primary' : 'bg-outline'"
                        :aria-label="link.is_active ? 'Sembunyikan tautan' : 'Tampilkan tautan'"
                        role="switch"
                        :aria-checked="link.is_active"
                    >
                        <span
                            class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"
                            :class="link.is_active ? 'translate-x-5' : 'translate-x-0'"
                        />
                    </button>

                    <!-- Edit -->
                    <button
                        @click="openEditModal(link)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors shrink-0"
                        aria-label="Edit tautan"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>

                    <!-- Delete -->
                    <button
                        @click="destroyLink(link)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-red-500/10 hover:text-red-500 transition-colors shrink-0"
                        aria-label="Hapus tautan"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Preview section -->
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <h2 class="font-bold text-lg text-on-surface">Pratinjau Tautan Publik</h2>
                    </div>
                    <a
                        :href="catalogUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary/80 transition-colors"
                    >
                        Buka halaman
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5">
                    <div class="text-center mb-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Tautan akan muncul di halaman katalog publik sebagai:</p>
                    </div>
                    <div v-if="previewChannels.length === 0" class="text-center py-6">
                        <svg class="w-10 h-10 mx-auto mb-2 text-on-surface-variant/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <p class="text-sm text-on-surface-variant">Aktifkan tautan untuk melihat pratinjau</p>
                    </div>
                    <div v-else class="flex flex-wrap gap-2 justify-center">
                        <span
                            v-for="ch in previewChannels"
                            :key="ch.title"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-outline text-sm font-medium text-on-surface bg-surface-container-low"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            {{ ch.title }}
                        </span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Add / Edit Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition-all duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-all duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showModal" class="fixed inset-0 z-50 bg-on-surface/50" @click="closeModal" />
            </Transition>
            <Transition
                enter-active-class="transition-all duration-200"
                enter-from-class="opacity-0 scale-95 -translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition-all duration-150"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 -translate-y-4"
            >
                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @click.self="closeModal"
                >
                    <div
                        class="w-full max-w-md bg-surface rounded-2xl border border-outline shadow-xl overflow-hidden"
                        role="dialog"
                        aria-modal="true"
                        :aria-label="editingLink ? 'Edit Tautan' : 'Tambah Tautan Baru'"
                    >
                        <div class="p-5 border-b border-outline">
                            <h3 class="text-lg font-bold text-on-surface" :id="editingLink ? 'edit-link-title' : 'add-link-title'">
                                {{ editingLink ? 'Edit Tautan' : 'Tambah Tautan Baru' }}
                            </h3>
                        </div>
                        <form @submit.prevent="submit" class="p-5 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="link_title">Judul Tautan</label>
                                <input
                                    id="link_title"
                                    v-model="form.title"
                                    type="text"
                                    required
                                    placeholder="Contoh: Tokopedia Saya"
                                    class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                                />
                                <p v-if="form.errors.title" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.title }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="link_url">URL Tautan</label>
                                <input
                                    id="link_url"
                                    v-model="form.url"
                                    type="url"
                                    required
                                    placeholder="https://tokopedia.com/username"
                                    class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                                />
                                <p v-if="form.errors.url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.url }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="link_icon">Ikon (opsional)</label>
                                <select
                                    id="link_icon"
                                    v-model="form.icon"
                                    class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors appearance-none"
                                >
                                    <option
                                        v-for="opt in iconOptions"
                                        :key="opt.value"
                                        :value="opt.value"
                                        class="bg-surface text-on-surface"
                                    >
                                        {{ opt.label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.icon" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.icon }}</p>
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="flex-1 px-4 py-3 rounded-xl border border-outline text-on-surface font-medium text-sm hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="flex-1 px-4 py-3 rounded-xl bg-primary text-on-primary text-sm font-bold flex items-center justify-center gap-2 hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </DashboardLayout>
</template>
