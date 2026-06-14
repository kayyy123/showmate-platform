<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})

const page = usePage();

const plans = computed(() => {
    const loggedIn = !!page.props.auth?.user;
    return [
        {
            name: 'Gratis',
            badge: 'GRATIS',
            price: 'Rp 0',
            period: 'selamanya',
            description: 'Coba semua fitur dasar tanpa biaya. Mulai kembangkan toko digital UMKM Anda sekarang.',
            features: [
                'Katalog produk hingga 10 item',
                'Tautan bisnis tidak terbatas',
                'Profil toko',
                'Statistik kunjungan',
                'Aksesibilitas dasar',
                'Tema standar',
            ],
            cta: 'Mulai Gratis',
            href: loggedIn ? 'merchant.manage' : 'register',
            highlighted: false,
        },
        {
            name: 'Pro',
            badge: 'POPULER',
            price: 'Rp 49.000',
            period: '/bulan',
            description: 'Fitur lengkap untuk UMKM yang ingin berkembang lebih jauh dengan katalog tanpa batas.',
            features: [
                'Katalog produk tidak terbatas',
                'Tautan bisnis tidak terbatas',
                'Profil toko premium',
                'Statistik lengkap',
                'Aksesibilitas penuh',
                'Tema kustom',
                'Prioritas dukungan',
                'Hapus branding EtalaseKu',
            ],
            cta: 'Langganan Pro',
            href: loggedIn ? 'upgrade-pro.page' : 'register',
            highlighted: true,
        },
    ];
});

const features = [
    {
        title: 'Katalog Produk',
        description: 'Tampilkan semua produk UMKM dalam satu halaman katalog digital yang rapi, lengkap dengan gambar, harga, dan deskripsi.',
        icon: 'catalog',
    },
    {
        title: 'Tautan Bisnis',
        description: 'Kumpulkan semua link penting seperti WhatsApp, Instagram, TikTok, dan marketplace dalam satu tempat yang mudah diakses.',
        icon: 'links',
    },
    {
        title: 'Profil Toko',
        description: 'Buat profil toko profesional dengan logo, deskripsi, dan informasi kontak yang mewakili brand UMKM Anda.',
        icon: 'store',
    },
    {
        title: 'Statistik Kunjungan',
        description: 'Pantau berapa banyak pengunjung yang melihat katalog dan melakukan interaksi dengan tautan bisnis Anda.',
        icon: 'stats',
    },
    {
        title: 'Aksesibilitas',
        description: 'Halaman publik yang ramah untuk semua pengguna dengan dukungan keyboard navigasi, kontras tinggi, dan screen reader.',
        icon: 'accessibility',
    },
    {
        title: 'Checkout WhatsApp',
        description: 'Pelanggan dapat langsung memesan produk melalui tombol checkout yang terhubung langsung ke WhatsApp bisnis Anda.',
        icon: 'checkout',
    },
]

const trustBadges = [
    'Gratis selamanya untuk UMKM',
    'Ribuan UMKM telah bergabung',
    'Aksesibilitas untuk semua',
]
</script>

