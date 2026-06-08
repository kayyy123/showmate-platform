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

    <div class="min-h-screen flex flex-col md:flex-row bg-surface">
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
                <h1 class="text-2xl font-bold text-on-surface">Selamat datang kembali!</h1>
                <p class="text-sm text-on-surface-variant mt-1 mb-8">Masuk ke akun EtalaseKu Anda</p>

                <!-- Status -->
                <div v-if="status" class="mb-4 text-sm font-medium text-emerald-600 dark:text-emerald-400" role="status">
                    {{ status }}
                </div>

                <!-- Google button -->
                <a
                    :href="route('auth.google')"
                    class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-outline rounded-lg text-sm font-medium text-on-surface bg-surface hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    <GoogleIcon class="w-5 h-5 shrink-0" />
                    Masuk dengan Google
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
                        <InputLabel for="email" value="Email" />
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
                        <InputLabel for="password" value="Kata Sandi" />
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
                            <span class="text-sm text-on-surface">Ingat saya</span>
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium text-primary hover:text-primary/80 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 rounded-md"
                        >
                            Lupa kata sandi?
                        </Link>
                    </div>

                    <PrimaryButton
                        class="w-full justify-center"
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Memproses...' : 'Masuk' }}
                    </PrimaryButton>

                    <p class="text-center text-sm text-on-surface-variant">
                        Belum punya akun?
                        <Link
                            :href="route('register')"
                            class="font-semibold text-primary hover:text-primary/80 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 rounded-md"
                        >
                            Daftar
                        </Link>
                    </p>
                </form>
            </div>
        </div>

        <!-- Right panel (poster) -->
        <div class="hidden md:block md:w-1/2 lg:w-1/2 xl:w-1/2 sticky top-0 h-screen">
            <img
                src="/images/login.png"
                alt="Ilustrasi login EtalaseKu"
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-l from-transparent via-transparent to-surface/10" />
        </div>

        <!-- Mobile poster (top) -->
        <div class="md:hidden relative w-full h-48 shrink-0">
            <img
                src="/images/login.png"
                alt="Ilustrasi login EtalaseKu"
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-surface" />
        </div>
    </div>
</template>
