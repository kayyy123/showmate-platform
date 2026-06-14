<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Password" />

    <div class="min-h-screen bg-[#0a0a0b] flex flex-col">
        <!-- Header -->
        <header class="flex items-center justify-between px-6 lg:px-12 py-4 shrink-0">
            <a
                href="/"
                class="flex items-center gap-2 hover:opacity-90 transition-opacity"
            >
                <img src="/images/image4-removebg-preview.png" alt="Logo EtalaseKu" class="h-10 md:h-12" />
                <span class="text-2xl lg:text-3xl font-extrabold tracking-tight"><span class="text-white">Etalase</span><span class="text-[#FFD700]">Ku</span></span>
            </a>
            <a
                href="/"
                class="inline-flex items-center gap-1.5 text-sm text-zinc-400 hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded-md px-2 py-1"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke website
            </a>
        </header>

        <!-- Main content -->
        <main class="flex-1 flex flex-col lg:flex-row items-center px-6 lg:px-12">
            <!-- Left: poster -->
            <div class="hidden lg:flex lg:w-[55%] items-center justify-center py-4">
                <div class="max-h-[78vh] max-w-full rounded-2xl bg-[#1e0a3c] overflow-hidden shadow-lg flex items-center justify-center" style="aspect-ratio: 4 / 3">
                    <img
                        src="/images/login.png"
                        alt="Ilustrasi aksesibilitas EtalaseKu untuk reset password"
                        class="w-full h-full object-contain"
                    />
                </div>
            </div>

            <!-- Right: form -->
            <div class="w-full lg:w-[45%] flex items-center justify-center py-8 lg:py-0">
                <div class="w-full max-w-[420px]">
                    <!-- Heading -->
                    <h1 class="text-2xl font-bold text-white">Reset Password</h1>
                    <p class="text-sm text-zinc-400 mt-2 mb-8 leading-relaxed">
                        Buat password baru untuk akun EtalaseKu Anda.
                    </p>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <InputLabel for="email" value="Email" class="text-zinc-300" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1.5 block w-full"
                                v-model="form.email"
                                required
                                readonly
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password Baru" class="text-zinc-300" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1.5 block w-full"
                                v-model="form.password"
                                required
                                autofocus
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Konfirmasi Password" class="text-zinc-300" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1.5 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <PrimaryButton
                            class="w-full justify-center !bg-yellow-400 !text-black hover:!bg-yellow-300"
                            :class="{ 'opacity-50': form.processing }"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Password Baru' }}
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="shrink-0 px-6 lg:px-12 py-4 border-t border-zinc-800">
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-1 text-xs text-zinc-500">
                <span class="inline-flex items-center gap-1.5 font-medium text-zinc-400">
                    <img src="/images/image4-removebg-preview.png" alt="Logo EtalaseKu" class="h-5" />
                    EtalaseKu
                </span>
                <a href="#" class="hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded">Syarat Layanan</a>
                <a href="#" class="hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded">Kebijakan Privasi</a>
                <a href="#" class="hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded">Pusat Bantuan</a>
            </div>
        </footer>
    </div>
</template>
