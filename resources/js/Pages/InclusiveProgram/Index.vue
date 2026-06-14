<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    application: Object,
});

const page = usePage();
const status = computed(() => props.application?.status || null);
const hasApplied = computed(() => !!props.application);
const isPending = computed(() => status.value === 'pending');
const isApproved = computed(() => status.value === 'approved');
const isRejected = computed(() => status.value === 'rejected');

const showForm = ref(false);
const form = ref({
    program_types: [],
    other_program_type: '',
    description: '',
    supporting_file: null,
    supporting_link: '',
    agreed: false,
});
const errors = ref({});
const submitting = ref(false);

const programTypeOptions = [
    { value: 'owned_by_disabled', label: 'Usaha dimiliki oleh penyandang disabilitas' },
    { value: 'employs_disabled', label: 'Mempekerjakan penyandang disabilitas' },
    { value: 'sells_inclusive_products', label: 'Menjual produk ramah disabilitas' },
    { value: 'accessibility_services', label: 'Menyediakan layanan aksesibilitas' },
    { value: 'community_empowerment', label: 'Komunitas/yayasan pemberdayaan disabilitas' },
    { value: 'other', label: 'Lainnya' },
];

const hasOther = computed(() => form.value.program_types.includes('other'));

function toggleType(value) {
    const idx = form.value.program_types.indexOf(value);
    if (idx === -1) {
        form.value.program_types.push(value);
    } else {
        form.value.program_types.splice(idx, 1);
    }
}

function handleFileUpload(e) {
    form.value.supporting_file = e.target.files[0] || null;
}

function openForm() {
    showForm.value = true;
    errors.value = {};
}

function closeForm() {
    showForm.value = false;
    resetForm();
}

function resetForm() {
    form.value = {
        program_types: [],
        other_program_type: '',
        description: '',
        supporting_file: null,
        supporting_link: '',
        agreed: false,
    };
    errors.value = {};
}

function validate() {
    const e = {};
    if (form.value.program_types.length === 0) {
        e.program_types = 'Pilih minimal satu jenis program inklusif.';
    }
    if (hasOther.value && !form.value.other_program_type.trim()) {
        e.other_program_type = 'Jelaskan jenis program lainnya.';
    }
    if (!form.value.description.trim()) {
        e.description = 'Deskripsi program wajib diisi.';
    }
    if (form.value.supporting_link && !form.value.supporting_link.match(/^https?:\/\/.+/)) {
        e.supporting_link = 'Link harus berupa URL yang valid (mulai dengan http:// atau https://).';
    }
    if (!form.value.agreed) {
        e.agreed = 'Anda harus menyetujui pernyataan ini.';
    }
    errors.value = e;
    return Object.keys(e).length === 0;
}

function submit() {
    if (!validate()) return;
    submitting.value = true;

    const data = new FormData();
    form.value.program_types.forEach(t => data.append('program_types[]', t));
    if (hasOther.value) data.append('other_program_type', form.value.other_program_type);
    data.append('description', form.value.description);
    if (form.value.supporting_file) data.append('supporting_file', form.value.supporting_file);
    if (form.value.supporting_link) data.append('supporting_link', form.value.supporting_link);
    data.append('agreed', form.value.agreed ? '1' : '0');

    router.post(route('inclusive-program.store'), data, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeForm();
        },
        onError: (err) => {
            errors.value = err;
            submitting.value = false;
        },
        onFinish: () => {
            submitting.value = false;
        },
    });
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
}
</script>

