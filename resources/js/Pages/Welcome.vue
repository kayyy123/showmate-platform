<script setup>
import { ref, computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})

const page = usePage()

const isMobileMenuOpen = ref(false)
const activeFaq = ref(null)

const scrollTo = (id) => {
    isMobileMenuOpen.value = false
    const el = document.getElementById(id)
    if (el) el.scrollIntoView({ behavior: 'smooth' })
}

const plans = computed(() => {
    const loggedIn = !!page.props.auth?.user
    return [
        {
            name: 'Gratis',
            price: 'Rp 0',
            period: '',
            description: 'Untuk UMKM yang baru memulai katalog digital.',
            features: [
                'Maks 10 produk',
                'Profil toko',
                'Link bisnis tak terbatas',
                'Statistik dasar',
                'Tema standar',
            ],
            cta: 'Daftar Gratis',
            routeName: loggedIn ? 'merchant.manage' : 'register',
            highlighted: false,
        },
        {
            name: 'Pro',
            price: 'Rp 49.000',
            period: '/bulan',
            description: 'Untuk UMKM yang ingin berkembang dengan fitur lengkap.',
            features: [
                'Produk tanpa batas',
                'Statistik lengkap',
                'Tema custom',
                'Verified Seller',
                'Trusted Badge',
                'QRIS Payment',
                'Hapus branding',
                'Prioritas dukungan',
            ],
            cta: 'Upgrade ke Pro',
            routeName: loggedIn ? 'upgrade-pro.page' : 'register',
            highlighted: true,
        },
    ]
})

const comparisonFeatures = [
    {
        category: 'Produk & Checkout',
        rows: [
            { label: 'Jumlah Produk', gratis: '10 produk', pro: 'Tanpa batas' },
            { label: 'Katalog Publik', gratis: true, pro: true },
            { label: 'Link Bisnis', gratis: 'Tak terbatas', pro: 'Tak terbatas' },
            { label: 'Checkout Web', gratis: true, pro: true },
            { label: 'Checkout WhatsApp', gratis: true, pro: true },
        ],
    },
    {
        category: 'Analitik',
        rows: [
            { label: 'Statistik Dasar', gratis: true, pro: true },
            { label: 'Statistik Lengkap', gratis: false, pro: true },
        ],
    },
    {
        category: 'Kustomisasi & Branding',
        rows: [
            { label: 'Tema Custom', gratis: false, pro: true },
            { label: 'Hapus Branding EtalaseKu', gratis: false, pro: true },
        ],
    },
    {
        category: 'Trust & Payment',
        rows: [
            { label: 'QRIS Payment', gratis: false, pro: true },
            { label: 'Verified Seller', gratis: false, pro: true },
            { label: 'Trusted Seller Badge', gratis: false, pro: true },
        ],
    },
    {
        category: 'Aksesibilitas',
        rows: [
            { label: 'Alt Text Produk', gratis: true, pro: true },
            { label: 'Tampilan Ramah Aksesibilitas', gratis: true, pro: true },
            { label: 'Badge UMKM Inklusif', gratis: false, pro: true },
        ],
    },
    {
        category: 'Dukungan',
        rows: [
            { label: 'Dukungan Prioritas', gratis: false, pro: true },
        ],
    },
]

const masalah = [
    { icon: 'search-off', text: 'Produk UMKM sulit ditemukan dan tersebar di banyak platform tanpa pusat yang rapi.' },
    { icon: 'chat', text: 'Katalog masih manual lewat chat bergantian, pelanggan harus scroll chat lama.' },
    { icon: 'link-off', text: 'Link bisnis seperti WhatsApp, Instagram, dan toko online tercecer di mana-mana.' },
    { icon: 'shield-off', text: 'Calon pembeli ragu karena toko tidak punya identitas digital yang profesional.' },
    { icon: 'accessibility-off', text: 'Banyak platform tidak ramah untuk pengguna dengan keterbatasan penglihatan atau motorik.' },
]

const solusi = [
    { icon: 'link', text: 'Satu tautan untuk semua produk, kontak, dan lokasi toko dalam halaman yang rapi.' },
    { icon: 'package', text: 'Produk, WhatsApp, alamat, dan tautan bisnis terorganisir dalam satu katalog.' },
    { icon: 'eye', text: 'Desain ramah aksesibilitas dengan kontras tinggi, navigasi keyboard, dan alt text.' },
    { icon: 'badge', text: 'Badge Verified Seller dan Trusted Badge untuk meningkatkan kepercayaan pembeli.' },
]

const fiturUtama = [
    { icon: 'store', title: 'Katalog Produk Online', desc: 'Tampilkan produk UMKM dalam katalog digital yang rapi dengan gambar, harga, dan deskripsi.' },
    { icon: 'link-intact', title: 'Tautan Bisnis', desc: 'Kumpulkan WhatsApp, Instagram, TikTok, dan marketplace dalam satu halaman seperti Linktree.' },
    { icon: 'cart', title: 'Checkout Web & WhatsApp', desc: 'Pelanggan bisa pesan langsung lewat tombol checkout terintegrasi WhatsApp atau form web.' },
    { icon: 'chart', title: 'Statistik Kunjungan', desc: 'Pantau jumlah pengunjung katalog dan interaksi dengan tautan bisnis secara real-time.' },
    { icon: 'accessible', title: 'Aksesibilitas WCAG AA', desc: 'Katalog ramah disabilitas dengan dukungan screen reader, navigasi keyboard, dan kontras warna.' },
    { icon: 'verified', title: 'Verified & Trust Badge', desc: 'Tingkatkan kepercayaan dengan lencana Verified Seller dan Trusted Seller di profil toko.' },
    { icon: 'location', title: 'Lokasi Toko', desc: 'Cantumkan alamat toko fisik agar pelanggan mudah menemukan lokasi usaha Anda.' },
    { icon: 'qr', title: 'QRIS untuk UMKM', desc: 'Terima pembayaran non-tunai dengan QRIS statis yang bisa ditampilkan di halaman toko.' },
]