<template>
    <Head title="EtalaseKu - Satu Link Untuk Semua Produk UMKM" />

    <div class="min-h-screen bg-[#0a0a0b] text-white overflow-x-hidden">
        <!-- ==================== NAVBAR ==================== -->
        <nav
            class="fixed top-3 md:top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-24px)] md:w-auto md:min-w-[640px] lg:min-w-[768px] max-w-4xl"
            role="navigation"
            aria-label="Navigasi utama"
        >
            <div
                class="flex items-center justify-between px-4 md:px-6 py-2.5 md:py-3 rounded-full bg-zinc-900/80 backdrop-blur-xl border border-zinc-800/60 shadow-lg shadow-black/20"
            >
                <!-- Brand -->
                <Link
                    href="/"
                    class="text-xl md:text-2xl font-extrabold tracking-tight shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded-lg px-1"
                    aria-label="EtalaseKu beranda"
                >
                    <span class="text-white">Etalase</span><span class="text-[#FFD700]">Ku</span>
                </Link>

                <!-- Center nav (desktop) -->
                <div class="hidden md:flex items-center gap-1">
                    <a
                        href="#fitur"
                        class="px-4 py-1.5 text-sm text-zinc-300 hover:text-white transition-colors rounded-full hover:bg-zinc-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        Fitur
                    </a>
                    <a
                        href="#harga"
                        class="px-4 py-1.5 text-sm text-zinc-300 hover:text-white transition-colors rounded-full hover:bg-zinc-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        Harga
                    </a>
                </div>

                <!-- Right actions -->
                <div class="flex items-center gap-2 md:gap-3">
                    <Link
                        :href="route('login')"
                        class="px-3 md:px-5 py-1.5 md:py-2 text-sm font-medium text-zinc-300 hover:text-white transition-colors rounded-full hover:bg-zinc-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        Login
                    </Link>
                    <Link
                        :href="route('register')"
                        class="px-3 md:px-5 py-1.5 md:py-2 text-sm font-semibold text-black bg-[#FFD700] hover:bg-yellow-300 transition-all rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        Sign Up
                    </Link>
                </div>
            </div>
        </nav>

        <!-- ==================== HERO ==================== -->
        <section
            class="relative min-h-screen flex flex-col items-center justify-center px-6 pt-24 md:pt-28 pb-16 md:pb-20 text-center"
            aria-labelledby="hero-title"
        >
            <!-- Background gradient -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
                <div
                    class="absolute -top-40 -left-40 w-80 h-80 md:w-[500px] md:h-[500px] rounded-full bg-purple-600/10 blur-[120px]"
                />
                <div
                    class="absolute -bottom-40 -right-40 w-80 h-80 md:w-[500px] md:h-[500px] rounded-full bg-yellow-500/10 blur-[120px]"
                />
            </div>

            <!-- Trust badge -->
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-zinc-700/60 bg-zinc-900/50 text-xs md:text-sm text-zinc-400 mb-8 md:mb-10"
            >
                <span class="w-2 h-2 rounded-full bg-emerald-400" aria-hidden="true" />
                Platform katalog digital inklusif untuk UMKM Indonesia
            </div>

            <!-- Main heading -->
            <h1
                id="hero-title"
                class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-extrabold leading-tight max-w-4xl mx-auto tracking-tight"
            >
                Satu Link Untuk Semua
                <br class="hidden sm:block" />
                <span class="text-[#FFD700]">Produk UMKM</span>
            </h1>

            <!-- Subtitle -->
            <p class="mt-5 md:mt-6 text-sm md:text-base lg:text-lg text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                EtalaseKu membantu UMKM membuat katalog digital, mengelola tautan bisnis, dan menjangkau pelanggan dengan halaman yang ramah aksesibilitas.
            </p>

            <!-- CTA -->
            <div class="mt-8 md:mt-10 flex flex-col sm:flex-row items-center gap-4">
                <Link
                    :href="route('register')"
                    class="inline-flex items-center gap-2 px-8 md:px-10 py-3.5 md:py-4 text-base md:text-lg font-bold text-black bg-[#FFD700] hover:bg-yellow-300 hover:scale-[1.02] active:scale-[0.98] transition-all rounded-full shadow-lg shadow-yellow-500/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                >
                    Mulai Gratis Sekarang
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
            </div>

            <!-- Trust badges -->
            <div class="mt-10 md:mt-14 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs md:text-sm text-zinc-500">
                <span v-for="badge in trustBadges" :key="badge" class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ badge }}
                </span>
            </div>
        </section>

        <!-- ==================== FEATURES ==================== -->
        <section
            id="fitur"
            class="relative px-6 py-20 md:py-28"
            aria-labelledby="fitur-title"
        >
            <div class="max-w-6xl mx-auto">
                <!-- Section heading -->
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2
                        id="fitur-title"
                        class="text-2xl md:text-4xl lg:text-5xl font-extrabold tracking-tight"
                    >
                        Sederhana tapi <span class="text-[#FFD700]">Powerful</span>
                    </h2>
                    <p class="mt-4 text-sm md:text-base text-zinc-400 leading-relaxed">
                        Semua yang dibutuhkan UMKM untuk tampil profesional ada di satu halaman.
                    </p>
                </div>

                <!-- Feature grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="group relative p-5 md:p-6 rounded-2xl bg-zinc-900/50 border border-zinc-800/60 hover:border-zinc-700/60 transition-all duration-300 hover:-translate-y-1"
                    >
                        <!-- Icon -->
                        <div
                            class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-zinc-800 flex items-center justify-center mb-4 group-hover:bg-[#FFD700]/10 transition-colors duration-300"
                            aria-hidden="true"
                        >
                            <!-- Katalog -->
                            <svg v-if="feature.icon === 'catalog'" class="w-5 h-5 md:w-6 md:h-6 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <!-- Links -->
                            <svg v-else-if="feature.icon === 'links'" class="w-5 h-5 md:w-6 md:h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                            <!-- Store -->
                            <svg v-else-if="feature.icon === 'store'" class="w-5 h-5 md:w-6 md:h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                            </svg>
                            <!-- Stats -->
                            <svg v-else-if="feature.icon === 'stats'" class="w-5 h-5 md:w-6 md:h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <!-- Accessibility -->
                            <svg v-else-if="feature.icon === 'accessibility'" class="w-5 h-5 md:w-6 md:h-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <!-- Checkout -->
                            <svg v-else-if="feature.icon === 'checkout'" class="w-5 h-5 md:w-6 md:h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 3.75v4.5m0-4.5h-4.5m4.5 0l-6 6m3 12c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 014.5 2.25h1.372c.516 0 .966.351 1.09.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 00-.276 1.455l1.106 1.722c.691 1.077 1.74 2.002 2.97 2.564l1.72.92a1.071 1.071 0 001.208-.212l.973-.974c.27-.27.664-.378 1.047-.272l4.282 1.07c.502.126.856.575.856 1.09v1.371a2.25 2.25 0 01-2.25 2.25h-1.5z" />
                            </svg>
                        </div>

                        <h3 class="text-base md:text-lg font-semibold mb-2">
                            {{ feature.title }}
                        </h3>
                        <p class="text-sm md:text-base text-zinc-400 leading-relaxed">
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== VISUAL DEMO ==================== -->
        <section
            class="relative px-6 py-20 md:py-28"
            aria-labelledby="demo-title"
        >
            <div class="max-w-6xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2
                        id="demo-title"
                        class="text-2xl md:text-4xl lg:text-5xl font-extrabold tracking-tight"
                    >
                        Lihat Bagaimana <span class="text-[#FFD700]">Tampilannya</span>
                    </h2>
                    <p class="mt-4 text-sm md:text-base text-zinc-400 leading-relaxed">
                        Setiap UMKM mendapatkan halaman publik yang profesional, lengkap dengan katalog produk, tautan bisnis, dan aksesibilitas.
                    </p>
                </div>

                <!-- Mockup card -->
                <div class="max-w-md mx-auto">
                    <div class="rounded-2xl bg-zinc-900 border border-zinc-800 overflow-hidden shadow-xl shadow-black/30">
                        <!-- Banner -->
                        <div class="h-24 md:h-28 bg-gradient-to-r from-purple-600/80 to-pink-600/80 flex items-end p-4 md:p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm md:text-base">
                                    TK
                                </div>
                                <div>
                                    <p class="text-white font-semibold text-sm md:text-base">Toko Kue Bu Ani</p>
                                    <p class="text-white/70 text-xs">Kue Tradisional & Modern</p>
                                </div>
                            </div>
                        </div>

                        <!-- Links -->
                        <div class="px-4 md:px-5 py-4 space-y-2.5">
                            <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-zinc-800/50 border border-zinc-700/50">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                <span class="text-sm text-zinc-300">+62 812-9174-3817</span>
                            </div>
                            <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-zinc-800/50 border border-zinc-700/50">
                                <svg class="w-4 h-4 text-pink-400 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.11 2.525c.636-.247 1.363-.416 2.427-.465C8.88 2.013 9.235 2 11.985 2h.33zm-.016 5.18a4.82 4.82 0 100 9.64 4.82 4.82 0 000-9.64zm0 1.6a3.22 3.22 0 110 6.44 3.22 3.22 0 010-6.44z" />
                                </svg>
                                <span class="text-sm text-zinc-300">@tokokuebuani</span>
                            </div>
                            <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-zinc-800/50 border border-zinc-700/50">
                                <svg class="w-4 h-4 text-blue-400 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2zm3.14 6.07c.31 0 .56.25.56.56v3.1a.56.56 0 11-1.12 0v-2.03l-3.17 3.17a.56.56 0 11-.8-.79l3.17-3.17h-2.03a.56.56 0 110-1.12h3.1c.04 0 .07.01.1.02.02 0 .05.02.07.03.03.01.06.03.09.06.02.02.04.04.06.06.03.03.05.06.07.1.01.02.03.05.04.07.02.04.02.08.02.12z" />
                                </svg>
                                <span class="text-sm text-zinc-300">Toko Kue Bu Ani</span>
                            </div>
                        </div>

                        <!-- Product preview -->
                        <div class="border-t border-zinc-800 px-4 md:px-5 py-4">
                            <p class="text-xs text-zinc-500 uppercase tracking-wider mb-3 font-medium">
                                Produk Unggulan
                            </p>
                            <div class="flex gap-3">
                                <div class="flex-1 rounded-xl bg-zinc-800/50 border border-zinc-700/50 p-3">
                                    <div class="w-full aspect-square rounded-lg bg-gradient-to-br from-amber-400/30 to-amber-600/30 mb-2" />
                                    <p class="text-xs font-medium text-zinc-300">Kue Bolu</p>
                                    <p class="text-xs text-zinc-500">Rp 45.000</p>
                                </div>
                                <div class="flex-1 rounded-xl bg-zinc-800/50 border border-zinc-700/50 p-3">
                                    <div class="w-full aspect-square rounded-lg bg-gradient-to-br from-pink-400/30 to-pink-600/30 mb-2" />
                                    <p class="text-xs font-medium text-zinc-300">Kue Lapis</p>
                                    <p class="text-xs text-zinc-500">Rp 35.000</p>
                                </div>
                                <div class="flex-1 rounded-xl bg-zinc-800/50 border border-zinc-700/50 p-3">
                                    <div class="w-full aspect-square rounded-lg bg-gradient-to-br from-green-400/30 to-green-600/30 mb-2" />
                                    <p class="text-xs font-medium text-zinc-300">Kue Lumpur</p>
                                    <p class="text-xs text-zinc-500">Rp 25.000</p>
                                </div>
                            </div>
                        </div>

                        <!-- Accessible badge -->
                        <div class="border-t border-zinc-800 px-4 md:px-5 py-3 flex items-center justify-between">
                            <span class="text-xs text-zinc-500">Halaman ramah aksesibilitas</span>
                            <span class="inline-flex items-center gap-1 text-xs text-emerald-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== ACCESSIBILITY ==================== -->
        <section
            class="relative px-6 py-20 md:py-28 bg-zinc-900/30"
            aria-labelledby="a11y-title"
        >
            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-center">
                    <!-- Left content -->
                    <div>
                        <h2
                            id="a11y-title"
                            class="text-2xl md:text-4xl lg:text-5xl font-extrabold tracking-tight"
                        >
                            Dibuat agar lebih mudah
                            <br />
                            <span class="text-[#FFD700]">diakses semua pelanggan</span>
                        </h2>
                        <p class="mt-4 text-sm md:text-base text-zinc-400 leading-relaxed">
                            EtalaseKu memastikan setiap halaman publik dapat diakses oleh semua pengguna, termasuk mereka yang menggunakan teknologi bantuan.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li
                                v-for="item in [
                                    { text: 'Dukungan navigasi keyboard penuh', icon: 'keyboard' },
                                    { text: 'Alt text deskriptif pada semua gambar', icon: 'image' },
                                    { text: 'Mode kontras tinggi untuk keterbacaan', icon: 'contrast' },
                                    { text: 'Struktur heading ramah screen reader', icon: 'structure' },
                                    { text: 'Ukuran teks yang nyaman dibaca', icon: 'text' },
                                ]"
                                :key="item.text"
                                class="flex items-start gap-3"
                            >
                                <span class="w-5 h-5 mt-0.5 shrink-0 rounded-full bg-emerald-400/20 flex items-center justify-center" aria-hidden="true">
                                    <svg class="w-3 h-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </span>
                                <span class="text-sm md:text-base text-zinc-300">{{ item.text }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Right visual -->
                    <div class="hidden md:flex justify-center" aria-hidden="true">
                        <div class="relative w-72 h-72">
                            <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-purple-600/10 to-pink-600/10 border border-zinc-800 flex items-center justify-center">
                                <svg class="w-32 h-32 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== PRICING ==================== -->
        <section
            id="harga"
            class="relative px-6 py-20 md:py-28"
            aria-labelledby="harga-title"
        >
            <div class="max-w-6xl mx-auto">
                <!-- Section heading -->
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2
                        id="harga-title"
                        class="text-2xl md:text-4xl lg:text-5xl font-extrabold tracking-tight"
                    >
                        Pilih Paket Sesuai
                        <br />
                        <span class="text-[#FFD700]">Kebutuhan UMKM</span>
                    </h2>
                    <p class="mt-4 text-sm md:text-base text-zinc-400 leading-relaxed">
                        Mulai dari gratis, kembangkan toko digital UMKM Anda tanpa biaya awal.
                    </p>
                </div>

                <!-- Pricing cards -->
                <div class="grid md:grid-cols-2 gap-6 md:gap-8 max-w-3xl mx-auto">
                    <div
                        v-for="plan in plans"
                        :key="plan.name"
                        class="relative rounded-2xl border p-6 md:p-8 transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl"
                        :class="plan.highlighted
                            ? 'border-yellow-500/40 bg-zinc-900/80 shadow-xl shadow-yellow-500/5 md:scale-[1.02]'
                            : 'border-zinc-800/60 bg-zinc-900/50'"
                    >
                        <!-- Badge -->
                        <div
                            class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-xs font-bold"
                            :class="plan.highlighted
                                ? 'bg-[#FFD700] text-black'
                                : 'bg-zinc-700 text-zinc-300'"
                        >
                            {{ plan.badge }}
                        </div>

                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold">{{ plan.name }}</h3>
                            <div class="mt-3">
                                <span class="text-3xl md:text-4xl font-extrabold">{{ plan.price }}</span>
                                <span class="text-sm text-zinc-400 ml-1">{{ plan.period }}</span>
                            </div>
                            <p class="mt-3 text-sm text-zinc-400 leading-relaxed">
                                {{ plan.description }}
                            </p>
                        </div>

                        <!-- Features list -->
                        <ul class="space-y-3 mb-8">
                            <li
                                v-for="feature in plan.features"
                                :key="feature"
                                class="flex items-start gap-3 text-sm"
                            >
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="text-zinc-300">{{ feature }}</span>
                            </li>
                        </ul>

                        <!-- CTA -->
                        <Link
                            :href="route(plan.href)"
                            class="block w-full text-center py-3 rounded-xl text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                            :class="plan.highlighted
                                ? 'bg-[#FFD700] text-black hover:bg-yellow-300'
                                : 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700 hover:text-white border border-zinc-700/60'"
                        >
                            {{ plan.cta }}
                        </Link>
                    </div>
                </div>

                <p class="mt-8 text-center text-xs text-zinc-500 max-w-lg mx-auto">
                    Pembayaran Pro akan tersedia melalui QRIS dan metode pembayaran lainnya pada versi produksi.
                </p>
            </div>
        </section>

        <!-- ==================== CTA BANNER ==================== -->
        <section class="relative px-6 py-20 md:py-28">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-4xl lg:text-5xl font-extrabold tracking-tight">
                    Siap Membawa UMKM Anda
                    <br />
                    <span class="text-[#FFD700]">ke Dunia Digital?</span>
                </h2>
                <p class="mt-4 text-sm md:text-base text-zinc-400 max-w-xl mx-auto leading-relaxed">
                    Bergabung dengan ribuan UMKM lain yang sudah menggunakan EtalaseKu untuk mengelola katalog digital dan tautan bisnis.
                </p>
                <div class="mt-8">
                    <Link
                        :href="route('register')"
                        class="inline-flex items-center gap-2 px-8 md:px-10 py-3.5 md:py-4 text-base md:text-lg font-bold text-black bg-[#FFD700] hover:bg-yellow-300 hover:scale-[1.02] active:scale-[0.98] transition-all rounded-full shadow-lg shadow-yellow-500/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        Mulai Gratis Sekarang
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ==================== FOOTER ==================== -->
        <footer class="border-t border-zinc-800 px-6 py-10 md:py-12" role="contentinfo">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <!-- Brand -->
                    <div class="text-center md:text-left">
                        <Link
                            href="/"
                            class="text-xl md:text-2xl font-extrabold tracking-tight focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded-lg px-1"
                        >
                            <span class="text-white">Etalase</span><span class="text-[#FFD700]">Ku</span>
                        </Link>
                        <p class="mt-1 text-xs text-zinc-500">
                            Platform katalog digital inklusif untuk UMKM Indonesia.
                        </p>
                    </div>

                    <!-- Links -->
                    <nav class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-sm text-zinc-400" aria-label="Footer navigasi">
                        <a
                            href="#fitur"
                            class="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded px-1"
                        >
                            Fitur
                        </a>
                        <a
                            href="#harga"
                            class="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded px-1"
                        >
                            Harga
                        </a>
                        <a
                            href="#"
                            class="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded px-1"
                        >
                            Syarat Layanan
                        </a>
                        <a
                            href="#"
                            class="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded px-1"
                        >
                            Kebijakan Privasi
                        </a>
                        <a
                            href="#"
                            class="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded px-1"
                        >
                            Pusat Bantuan
                        </a>
                    </nav>
                </div>

                <!-- Copyright -->
                <div class="mt-8 pt-6 border-t border-zinc-800/60 text-center text-xs text-zinc-600">
                    &copy; {{ new Date().getFullYear() }} EtalaseKu. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</template>