<template>
    <DashboardLayout>
        <template #header>
            Program Inklusif
        </template>

        <!-- Flash messages -->
        <div v-if="page.props.flash?.success" class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium" role="alert">
            {{ page.props.flash.success }}
        </div>
        <div v-if="page.props.errors?.message" class="mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-medium" role="alert">
            {{ page.props.errors.message }}
        </div>

        <!-- Not Applied State -->
        <div v-if="!hasApplied && !showForm" class="max-w-xl mx-auto">
            <div class="rounded-2xl border border-outline bg-surface p-8 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-primary/10 flex items-center justify-center text-2xl" aria-hidden="true">
                    ♿
                </div>
                <h1 class="text-xl font-bold text-on-surface">Program Inklusif</h1>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    Tunjukkan komitmen usaha Anda terhadap aksesibilitas dan pemberdayaan penyandang disabilitas.
                    Merchant yang disetujui akan mendapatkan badge <strong>UMKM Inklusif</strong> pada katalog publik.
                </p>
                <button
                    @click="openForm"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-on-primary font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    Ajukan Program Inklusif
                </button>
            </div>
        </div>

        <!-- Pending State -->
        <div v-else-if="isPending" class="max-w-xl mx-auto">
            <div class="rounded-2xl border border-outline bg-surface p-8 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-yellow-500/10 flex items-center justify-center text-2xl" aria-hidden="true">
                    ⏳
                </div>
                <h1 class="text-xl font-bold text-on-surface">Program Inklusif Sedang Ditinjau</h1>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    Pengajuan Program Inklusif Anda telah diterima dan sedang ditinjau oleh tim EtalaseKu.
                    Kami akan memberi tahu Anda setelah proses review selesai.
                </p>
                <div class="flex items-center justify-center gap-2 text-xs text-on-surface-variant">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse" aria-hidden="true" />
                        Menunggu Review
                    </span>
                </div>
                <p class="text-xs text-on-surface-variant">Diajukan pada: {{ formatDate(props.application?.created_at) }}</p>
            </div>
        </div>

        <!-- Approved State -->
        <div v-else-if="isApproved" class="max-w-xl mx-auto">
            <div class="rounded-2xl border border-emerald-500/30 bg-surface p-8 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-500/10 flex items-center justify-center text-3xl" aria-hidden="true">
                    ♿
                </div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-sm font-bold">
                    UMKM Inklusif
                </div>
                <h1 class="text-xl font-bold text-on-surface">Program Inklusif Disetujui</h1>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    Selamat! Usaha Anda telah terdaftar sebagai bagian dari Program Inklusif EtalaseKu.
                    Badge <strong>UMKM Inklusif</strong> sekarang muncul di katalog publik Anda.
                </p>
                <div class="pt-2">
                    <a
                        v-if="props.application?.user?.store_slug"
                        :href="route('catalog.show', props.application.user.store_slug)"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                    >
                        Lihat Katalog Saya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Rejected State -->
        <div v-else-if="isRejected && !showForm" class="max-w-xl mx-auto">
            <div class="rounded-2xl border border-red-500/30 bg-surface p-8 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-red-500/10 flex items-center justify-center text-2xl" aria-hidden="true">
                    ❌
                </div>
                <h1 class="text-xl font-bold text-on-surface">Pengajuan Program Inklusif Ditolak</h1>
                <div v-if="props.application?.rejection_reason" class="p-4 rounded-xl bg-red-500/5 border border-red-500/20 text-left">
                    <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1">Alasan Penolakan</p>
                    <p class="text-sm text-on-surface">{{ props.application.rejection_reason }}</p>
                </div>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    Anda dapat mengajukan ulang dengan melengkapi informasi yang diperlukan.
                </p>
                <button
                    @click="openForm"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-on-primary font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    Ajukan Ulang
                </button>
            </div>
        </div>

        <!-- Application Form -->
        <div v-if="showForm" class="max-w-2xl mx-auto">
            <div class="rounded-2xl border border-outline bg-surface p-6 md:p-8 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-on-surface">Ajukan Program Inklusif</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Lengkapi data berikut untuk mendaftarkan usaha Anda ke Program Inklusif EtalaseKu.</p>
                </div>

                <!-- Bagian 1: Jenis Program -->
                <div class="space-y-3">
                    <fieldset>
                        <legend class="text-sm font-semibold text-on-surface mb-2">
                            Jenis Program Inklusif <span class="text-red-400" aria-label="wajib">*</span>
                        </legend>
                        <p v-if="errors.program_types" class="text-xs text-red-400 mb-2" role="alert">{{ errors.program_types }}</p>
                        <div class="space-y-2">
                            <label
                                v-for="opt in programTypeOptions"
                                :key="opt.value"
                                class="flex items-start gap-3 p-3 rounded-xl cursor-pointer transition-colors"
                                :class="form.program_types.includes(opt.value) ? 'bg-primary/10 border border-primary/30' : 'bg-surface-container border border-transparent hover:bg-surface-container-high'"
                            >
                                <input
                                    type="checkbox"
                                    :checked="form.program_types.includes(opt.value)"
                                    @change="toggleType(opt.value)"
                                    class="mt-0.5 w-4 h-4 rounded border-outline text-primary focus:ring-primary/30 focus:ring-offset-0"
                                />
                                <span class="text-sm text-on-surface select-none">{{ opt.label }}</span>
                            </label>
                        </div>
                        <div v-if="hasOther" class="mt-3">
                            <label for="other_program_type" class="block text-sm font-medium text-on-surface mb-1">
                                Jelaskan program lainnya <span class="text-red-400" aria-label="wajib">*</span>
                            </label>
                            <input
                                id="other_program_type"
                                v-model="form.other_program_type"
                                type="text"
                                placeholder="Sebutkan program inklusif yang Anda jalankan..."
                                class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                            />
                            <p v-if="errors.other_program_type" class="mt-1 text-xs text-red-400" role="alert">{{ errors.other_program_type }}</p>
                        </div>
                    </fieldset>
                </div>

                <!-- Bagian 2: Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-on-surface mb-1">
                        Deskripsi Program <span class="text-red-400" aria-label="wajib">*</span>
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        placeholder="Jelaskan bagaimana usaha Anda mendukung aksesibilitas atau pemberdayaan penyandang disabilitas..."
                        class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none"
                    ></textarea>
                    <p v-if="errors.description" class="mt-1 text-xs text-red-400" role="alert">{{ errors.description }}</p>
                </div>

                <!-- Bagian 3: Bukti Pendukung -->
                <div class="space-y-4">
                    <div>
                        <label for="supporting_file" class="block text-sm font-semibold text-on-surface mb-1">
                            Bukti Pendukung <span class="text-xs text-on-surface-variant font-normal">(opsional)</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-container border border-outline text-on-surface text-sm font-medium cursor-pointer hover:bg-surface-container-high transition-colors focus-within:ring-2 focus-within:ring-primary/50">
                                <svg class="w-4 h-4 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Pilih File
                                <input
                                    id="supporting_file"
                                    type="file"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                    @change="handleFileUpload"
                                    class="sr-only"
                                />
                            </label>
                            <span v-if="form.supporting_file" class="text-sm text-on-surface-variant truncate">{{ form.supporting_file.name }}</span>
                        </div>
                        <p class="text-xs text-on-surface-variant mt-1">Format: JPG, PNG, PDF, DOC. Maksimal 5MB. Opsional, tapi disarankan.</p>
                        <p v-if="errors.supporting_file" class="mt-1 text-xs text-red-400" role="alert">{{ errors.supporting_file }}</p>
                    </div>
                    <div>
                        <label for="supporting_link" class="block text-sm font-semibold text-on-surface mb-1">
                            Link Pendukung <span class="text-xs text-on-surface-variant font-normal">(opsional)</span>
                        </label>
                        <input
                            id="supporting_link"
                            v-model="form.supporting_link"
                            type="url"
                            placeholder="https://www.instagram.com/... atau https://website-anda.com"
                            class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                        />
                        <p v-if="errors.supporting_link" class="mt-1 text-xs text-red-400" role="alert">{{ errors.supporting_link }}</p>
                    </div>
                </div>

                <!-- Bagian 4: Pernyataan -->
                <div class="p-4 rounded-xl bg-surface-container border border-outline">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            v-model="form.agreed"
                            class="mt-0.5 w-4 h-4 rounded border-outline text-primary focus:ring-primary/30 focus:ring-offset-0"
                        />
                        <span class="text-sm text-on-surface">
                            Saya menyatakan informasi yang diberikan benar dan dapat dipertanggungjawabkan.
                            <span class="text-red-400" aria-label="wajib">*</span>
                        </span>
                    </label>
                    <p v-if="errors.agreed" class="mt-1 text-xs text-red-400 ml-7" role="alert">{{ errors.agreed }}</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <button
                        type="button"
                        @click="closeForm"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-outline text-on-surface font-medium hover:bg-surface-container-high transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        @click="submit"
                        :disabled="submitting"
                        class="flex-[2] px-4 py-2.5 rounded-xl bg-primary text-on-primary font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ submitting ? 'Mengirim...' : 'Kirim Pengajuan' }}
                    </button>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
