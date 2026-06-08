<script setup>
import GoogleIcon from '@/Components/GoogleIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar" />

    <div class="min-h-screen flex flex-col md:flex-row bg-surface">
        <!-- Mobile poster (top) -->
        <div class="md:hidden relative w-full h-48 shrink-0">
            <img
                src="/images/register.png"
                alt="Ilustrasi daftar EtalaseKu"
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-surface" />
        </div>

        <!-- Right panel (poster) — visually on right, but in DOM before form so tab order reaches form first on mobile -->
        <div class="hidden md:block md:w-1/2 lg:w-1/2 xl:w-1/2 sticky top-0 h-screen order-last">
            <img
                src="/images/register.png"
                alt="Ilustrasi daftar EtalaseKu"
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-l from-transparent via-transparent to-surface/10" />
        </div>

        <!-- Left panel (form) -->
        <div class="flex-1 flex items-center justify-center px-6 py-10 md:py-0">
            <div class="w-full max-w-sm">
                <!-- Logo -->
                <img
                    src="/images/logo-etalaseku.png"
                    alt="EtalaseKu"
                    class="h-10 w-auto mb-8"
                />

                <!-- Heading -->
                <h1 class="text-2xl font-bold text-on-surface">Buat akun baru</h1>
                <p class="text-sm text-on-surface-variant mt-1 mb-8">Bergabunglah dengan EtalaseKu dan kembangkan bisnis Anda</p>

                <!-- Google button -->
                <a
                    :href="route('auth.google')"
                    class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-outline rounded-lg text-sm font-medium text-on-surface bg-surface hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    <GoogleIcon class="w-5 h-5 shrink-0" />
                    Daftar dengan Google
                </a>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-outline" />
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-surface px-2 text-on-surface-variant">atau</span>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <InputLabel for="name" value="Nama" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1.5 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1.5 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Kata Sandi" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1.5 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Konfirmasi Kata Sandi" />
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
                        class="w-full justify-center"
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Memproses...' : 'Daftar' }}
                    </PrimaryButton>

                    <p class="text-center text-sm text-on-surface-variant">
                        Sudah punya akun?
                        <Link
                            :href="route('login')"
                            class="font-semibold text-primary hover:text-primary/80 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 rounded-md"
                        >
                            Masuk
                        </Link>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>