const layanan = [
    { title: 'Katalog Digital UMKM', desc: 'Buat katalog produk online yang mudah dibagikan tanpa perlu membuat website dari nol.' },
    { title: 'Pemesanan WhatsApp', desc: 'Pelanggan dapat langsung menghubungi merchant melalui WhatsApp untuk bertanya atau membeli produk.' },
    { title: 'Checkout Online', desc: 'Terima pesanan langsung dari katalog dengan form checkout yang sederhana dan mudah digunakan.' },
    { title: 'Profil & Lokasi Toko', desc: 'Tampilkan nama toko, deskripsi, WhatsApp, alamat, kota, provinsi, dan link Google Maps agar pelanggan lebih percaya.' },
    { title: 'Verified Seller', desc: 'Bantu merchant membangun kepercayaan melalui badge seller terverifikasi.' },
    { title: 'Aksesibilitas untuk Semua', desc: 'Dukung pengalaman belanja yang lebih inklusif dengan tampilan mudah dibaca, alt text produk, navigasi sederhana, dan kontras warna yang nyaman.' },
    { title: 'Statistik Usaha', desc: 'Pantau kunjungan katalog, produk populer, dan aktivitas pelanggan.' },
    { title: 'Dukungan UMKM Disabilitas', desc: 'Cocok untuk UMKM inklusif seperti alat bantu disabilitas, fashion adaptif, pendidikan inklusif, kerajinan, makanan, dan jasa lokal.' },
]

const untukSiapa = [
    { icon: 'cup', label: 'UMKM Makanan & Minuman' },
    { icon: 'palette', label: 'Kerajinan Tangan' },
    { icon: 'shirt', label: 'Fashion Adaptif' },
    { icon: 'heart', label: 'Alat Bantu Disabilitas' },
    { icon: 'tools', label: 'Jasa Lokal' },
    { icon: 'users', label: 'Komunitas & Usaha Sosial' },
]

const caraKerja = [
    { step: '1', title: 'Daftar Gratis', desc: 'Buat akun merchant EtalaseKu dalam 1 menit. Tidak perlu kartu kredit.' },
    { step: '2', title: 'Isi Profil & Produk', desc: 'Tambahkan nama toko, foto produk, harga, dan tautan bisnis Anda.' },
    { step: '3', title: 'Bagikan Link Katalog', desc: 'Sebarkan satu tautan ke pelanggan. Mereka bisa lihat katalog dan pesan langsung.' },
]

const faq = [
    { q: 'Apakah pembeli harus login untuk melihat katalog saya?', a: 'Tidak. Katalog EtalaseKu bersifat publik dan bisa diakses siapa saja tanpa login. Pembeli cukup mengklik tautan yang Anda bagikan.' },
    { q: 'Apakah pelanggan bisa pesan langsung lewat WhatsApp?', a: 'Bisa. Setiap produk memiliki tombol checkout yang terhubung langsung ke nomor WhatsApp bisnis Anda. Pelanggan cukup klik dan otomatis terkirim format pesanan.' },
    { q: 'Apakah EtalaseKu cocok untuk UMKM kecil dengan produk terbatas?', a: 'Sangat cocok. Paket Gratis sudah mendukung hingga 10 produk, tautan bisnis tidak terbatas, dan profil toko lengkap. Tanpa biaya awal.' },
    { q: 'Apakah platform ini mendukung pelaku usaha disabilitas?', a: 'Ya, EtalaseKu dirancang khusus dengan aksesibilitas sebagai prioritas. Mendukung alt text produk, navigasi keyboard, kontras tinggi, dan ramah screen reader.' },
    { q: 'Apakah saya bisa menampilkan QRIS di halaman toko?', a: 'Bisa. Paket Pro menyediakan fitur QRIS statis yang bisa ditampilkan di halaman katalog agar pelanggan bisa bayar langsung.' },
    { q: 'Apa perbedaan paket Gratis dan Pro?', a: 'Paket Gratis cukup untuk memulai dengan 10 produk dan fitur dasar. Paket Pro menghapus batas produk, menambah statistik lengkap, tema kustom, QRIS, Verified Seller, dan bebas branding EtalaseKu.' },
]

