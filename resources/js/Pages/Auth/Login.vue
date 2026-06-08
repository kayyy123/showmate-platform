<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GoogleIcon from '@/Components/GoogleIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk" />

    <div class="min-h-screen bg-[#0a0a0b] flex flex-col">
        <!-- Header -->
        <header class="flex items-center justify-between px-6 lg:px-12 py-4 shrink-0">
            <img
                src="/images/logo-etalaseku-seller.png"
                alt="EtalaseKu"
                class="h-8 w-auto"
            />
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
        <main class="flex-1 flex flex-col lg:flex-row items-stretch">
            <!-- Left: poster -->
            <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-4">
                <div class="max-h-[75vh] max-w-full rounded-2xl bg-[#1e0a3c] overflow-hidden shadow-lg flex items-center justify-center" style="aspect-ratio: 1295 / 1823">
                    <img
                        src="/images/login.png"
                        alt="Ilustrasi aksesibilitas EtalaseKu untuk login"
                        class="w-full h-full object-contain"
                    />
                </div>
            </div>

            <!-- Right: form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-8 lg:py-0">
                <div class="w-full max-w-[420px]">
                    <!-- Heading -->
                    <h1 class="text-2xl font-bold text-white">Selamat Datang Kembali</h1>
                    <p class="text-sm text-zinc-400 mt-2 mb-8">
                        Belum punya akun?
                        <Link
                            :href="route('register')"
                            class="font-semibold text-yellow-400 hover:text-yellow-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded"
                        >
                            Daftar di sini
                        </Link>
                    </p>

                    <!-- Status message -->
                    <div v-if="status" class="mb-6 text-sm font-medium text-emerald-400" role="status">
                        {{ status }}
                    </div>

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
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" class="text-zinc-300" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1.5 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="text-sm text-zinc-400">Ingat saya</span>
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm font-medium text-yellow-400 hover:text-yellow-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded"
                            >
                                Lupa password?
                            </Link>
                        </div>

                        <PrimaryButton
                            class="w-full justify-center !bg-yellow-400 !text-black hover:!bg-yellow-300"
                            :class="{ 'opacity-50': form.processing }"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Memproses...' : 'Masuk' }}
                        </PrimaryButton>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-zinc-700" />
                        </div>
                        <div class="relative flex justify-center text-xs uppercase tracking-wider">
                            <span class="bg-[#0a0a0b] px-3 text-zinc-500">atau masuk dengan</span>
                        </div>
                    </div>

                    <!-- Google button -->
                    <a
                        :href="route('auth.google')"
                        class="w-full inline-flex items-center justify-center gap-3 px-4 py-2.5 border border-zinc-700 rounded-lg text-sm font-medium text-zinc-300 bg-transparent hover:bg-zinc-800/50 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        <GoogleIcon class="w-5 h-5 shrink-0" />
                        Masuk dengan Google
                    </a>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="shrink-0 px-6 lg:px-12 py-4 border-t border-zinc-800">
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-1 text-xs text-zinc-500">
                <span class="font-medium text-zinc-400">EtalaseKu</span>
                <a href="#" class="hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded">Syarat Layanan</a>
                <a href="#" class="hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded">Kebijakan Privasi</a>
                <a href="#" class="hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded">Pusat Bantuan</a>
            </div>
        </footer>
    </div>
</template>