const showcaseMerchants = [
    {
        name: 'Akses Mandiri',
        slug: 'akses-mandiri',
        category: 'Alat Bantu Disabilitas',
        badges: ['verified', 'inclusive'],
        colors: { from: 'from-blue-600/20', to: 'to-blue-700/10', badge: 'bg-blue-500/10 text-blue-400 border-blue-500/30' },
        stats: { produk: 24, kunjungan: '1.2K', pesanan: 89 },
        products: [
            { emoji: '🦯', name: 'Tongkat Lipat', price: 'Rp 85.000' },
            { emoji: '♿', name: 'Kursi Roda Lipat', price: 'Rp 2.450.000' },
        ],
        desc: 'Menyediakan alat bantu mandiri untuk disabilitas fisik.',
        wa: '6281234567890',
    },
    {
        name: 'Sahabat Tuli Indonesia',
        slug: 'sahabat-tuli',
        category: 'Pendidikan Inklusif',
        badges: ['verified'],
        colors: { from: 'from-purple-600/20', to: 'to-purple-700/10', badge: 'bg-purple-500/10 text-purple-400 border-purple-500/30' },
        stats: { produk: 18, kunjungan: '3.4K', pesanan: 156 },
        products: [
            { emoji: '📖', name: 'Kamus BISINDO', price: 'Rp 65.000' },
            { emoji: '🎓', name: 'Kelas Bahasa Isyarat', price: 'Rp 150.000' },
        ],
        desc: 'Kelas dan materi bahasa isyarat untuk komunitas.',
        wa: '6281234567891',
    },
    {
        name: 'Difabel Berkarya',
        slug: 'difabel-berkarya',
        category: 'Kerajinan',
        badges: ['inclusive'],
        colors: { from: 'from-emerald-600/20', to: 'to-emerald-700/10', badge: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' },
        stats: { produk: 32, kunjungan: '2.8K', pesanan: 210 },
        products: [
            { emoji: '🧵', name: 'Tas Rajut Eksklusif', price: 'Rp 120.000' },
            { emoji: '🪡', name: 'Dompet Batik Tuli', price: 'Rp 55.000' },
        ],
        desc: 'Kerajinan tangan berkarya dari teman-teman disabilitas.',
        wa: '6281234567892',
    },
    {
        name: 'Adaptif Fashion',
        slug: 'adaptif-fashion',
        category: 'Fashion Adaptif',
        badges: ['verified', 'inclusive'],
        colors: { from: 'from-rose-600/20', to: 'to-rose-700/10', badge: 'bg-rose-500/10 text-rose-400 border-rose-500/30' },
        stats: { produk: 47, kunjungan: '5.1K', pesanan: 378 },
        products: [
            { emoji: '👕', name: 'Kemeja Magnetik', price: 'Rp 135.000' },
            { emoji: '👖', name: 'Celana Elastis', price: 'Rp 110.000' },
        ],
        desc: 'Fashion adaptif yang nyaman untuk semua.',
        wa: '6281234567893',
    },
    {
        name: 'Rasa Inklusif',
        slug: 'rasa-inklusif',
        category: 'Makanan & Minuman',
        badges: ['verified'],
        colors: { from: 'from-amber-600/20', to: 'to-amber-700/10', badge: 'bg-amber-500/10 text-amber-400 border-amber-500/30' },
        stats: { produk: 15, kunjungan: '4.7K', pesanan: 520 },
        products: [
            { emoji: '🍱', name: 'Nasi Kotak Inklusif', price: 'Rp 25.000' },
            { emoji: '🥤', name: 'Sirup Herbal', price: 'Rp 18.000' },
        ],
        desc: 'Kuliner ramah difabel, dipesan dengan mudah.',
        wa: '6281234567894',
    },
]

const trustPoints = [
    { icon: 'badge-check', text: 'Verified Seller — Profil toko terverifikasi' },
    { icon: 'map-pin', text: 'Alamat dan lokasi toko jelas' },
    { icon: 'phone', text: 'Nomor WhatsApp aktif tercantum' },
    { icon: 'flag', text: 'Laporkan seller jika mencurigakan' },
    { icon: 'alert', text: 'Laporkan produk jika tidak sesuai' },
    { icon: 'shield', text: 'Trusted Seller Badge untuk toko terpercaya' },
]

const navLinks = [
    { id: 'fitur', label: 'Fitur' },
    { id: 'showcase', label: 'Showcase' },
    { id: 'layanan', label: 'Layanan' },
    { id: 'untuk-siapa', label: 'Untuk Siapa' },
    { id: 'harga', label: 'Harga' },
    { id: 'faq', label: 'FAQ' },
]
</script>

<template>
    <Head title="EtalaseKu - Katalog Digital Inklusif untuk UMKM" />

    <div class="min-h-screen bg-[#0a0a0b] text-white overflow-x-hidden">
        <!-- Skip to content -->
        <a href="#hero" class="skip-link">
            Langsung ke konten utama
        </a>

        <!-- ==================== NAVBAR ==================== -->
        <header
            class="fixed top-0 left-0 right-0 z-50 px-4 md:px-8 py-3"
            role="banner"
        >
            <nav
                class="max-w-7xl mx-auto flex items-center justify-between bg-zinc-900/80 backdrop-blur-xl border border-zinc-800/60 rounded-2xl px-5 md:px-8 py-3"
                role="navigation"
                aria-label="Navigasi utama"
            >
                <Link href="/" class="flex items-center gap-2 shrink-0" aria-label="EtalaseKu beranda">
                    <img src="/images/image4-removebg-preview.png" alt="Logo EtalaseKu" class="h-10 md:h-12" />
                    <span class="text-2xl md:text-3xl font-extrabold tracking-tight"><span class="text-white">Etalase</span><span class="text-[#FFD700]">Ku</span></span>
                </Link>

                <div class="hidden md:flex items-center gap-6">
                    <button
                        v-for="link in navLinks"
                        :key="link.id"
                        @click="scrollTo(link.id)"
                        class="text-sm text-zinc-400 hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded px-2 py-1"
                    >
                        {{ link.label }}
                    </button>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="text-sm font-medium text-zinc-300 hover:text-white transition-colors px-4 py-2 rounded-lg border border-zinc-700 hover:border-zinc-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        Masuk
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="text-sm font-semibold bg-[#FFD700] text-black px-5 py-2 rounded-lg hover:bg-yellow-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    >
                        Daftar Gratis
                    </Link>
                </div>

                <button
                    class="md:hidden p-2 rounded-lg text-zinc-400 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    :aria-label="isMobileMenuOpen ? 'Tutup menu' : 'Buka menu'"
                    :aria-expanded="isMobileMenuOpen"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </nav>

            <!-- Mobile menu -->
            <div
                v-if="isMobileMenuOpen"
                class="md:hidden mt-2 bg-zinc-900/95 backdrop-blur-xl border border-zinc-800/60 rounded-2xl px-5 py-4 space-y-3"
                role="navigation"
                aria-label="Navigasi mobile"
            >
                <button
                    v-for="link in navLinks"
                    :key="'m-' + link.id"
                    @click="scrollTo(link.id)"
                    class="block w-full text-left text-sm text-zinc-400 hover:text-white transition-colors py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded px-2"
                >
                    {{ link.label }}
                </button>
                <hr class="border-zinc-800" aria-hidden="true" />
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="block text-center text-sm font-medium text-zinc-300 border border-zinc-700 rounded-lg px-4 py-2 hover:border-zinc-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                >
                    Masuk
                </Link>
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="block text-center text-sm font-semibold bg-[#FFD700] text-black rounded-lg px-4 py-2 hover:bg-yellow-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                >
                    Daftar Gratis
                </Link>
            </div>
        </header>

        <!-- ==================== HERO ==================== -->
        <section id="hero" class="relative pt-36 pb-16 md:pt-44 md:pb-24 px-6 overflow-hidden">
            <!-- Background orbs -->
            <div class="absolute top-[-200px] left-[-100px] w-[500px] h-[500px] rounded-full bg-yellow-500/10 blur-[120px] pointer-events-none" aria-hidden="true" />
            <div class="absolute bottom-[-150px] right-[-100px] w-[400px] h-[400px] rounded-full bg-yellow-500/5 blur-[100px] pointer-events-none" aria-hidden="true" />

            <div class="max-w-7xl mx-auto relative z-10">
                <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-zinc-800/60 border border-zinc-700/60 text-xs text-zinc-400 mb-6" role="status">
                            <span class="w-2 h-2 rounded-full bg-emerald-400" aria-hidden="true" />
                            Gratis untuk UMKM — tanpa biaya awal
                        </div>

                        <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                            Katalog Digital Inklusif
                            <br />
                            <span class="text-[#FFD700]">untuk UMKM</span>
                            <br />
                            yang Ingin Lebih Mudah Ditemukan
                        </h1>

                        <p class="mt-5 text-base md:text-lg text-zinc-400 leading-relaxed max-w-xl">
                            Buat katalog toko online dalam hitungan menit. Bagikan satu tautan ke pelanggan — mereka bisa lihat produk, hubungi via WhatsApp, dan pesan langsung. Ramah untuk semua, termasuk pengguna disabilitas.
                        </p>

                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="inline-flex items-center justify-center gap-2 bg-[#FFD700] text-black font-semibold px-7 py-3.5 rounded-xl hover:bg-yellow-300 transition-all hover:scale-[1.02] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 shadow-lg shadow-yellow-500/20"
                            >
                                Mulai Gratis
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </Link>
                            <button
                                @click="scrollTo('demo')"
                                class="inline-flex items-center justify-center gap-2 border border-zinc-700 text-zinc-300 font-medium px-7 py-3.5 rounded-xl hover:border-zinc-500 hover:text-white transition-all hover:scale-[1.02] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                            >
                                Lihat Contoh Katalog
                            </button>
                        </div>

                        <div class="mt-8 flex flex-wrap items-center gap-5 text-sm text-zinc-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Gratis selamanya
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Aksesibilitas WCAG AA
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Ribuan UMKM bergabung
                            </span>
                        </div>
                    </div>

                    <!-- Phone mockup -->
                    <div id="demo" class="relative flex justify-center md:justify-end">
                        <div class="w-[280px] sm:w-[320px] bg-zinc-900 rounded-[2.5rem] border-4 border-zinc-800 shadow-2xl shadow-yellow-500/5 overflow-hidden">
                            <div class="px-4 pt-5 pb-3 bg-zinc-800/50 border-b border-zinc-800">
                                <div class="flex items-center gap-3 mb-1">
                                    <div class="w-9 h-9 rounded-full bg-[#FFD700]/20 flex items-center justify-center text-[#FFD700] text-sm font-bold" aria-hidden="true">K</div>
                                    <div>
                                        <p class="text-sm font-semibold text-white">Kue Bu Ani</p>
                                        <p class="text-[10px] text-zinc-500">Kue & Camilan Rumahan</p>
                                    </div>
                                </div>
                                <p class="mt-2 text-[11px] text-zinc-400 leading-relaxed">
                                    Toko kue rumahan siap antar. Pesan sekarang!
                                </p>
                                <div class="mt-2 flex flex-wrap gap-1.5" role="list" aria-label="Tautan bisnis">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 text-[10px] font-medium" role="listitem">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        WhatsApp
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-pink-500/10 text-pink-400 text-[10px] font-medium" role="listitem">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                        Instagram
                                    </span>
                                </div>
                            </div>
                            <div class="px-4 py-3 space-y-3" role="list" aria-label="Produk">
                                <div class="flex gap-3 items-center p-2.5 rounded-xl bg-zinc-800/40 border border-zinc-800/60">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-500/30 to-amber-600/20 shrink-0 flex items-center justify-center text-lg" aria-hidden="true">🎂</div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-white truncate">Kue Ulang Tahun</p>
                                        <p class="text-[11px] text-zinc-500">Rp 85.000</p>
                                        <p class="text-[10px] text-emerald-400 font-medium">Pesan via WhatsApp</p>
                                    </div>
                                </div>
                                <div class="flex gap-3 items-center p-2.5 rounded-xl bg-zinc-800/40 border border-zinc-800/60">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-500/30 to-amber-600/20 shrink-0 flex items-center justify-center text-lg" aria-hidden="true">🍪</div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-white truncate">Kue Kering Lebaran</p>
                                        <p class="text-[11px] text-zinc-500">Rp 45.000</p>
                                        <p class="text-[10px] text-emerald-400 font-medium">Pesan via WhatsApp</p>
                                    </div>
                                </div>
                                <div class="flex gap-3 items-center p-2.5 rounded-xl bg-zinc-800/40 border border-zinc-800/60">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-500/30 to-amber-600/20 shrink-0 flex items-center justify-center text-lg" aria-hidden="true">🧁</div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-white truncate">Cupcake Kustom</p>
                                        <p class="text-[11px] text-zinc-500">Rp 25.000</p>
                                        <p class="text-[10px] text-emerald-400 font-medium">Pesan via WhatsApp</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-2.5 border-t border-zinc-800 flex items-center justify-between text-[10px] text-zinc-500">
                                <span class="flex items-center gap-1">
                                    <img src="/images/image4-removebg-preview.png" alt="Logo EtalaseKu" class="h-4" />
                                    EtalaseKu
                                </span>
                                <span>1 link untuk semua produk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== MASALAH & SOLUSI ==================== -->
        <section id="masalah" class="px-6 py-16 md:py-24">
            <div class="max-w-6xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Masalah UMKM Saat Ini
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        Dan bagaimana EtalaseKu membantu menyelesaikannya.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start">
                    <div>
                        <h3 class="text-lg font-semibold text-red-400 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                            Masalah
                        </h3>
                        <ul class="space-y-4">
                            <li v-for="(item, i) in masalah" :key="i" class="flex items-start gap-3 p-3 rounded-xl bg-red-500/5 border border-red-500/10">
                                <svg class="w-5 h-5 mt-0.5 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                <span class="text-sm text-zinc-300">{{ item.text }}</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-emerald-400 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Solusi EtalaseKu
                        </h3>
                        <ul class="space-y-4">
                            <li v-for="(item, i) in solusi" :key="i" class="flex items-start gap-3 p-3 rounded-xl bg-emerald-500/5 border border-emerald-500/10">
                                <svg class="w-5 h-5 mt-0.5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span class="text-sm text-zinc-300">{{ item.text }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== FITUR UTAMA ==================== -->
        <section id="fitur" class="px-6 py-16 md:py-24 bg-zinc-900/30">
            <div class="max-w-6xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Fitur <span class="text-[#FFD700]">Lengkap</span> untuk UMKM
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        Semua yang Anda butuhkan untuk membawa toko ke dunia digital.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div
                        v-for="fitur in fiturUtama"
                        :key="fitur.title"
                        class="group rounded-2xl border border-zinc-800/60 bg-zinc-900/50 p-5 md:p-6 transition-all duration-300 hover:border-yellow-500/30 hover:bg-zinc-900/80 hover:shadow-lg hover:shadow-yellow-500/5 hover:-translate-y-1"
                    >
                        <div class="w-10 h-10 rounded-xl bg-[#FFD700]/10 flex items-center justify-center mb-4 group-hover:bg-[#FFD700]/20 transition-colors" aria-hidden="true">
                            <svg v-if="fitur.icon === 'store'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                            <svg v-else-if="fitur.icon === 'link-intact'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                            <svg v-else-if="fitur.icon === 'cart'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                            <svg v-else-if="fitur.icon === 'chart'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            <svg v-else-if="fitur.icon === 'accessible'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                            <svg v-else-if="fitur.icon === 'verified'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                            <svg v-else-if="fitur.icon === 'location'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            <svg v-else-if="fitur.icon === 'qr'" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 14.625v0m0 0a1.125 1.125 0 011.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v.75m-6.75-2.25a1.125 1.125 0 01-1.125 1.125h-.75" /></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-white mb-1.5">{{ fitur.title }}</h3>
                        <p class="text-xs text-zinc-400 leading-relaxed">{{ fitur.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== SHOWCASE ==================== -->
        <section id="showcase" class="px-6 py-16 md:py-24">
            <div class="max-w-6xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Contoh Katalog UMKM di <span class="text-[#FFD700]">EtalaseKu</span>
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        Lihat bagaimana UMKM inklusif menampilkan produk, tautan bisnis, WhatsApp, dan informasi toko dalam satu halaman katalog digital.
                    </p>
                </div>

                <!-- Horizontal scroll carousel -->
                <div
                    class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-thin scrollbar-thumb-zinc-700 scrollbar-track-transparent"
                    role="list"
                    aria-label="Contoh katalog merchant"
                >
                    <div
                        v-for="(merchant, idx) in showcaseMerchants"
                        :key="merchant.name"
                        class="min-w-[320px] sm:min-w-[360px] md:min-w-[400px] snap-start shrink-0"
                        role="listitem"
                    >
                        <div class="rounded-2xl border border-zinc-800/60 bg-zinc-900/40 p-5 md:p-6 transition-all duration-300 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5 hover:-translate-y-1 h-full flex flex-col">
                            <!-- Merchant profile card -->
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold shrink-0"
                                    :class="merchant.colors.badge"
                                    aria-hidden="true"
                                >
                                    {{ merchant.name.charAt(0) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-white truncate">{{ merchant.name }}</p>
                                        <span v-if="merchant.badges.includes('verified')" class="shrink-0 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-[#FFD700]/10 text-[#FFD700] border border-[#FFD700]/30" title="Verified Seller">
                                            ✔ Verified
                                        </span>
                                        <span v-if="merchant.badges.includes('inclusive')" class="shrink-0 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30" title="UMKM Inklusif">
                                            ♿ Inklusif
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-zinc-500 truncate">{{ merchant.category }}</p>
                                </div>
                            </div>

                            <!-- Phone mockup -->
                            <div class="relative rounded-xl border border-zinc-800/60 bg-zinc-900/80 overflow-hidden mb-4">
                                <!-- Phone header -->
                                <div class="px-3 pt-3 pb-2 bg-zinc-800/50 border-b border-zinc-800/60">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-6 h-6 rounded-full bg-zinc-700/60 flex items-center justify-center text-[10px] font-bold text-zinc-300" aria-hidden="true">
                                            {{ merchant.name.charAt(0) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[11px] font-semibold text-white truncate leading-tight">{{ merchant.name }}</p>
                                            <p class="text-[9px] text-zinc-500 truncate leading-tight">{{ merchant.category }}</p>
                                        </div>
                                        <div class="ml-auto shrink-0" aria-hidden="true">
                                            <svg class="w-3 h-3 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-zinc-500 leading-relaxed line-clamp-1">{{ merchant.desc }}</p>
                                    <div class="mt-1.5 flex flex-wrap gap-1">
                                        <span v-if="merchant.badges.includes('verified')" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[8px] font-bold bg-[#FFD700]/10 text-[#FFD700] border border-[#FFD700]/30">
                                            ✔ Verified
                                        </span>
                                        <span v-if="merchant.badges.includes('inclusive')" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[8px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            ♿ Inklusif
                                        </span>
                                    </div>
                                </div>

                                <!-- Products -->
                                <div class="px-3 py-2.5 space-y-2">
                                    <p class="text-[9px] font-semibold text-zinc-500 uppercase tracking-wider">Produk</p>
                                    <div
                                        v-for="(product, pi) in merchant.products"
                                        :key="pi"
                                        class="flex items-center gap-2.5 p-2 rounded-lg bg-zinc-800/40 border border-zinc-800/60"
                                    >
                                        <div class="w-8 h-8 rounded-lg bg-zinc-800/60 flex items-center justify-center text-sm shrink-0" aria-hidden="true">{{ product.emoji }}</div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-medium text-white truncate">{{ product.name }}</p>
                                            <p class="text-[10px] text-zinc-500">{{ product.price }}</p>
                                        </div>
                                        <span class="text-[9px] text-emerald-400 font-medium shrink-0">WhatsApp</span>
                                    </div>
                                </div>

                                <!-- Bottom actions -->
                                <div class="px-3 py-2 border-t border-zinc-800/60 flex items-center gap-2">
                                    <span class="flex items-center gap-1 text-[9px] text-emerald-400 font-medium">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        Hubungi
                                    </span>
                                    <span class="ml-auto inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#FFD700]/10 text-[#FFD700] text-[9px] font-bold hover:bg-[#FFD700]/20 transition-colors cursor-default">
                                        Lihat Produk →
                                    </span>
                                </div>
                            </div>

                            <!-- Stats row -->
                            <div class="flex items-center justify-between text-center mt-auto pt-3 border-t border-zinc-800/40">
                                <div>
                                    <p class="text-xs font-bold text-white">{{ merchant.stats.produk }}</p>
                                    <p class="text-[10px] text-zinc-500">Produk</p>
                                </div>
                                <div class="w-px h-8 bg-zinc-800/60" aria-hidden="true" />
                                <div>
                                    <p class="text-xs font-bold text-white">{{ merchant.stats.kunjungan }}</p>
                                    <p class="text-[10px] text-zinc-500">Kunjungan</p>
                                </div>
                                <div class="w-px h-8 bg-zinc-800/60" aria-hidden="true" />
                                <div>
                                    <p class="text-xs font-bold text-white">{{ merchant.stats.pesanan }}</p>
                                    <p class="text-[10px] text-zinc-500">Pesanan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA under carousel -->
                <div class="text-center mt-10">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex items-center justify-center gap-2 bg-[#FFD700] text-black font-semibold px-7 py-3.5 rounded-xl hover:bg-yellow-300 transition-all hover:scale-[1.02] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 shadow-lg shadow-yellow-500/20"
                    >
                        Buat Katalog Gratis
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ==================== LAYANAN ==================== -->
        <section id="layanan" class="px-6 py-16 md:py-24">
            <div class="max-w-6xl mx-auto">
                <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Layanan Digital untuk UMKM yang <span class="text-[#FFD700]">Lebih Inklusif</span>
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        EtalaseKu membantu UMKM membuat katalog digital, menerima pesanan, membangun kepercayaan, dan menjangkau pelanggan termasuk pengguna disabilitas.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div
                        v-for="(item, i) in layanan"
                        :key="i"
                        class="group rounded-2xl border border-zinc-800/60 bg-zinc-900/50 p-5 md:p-6 transition-all duration-300 hover:border-yellow-500/30 hover:bg-zinc-900/80 hover:shadow-lg hover:shadow-yellow-500/5 hover:-translate-y-1"
                    >
                        <div class="w-10 h-10 rounded-xl bg-[#FFD700]/10 flex items-center justify-center mb-4 group-hover:bg-[#FFD700]/20 transition-colors" aria-hidden="true">
                            <svg v-if="i === 0" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                            <svg v-else-if="i === 1" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                            <svg v-else-if="i === 2" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                            <svg v-else-if="i === 3" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            <svg v-else-if="i === 4" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                            <svg v-else-if="i === 5" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                            <svg v-else-if="i === 6" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            <svg v-else-if="i === 7" class="w-5 h-5 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-white mb-1.5">{{ item.title }}</h3>
                        <p class="text-xs text-zinc-400 leading-relaxed">{{ item.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== UNTUK SIAPA ==================== -->
        <section id="untuk-siapa" class="px-6 py-16 md:py-24">
            <div class="max-w-6xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Dibuat untuk <span class="text-[#FFD700]">Berbagai</span> Jenis UMKM
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        Dari usaha kuliner hingga produk dan layanan ramah disabilitas, EtalaseKu membantu pelaku usaha membangun katalog digital yang lebih profesional, terpercaya, dan mudah diakses oleh semua orang.
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div
                        v-for="item in untukSiapa"
                        :key="item.label"
                        class="text-center p-5 rounded-2xl border border-zinc-800/60 bg-zinc-900/30 hover:bg-zinc-900/60 hover:border-zinc-700/60 transition-all duration-300 hover:-translate-y-1"
                    >
                        <div class="w-10 h-10 rounded-full bg-zinc-800/60 flex items-center justify-center mx-auto mb-3" aria-hidden="true">
                            <svg v-if="item.icon === 'cup'" class="w-5 h-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                            <svg v-else-if="item.icon === 'palette'" class="w-5 h-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" /></svg>
                            <svg v-else-if="item.icon === 'shirt'" class="w-5 h-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15l3-3m0 0l3 3m-3-3v6m-6 0h12" /></svg>
                            <svg v-else-if="item.icon === 'heart'" class="w-5 h-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                            <svg v-else-if="item.icon === 'tools'" class="w-5 h-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-7.2 7.2a1.5 1.5 0 01-2.12-2.12l7.2-7.2m2.83-2.83l7.2-7.2a1.5 1.5 0 012.12 2.12l-7.2 7.2" /></svg>
                            <svg v-else-if="item.icon === 'users'" class="w-5 h-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                        </div>
                        <p class="text-xs font-medium text-zinc-300">{{ item.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== INKLUSIVITAS ==================== -->
        <section class="px-6 py-16 md:py-24 bg-zinc-900/30">
            <div class="max-w-5xl mx-auto">
                <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FFD700]/10 border border-[#FFD700]/20 text-[#FFD700] text-xs font-medium mb-4" role="status">
                            Aksesibilitas
                        </div>
                        <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight leading-tight">
                            Dibangun untuk UMKM,
                            <br />
                            <span class="text-[#FFD700]">dirancang agar bisa</span>
                            <br />
                            diakses semua orang
                        </h2>
                        <p class="mt-4 text-sm md:text-base text-zinc-400 leading-relaxed">
                            Kami percaya bahwa setiap pelaku UMKM berhak memiliki kehadiran digital yang profesional. Dan setiap pelanggan berhak mengaksesnya dengan nyaman — termasuk mereka yang memiliki keterbatasan penglihatan, pendengaran, atau motorik.
                        </p>

                        <ul class="mt-6 space-y-3">
                            <li v-for="(text, i) in ['Setiap produk bisa diberi alt text untuk pembaca layar.', 'Tampilan dengan kontras tinggi dan font jelas.', 'Navigasi sederhana yang bisa diakses dengan keyboard.', 'Informasi toko, kontak, dan lokasi tersusun rapi.', 'Tombol WhatsApp dan lokasi mudah ditemukan.']" :key="i" class="flex items-start gap-3 text-sm text-zinc-400">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>{{ text }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="relative flex justify-center md:justify-end">
                        <div class="w-full max-w-sm rounded-2xl border border-zinc-800/60 bg-zinc-900/50 p-6 md:p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <svg class="w-8 h-8 text-[#FFD700]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                                <div>
                                    <p class="text-sm font-semibold text-white">Standar WCAG AA</p>
                                    <p class="text-xs text-zinc-500">Aksesibilitas tingkat tinggi</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-zinc-800/40 border border-zinc-800/60">
                                    <svg class="w-5 h-5 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                    <span class="text-xs text-zinc-400">Alt text pada setiap produk</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-zinc-800/40 border border-zinc-800/60">
                                    <svg class="w-5 h-5 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11.25l3-3m0 0l3 3m-3-3v7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-xs text-zinc-400">Navigasi keyboard penuh</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-zinc-800/40 border border-zinc-800/60">
                                    <svg class="w-5 h-5 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                    <span class="text-xs text-zinc-400">Kontras tinggi & font jelas</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-zinc-800/40 border border-zinc-800/60">
                                    <svg class="w-5 h-5 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                                    <span class="text-xs text-zinc-400">Navigasi sederhana & intuitif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== CARA KERJA ==================== -->
        <section class="px-6 py-16 md:py-24">
            <div class="max-w-4xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Cara <span class="text-[#FFD700]">Kerja</span>
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        Mulai buat katalog digital UMKM Anda dalam 3 langkah mudah.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                    <div v-for="item in caraKerja" :key="item.step" class="relative text-center p-6 md:p-8 rounded-2xl border border-zinc-800/60 bg-zinc-900/30">
                        <div class="w-12 h-12 rounded-full bg-[#FFD700] text-black text-lg font-bold flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                            {{ item.step }}
                        </div>
                        <h3 class="text-base font-semibold text-white mb-2">{{ item.title }}</h3>
                        <p class="text-sm text-zinc-400 leading-relaxed">{{ item.desc }}</p>

                        <!-- Arrow connector -->
                        <div v-if="item.step !== '3'" class="hidden md:block absolute top-1/2 -right-5 text-zinc-600" aria-hidden="true">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" /></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== PRICING ==================== -->
        <section id="harga" class="px-6 py-16 md:py-24 bg-zinc-900/30">
            <div class="max-w-5xl mx-auto">
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Pilih Paket yang Sesuai untuk <span class="text-[#FFD700]">Usaha Anda</span>
                    </h2>
                    <p class="mt-4 text-zinc-400 text-sm md:text-base leading-relaxed">
                        Mulai dari katalog digital gratis hingga fitur lengkap untuk UMKM yang ingin tumbuh lebih profesional dan inklusif.
                    </p>
                </div>

                <!-- Pricing Cards -->
                <!-- Grid matches table columns: Fitur(4/10) | Gratis(3/10) | Pro(3/10) -->
                <div class="grid grid-cols-1 md:grid-cols-10">
                    <!-- Spacer matching "Fitur" column width -->
                    <div class="hidden md:block md:col-span-4"></div>

                    <div
                        v-for="plan in plans"
                        :key="plan.name"
                        class="md:col-span-3"
                        :class="plan.highlighted ? 'md:pl-3' : 'md:pr-3'"
                    >
                        <div
                            class="relative rounded-xl border p-5 flex flex-col transition-all duration-300"
                            :class="plan.highlighted
                                ? 'border-[#FFD700]/40 bg-[#003366]/10 shadow-lg shadow-[#FFD700]/5'
                                : 'border-zinc-800/60 bg-zinc-900/50 hover:border-zinc-700/60'"
                        >
                            <!-- Badge -->
                            <div v-if="plan.highlighted" class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-xs font-bold bg-[#FFD700] text-black whitespace-nowrap">
                                PALING POPULER
                            </div>
                            <div v-else class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-xs font-bold bg-zinc-800 text-zinc-400">
                                GRATIS
                            </div>

                            <div class="text-center pt-1">
                                <h3 class="text-base font-semibold text-white">{{ plan.name }}</h3>
                                <div class="mt-2">
                                    <span class="text-2xl md:text-3xl font-extrabold text-white">{{ plan.price }}</span>
                                    <span v-if="plan.period" class="text-sm text-zinc-400 ml-1">{{ plan.period }}</span>
                                </div>
                                <p class="mt-2 text-xs text-zinc-400 leading-relaxed">
                                    {{ plan.description }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <Link
                                    :href="route(plan.routeName)"
                                    class="block w-full text-center py-2.5 rounded-xl text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                                    :class="plan.highlighted
                                        ? 'bg-[#FFD700] text-black hover:bg-yellow-300'
                                        : 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700 hover:text-white border border-zinc-700/60'"
                                >
                                    {{ plan.cta }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparison Table -->
                <div class="mt-8 rounded-2xl border border-zinc-800/60 bg-zinc-900/30 overflow-hidden">
                    <div class="px-6 md:px-8 pt-6 pb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Perbandingan Fitur</span>
                    </div>

                    <div class="overflow-x-auto px-6 md:px-8 pb-6 md:pb-8">
                        <table class="w-full min-w-[500px] text-sm" aria-label="Tabel perbandingan fitur paket">
                            <caption class="sr-only">
                                Perbandingan fitur antara paket Gratis dan Pro
                            </caption>
                            <thead>
                                <tr class="border-b border-zinc-800/60">
                                    <th scope="col" class="text-left py-3 pr-4 font-semibold text-zinc-300 w-2/5">Fitur</th>
                                    <th scope="col" class="text-center py-3 px-4 font-semibold text-zinc-400 w-3/10">Gratis</th>
                                    <th scope="col" class="text-center py-3 px-4 font-semibold text-[#FFD700] w-3/10">Pro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="group in comparisonFeatures" :key="group.category">
                                    <tr class="border-b border-zinc-800/40">
                                        <td colspan="3" class="py-2.5 pr-4 font-semibold text-xs uppercase tracking-wider text-[#FFD700]">
                                            {{ group.category }}
                                        </td>
                                    </tr>
                                    <tr v-for="row in group.rows" :key="row.label" class="border-b border-zinc-800/20 last:border-0">
                                        <td class="py-3 pr-4 text-zinc-300">{{ row.label }}</td>
                                        <td class="text-center py-3 px-4">
                                            <span v-if="row.gratis === true" class="text-emerald-400 text-base" aria-label="Ya">✓</span>
                                            <span v-else-if="row.gratis === false" class="text-zinc-600" aria-label="Tidak">✗</span>
                                            <span v-else class="text-zinc-400 text-xs">{{ row.gratis }}</span>
                                        </td>
                                        <td class="text-center py-3 px-4">
                                            <span v-if="row.pro === true" class="text-[#FFD700] text-base" aria-label="Ya">✓</span>
                                            <span v-else-if="row.pro === false" class="text-zinc-600" aria-label="Tidak">✗</span>
                                            <span v-else class="text-[#FFD700] text-xs font-medium">{{ row.pro }}</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== TRUST / ANTI SCAM ==================== -->
        <section class="px-6 py-16 md:py-24">
            <div class="max-w-5xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Belanja <span class="text-[#FFD700]">Aman</span> & Terpercaya
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        Kami menjaga ekosistem tetap aman bagi penjual dan pembeli.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="item in trustPoints" :key="item.text" class="flex items-center gap-3 p-4 rounded-xl border border-zinc-800/60 bg-zinc-900/40">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-zinc-300">{{ item.text }}</span>
                    </div>
                </div>

                <div class="mt-8 p-5 rounded-2xl bg-yellow-500/5 border border-yellow-500/10 text-center">
                    <p class="text-sm text-zinc-400">
                        <span class="text-yellow-400 font-semibold">EtalaseKu tidak memproses transaksi keuangan.</span>
                        Semua pembayaran dilakukan langsung antara pembeli dan penjual.
                        Kami hanya menyediakan platform katalog digital.
                    </p>
                </div>
            </div>
        </section>

        <!-- ==================== FAQ ==================== -->
        <section id="faq" class="px-6 py-16 md:py-24 bg-zinc-900/30">
            <div class="max-w-3xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                    <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight">
                        Pertanyaan <span class="text-[#FFD700]">Umum</span>
                    </h2>
                    <p class="mt-3 text-zinc-400 text-sm md:text-base">
                        Jawaban cepat untuk pertanyaan yang sering diajukan.
                    </p>
                </div>

                <div class="space-y-3">
                    <div
                        v-for="(item, i) in faq"
                        :key="i"
                        class="rounded-2xl border border-zinc-800/60 bg-zinc-900/40 overflow-hidden transition-all duration-200"
                    >
                        <button
                            class="w-full flex items-center justify-between gap-4 px-5 md:px-6 py-4 text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50"
                            @click="activeFaq = activeFaq === i ? null : i"
                            :aria-expanded="activeFaq === i"
                            :aria-controls="'faq-answer-' + i"
                        >
                            <span class="text-sm font-medium text-white">{{ item.q }}</span>
                            <svg
                                class="w-5 h-5 shrink-0 text-zinc-500 transition-transform duration-200"
                                :class="{ 'rotate-180': activeFaq === i }"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div
                            :id="'faq-answer-' + i"
                            v-show="activeFaq === i"
                            class="px-5 md:px-6 pb-4"
                            role="region"
                        >
                            <p class="text-sm text-zinc-400 leading-relaxed">{{ item.a }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== CTA BANNER ==================== -->
        <section class="relative px-6 py-16 md:py-24 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] rounded-full bg-yellow-500/5 blur-[100px] pointer-events-none" aria-hidden="true" />

            <div class="max-w-3xl mx-auto text-center relative z-10">
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
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex items-center justify-center gap-2 bg-[#FFD700] text-black font-semibold px-8 py-3.5 rounded-xl hover:bg-yellow-300 transition-all hover:scale-[1.02] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 shadow-lg shadow-yellow-500/20"
                    >
                        Mulai Gratis Sekarang
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ==================== FOOTER ==================== -->
        <footer class="border-t border-zinc-800 px-6 py-10 md:py-14" role="contentinfo">
            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <Link href="/" class="inline-flex items-center gap-2">
                            <img src="/images/image4-removebg-preview.png" alt="Logo EtalaseKu" class="h-10" />
                            <span class="text-2xl font-extrabold tracking-tight"><span class="text-white">Etalase</span><span class="text-[#FFD700]">Ku</span></span>
                        </Link>
                        <p class="mt-3 text-sm text-zinc-500 max-w-sm leading-relaxed">
                            Platform katalog digital inklusif untuk UMKM Indonesia. Satu tautan untuk semua produk, kontak, dan lokasi toko.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Menu</h3>
                        <ul class="space-y-2.5">
                            <li v-for="link in navLinks" :key="link.id">
                                <button
                                    @click="scrollTo(link.id)"
                                    class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded"
                                >
                                    {{ link.label }}
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Lainnya</h3>
                        <ul class="space-y-2.5">
                            <li>
                                <Link
                                    v-if="canLogin"
                                    :href="route('login')"
                                    class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded"
                                >
                                    Masuk
                                </Link>
                            </li>
                            <li>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded"
                                >
                                    Daftar
                                </Link>
                            </li>
                            <li>
                                <button
                                    @click="scrollTo('harga')"
                                    class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded"
                                >
                                    Harga
                                </button>
                            </li>
                            <li>
                                <button
                                    @click="scrollTo('faq')"
                                    class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400/50 rounded"
                                >
                                    FAQ
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-zinc-800/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-600">
                    <p>&copy; {{ new Date().getFullYear() }} EtalaseKu. Semua hak dilindungi.</p>
                    <p>
                        Dibangun dengan fokus pada
                        <span class="text-zinc-500">inklusi</span>
                        dan
                        <span class="text-zinc-500">aksesibilitas</span>
                        untuk UMKM Indonesia.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.skip-link {
    position: absolute;
    top: -100%;
    left: 1rem;
    padding: 0.5rem 1rem;
    background: #FFD700;
    color: #000;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.5rem;
    z-index: 100;
    transition: top 0.2s;
}
.skip-link:focus {
    top: 1rem;
}
</style>
