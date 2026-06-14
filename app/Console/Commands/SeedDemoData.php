<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\CatalogVisit;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SeedDemoData extends Command
{
    protected $signature = 'demo:seed-data {--force : Force re-seeding even if data exists}';
    protected $description = 'Seed realistic demo data for EtalaseKu UMKM inclusive platform';

    public function handle(): void
    {
        if (!$this->option('force') && User::where('email', 'like', '%@etalaseku.demo')->exists()) {
            $this->warn('Demo data already exists. Use --force to re-seed.');
            return;
        }

        $this->info('=== Seed Demo Data EtalaseKu ===');
        $this->newLine();

        $categories = $this->createCategories();
        $merchants = $this->createMerchantsWithStores();
        $allProducts = $this->createProducts($merchants, $categories);
        $flatProducts = $allProducts->flatten();
        $checkouts = $this->createCheckouts($merchants, $flatProducts);
        $visits = $this->createCatalogVisits($merchants);

        $verified = $merchants->filter(fn($m) => $m->email_verified_at !== null)->count();
        $unverified = $merchants->count() - $verified;

        $this->newLine();
        $this->info('=== Ringkasan Data Demo ===');
        $this->table(
            ['Item', 'Jumlah'],
            [
                ['Kategori', count($categories)],
                ['Merchant', $merchants->count() . " ($verified verified, $unverified non-verified)"],
                ['Produk', $flatProducts->count()],
                ['Checkout', $checkouts->count()],
                ['Kunjungan Katalog', $visits->count()],
            ]
        );
        $this->newLine();
        $this->info('Demo data seeding completed successfully!');
    }

    private function createCategories(): \Illuminate\Support\Collection
    {
        $names = [
            'Alat Bantu Disabilitas',
            'Pendidikan Inklusif',
            'Kesehatan',
            'Fashion Adaptif',
            'Kerajinan',
            'Makanan & Minuman',
            'Jasa',
        ];

        $categories = collect();
        foreach ($names as $name) {
            $categories->push(Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => "Kategori $name untuk UMKM inklusif",
                    'is_active' => true,
                ]
            ));
        }

        return $categories;
    }

    private function createMerchantsWithStores(): \Illuminate\Support\Collection
    {
        $merchants = [
            [
                'name' => 'Akses Mandiri',
                'store_name' => 'Akses Mandiri',
                'store_description' => 'Menyediakan alat bantu mobilitas dan aksesibilitas untuk penyandang disabilitas. Memberdayakan kemandirian melalui produk berkualitas.',
                'whatsapp_number' => '6281212345001',
                'instagram_url' => 'https://instagram.com/aksesmandiri',
                'plan' => 'pro',
                'verified' => true,
            ],
            [
                'name' => 'Sahabat Tuli Indonesia',
                'store_name' => 'Sahabat Tuli Indonesia',
                'store_description' => 'Komunitas penyedia alat bantu dengar dan layanan penerjemah bahasa isyarat untuk teman-teman tuli.',
                'whatsapp_number' => '6281212345002',
                'instagram_url' => 'https://instagram.com/sahabattuli',
                'plan' => 'pro',
                'verified' => true,
            ],
            [
                'name' => 'Difabel Berkarya',
                'store_name' => 'Difabel Berkarya',
                'store_description' => 'Rumah kreatif yang memberdayakan pengrajin difabel untuk menghasilkan kerajinan tangan berkualitas tinggi.',
                'whatsapp_number' => '6281212345003',
                'instagram_url' => 'https://instagram.com/difabelberkarya',
                'plan' => 'pro',
                'verified' => true,
            ],
            [
                'name' => 'Inklusi Kreatif',
                'store_name' => 'Inklusi Kreatif',
                'store_description' => 'Menyediakan alat peraga edukasi dan media belajar inklusif untuk anak-anak dengan kebutuhan khusus.',
                'whatsapp_number' => '6281212345004',
                'plan' => 'pro',
                'verified' => true,
            ],
            [
                'name' => 'Toko Braille Nusantara',
                'store_name' => 'Toko Braille Nusantara',
                'store_description' => 'Pusat perlengkapan braille, alat tulis, dan media edukasi untuk tunanetra di seluruh Indonesia.',
                'whatsapp_number' => '6281212345005',
                'instagram_url' => 'https://instagram.com/braillenusantara',
                'plan' => 'pro',
                'verified' => true,
            ],
            [
                'name' => 'Rumah Terapi Anak',
                'store_name' => 'Rumah Terapi Anak',
                'store_description' => 'Menyediakan alat terapi dan stimulasi sensorik untuk anak-anak dengan kebutuhan perkembangan khusus.',
                'whatsapp_number' => '6281212345006',
                'plan' => 'free',
                'verified' => true,
            ],
            [
                'name' => 'Kursi Roda Sejahtera',
                'store_name' => 'Kursi Roda Sejahtera',
                'store_description' => 'Spesialis kursi roda dan alat bantu mobilitas dengan berbagai varian untuk kebutuhan disabilitas.',
                'whatsapp_number' => '6281212345007',
                'instagram_url' => 'https://instagram.com/kursirodasejahtera',
                'plan' => 'free',
                'verified' => true,
            ],
            [
                'name' => 'Karya Disabilitas Indonesia',
                'store_name' => 'Karya Disabilitas Indonesia',
                'store_description' => 'Rumah produksi batik, lukisan, dan kerajinan eksklusif hasil karya seniman disabilitas Indonesia.',
                'whatsapp_number' => '6281212345008',
                'instagram_url' => 'https://instagram.com/karyadisabilitas',
                'plan' => 'pro',
                'verified' => true,
            ],
            [
                'name' => 'Dengar Lebih Baik',
                'store_name' => 'Dengar Lebih Baik',
                'store_description' => 'Klinik alat bantu dengar dan aksesoris pendengaran untuk anak hingga lansia.',
                'whatsapp_number' => '6281212345009',
                'plan' => 'free',
                'verified' => true,
            ],
            [
                'name' => 'Adaptif Fashion',
                'store_name' => 'Adaptif Fashion',
                'store_description' => 'Busana adaptif yang mudah dipakai untuk lansia dan disabilitas, tanpa mengorbankan gaya.',
                'whatsapp_number' => '6281212345010',
                'instagram_url' => 'https://instagram.com/adaptiffashion',
                'plan' => 'free',
                'verified' => true,
            ],
            [
                'name' => 'Sentra UMKM Inklusif',
                'store_name' => 'Sentra UMKM Inklusif',
                'store_description' => 'Pusat oleh-oleh dan makanan ringan hasil produksi UMKM binaan komunitas disabilitas.',
                'whatsapp_number' => '6281212345011',
                'plan' => 'pro',
                'verified' => false,
            ],
            [
                'name' => 'Tangan Terampil',
                'store_name' => 'Tangan Terampil',
                'store_description' => 'Kerajinan tangan berkualitas hasil karya penyandang disabilitas yang terampil dan kreatif.',
                'whatsapp_number' => '6281212345012',
                'plan' => 'free',
                'verified' => false,
            ],
            [
                'name' => 'Lentera Inklusi',
                'store_name' => 'Lentera Inklusi',
                'store_description' => 'Menyediakan buku cerita inklusif, poster edukasi, dan alat peraga untuk pendidikan anak berkebutuhan khusus.',
                'whatsapp_number' => '6281212345013',
                'plan' => 'free',
                'verified' => false,
            ],
            [
                'name' => 'Alat Bantu Nusantara',
                'store_name' => 'Alat Bantu Nusantara',
                'store_description' => 'Distributor alat bantu jalan, tongkat medis, dan perlengkapan keselamatan lansia dan disabilitas.',
                'whatsapp_number' => '6281212345014',
                'plan' => 'pro',
                'verified' => false,
            ],
            [
                'name' => 'Toko Edukasi Braille',
                'store_name' => 'Toko Edukasi Braille',
                'store_description' => 'Spesialis buku dan perlengkapan belajar braille untuk tunanetra dari tingkat dasar hingga lanjutan.',
                'whatsapp_number' => '6281212345015',
                'plan' => 'free',
                'verified' => false,
            ],
            [
                'name' => 'Maju Bersama Difabel',
                'store_name' => 'Maju Bersama Difabel',
                'store_description' => 'Layanan jasa kreatif dan pelatihan digital untuk memberdayakan UMKM difabel di era digital.',
                'whatsapp_number' => '6281212345016',
                'instagram_url' => 'https://instagram.com/majubersamadifabel',
                'plan' => 'free',
                'verified' => false,
            ],
            [
                'name' => 'Pelita Akses',
                'store_name' => 'Pelita Akses',
                'store_description' => 'Solusi aksesibilitas digital dan perlengkapan adaptif untuk mendukung produktivitas penyandang disabilitas.',
                'whatsapp_number' => '6281212345017',
                'plan' => 'free',
                'verified' => false,
            ],
            [
                'name' => 'Bina Mandiri UMKM',
                'store_name' => 'Bina Mandiri UMKM',
                'store_description' => 'Makanan ringan dan olahan herbal sehat hasil produksi UMKM binaan komunitas disabilitas.',
                'whatsapp_number' => '6281212345018',
                'plan' => 'free',
                'verified' => false,
            ],
            [
                'name' => 'Solusi Mobilitas',
                'store_name' => 'Solusi Mobilitas',
                'store_description' => 'Penyedia skuter listrik, kursi roda premium, dan alat bantu mobilitas modern untuk disabilitas.',
                'whatsapp_number' => '6281212345019',
                'plan' => 'free',
                'verified' => false,
            ],
            [
                'name' => 'Komunitas Karya Inklusif',
                'store_name' => 'Komunitas Karya Inklusif',
                'store_description' => 'Koleksi kain tenun, rajutan, dan aksesoris etnik handmade oleh komunitas pengrajin disabilitas.',
                'whatsapp_number' => '6281212345020',
                'instagram_url' => 'https://instagram.com/komunitaskarya',
                'plan' => 'free',
                'verified' => false,
            ],
        ];

        $created = collect();
        foreach ($merchants as $data) {
            $slug = Str::slug($data['store_name']);
            $email = $slug . '@etalaseku.demo';

            $user = User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'merchant',
                'slug' => $slug,
                'store_name' => $data['store_name'],
                'store_slug' => $slug,
                'store_description' => $data['store_description'],
                'whatsapp_number' => $data['whatsapp_number'],
                'instagram_url' => $data['instagram_url'] ?? null,
                'plan' => $data['plan'],
            ]);

            if ($data['verified']) {
                $user->email_verified_at = now()->subDays(rand(10, 90));
                $user->save();
            }

            Store::create([
                'user_id' => $user->id,
                'name' => $data['store_name'],
                'slug' => $slug . '-store',
                'description' => $data['store_description'],
                'whatsapp' => $data['whatsapp_number'],
                'instagram' => $data['instagram_url'] ?? null,
                'is_active' => true,
            ]);

            $created->push($user);
        }

        return $created;
    }

    private function createProducts(\Illuminate\Support\Collection $merchants, \Illuminate\Support\Collection $categories): \Illuminate\Support\Collection
    {
        $categoryMap = [];
        foreach ($categories as $c) {
            $categoryMap[$c->name] = $c->id;
        }

        $productsByMerchant = collect();

        $productData = [
            'Akses Mandiri' => [
                'Alat Bantu Disabilitas' => [
                    ['name' => 'Tongkat Lipat Tunanetra', 'description' => 'Tongkat lipat putih reflektor untuk tunanetra. Ringan, kokoh, dan mudah dilipat saat tidak digunakan.', 'price' => 85000, 'tag' => 'Mobilitas, Best Seller'],
                    ['name' => 'Kursi Roda Lipat Ekonomis', 'description' => 'Kursi roda lipat rangka besi ringan, nyaman untuk mobilitas sehari-hari di dalam dan luar ruangan.', 'price' => 1200000, 'tag' => 'Mobilitas'],
                    ['name' => 'Walker Aluminium Ringan', 'description' => 'Walker lipat berbahan aluminium anti karat, tinggi adjustable, cocok untuk lansia dan pemulihan.', 'price' => 450000, 'tag' => 'Mobilitas, Best Seller'],
                    ['name' => 'Tongkat Kaki Empat', 'description' => 'Tongkat dengan 4 kaki penopang untuk keseimbangan maksimal, adjustable height.', 'price' => 120000, 'tag' => 'Mobilitas'],
                ],
            ],
            'Sahabat Tuli Indonesia' => [
                'Jasa' => [
                    ['name' => 'Hearing Aid Portable', 'description' => 'Alat bantu dengar portabel dengan volume adjustable, cocok untuk lansia dan tunarungu ringan.', 'price' => 750000, 'tag' => 'Tuli, Best Seller'],
                    ['name' => 'Papan Komunikasi Tuli', 'description' => 'Papan komunikasi visual bergambar untuk membantu interaksi sehari-hari tunarungu wicara.', 'price' => 65000, 'tag' => 'Tuli, Edukasi'],
                    ['name' => 'Buku Belajar Bahasa Isyarat', 'description' => 'Buku panduan belajar SIBI (Sistem Isyarat Bahasa Indonesia) untuk pemula lengkap dengan ilustrasi.', 'price' => 85000, 'tag' => 'Tuli, Edukasi, Baru'],
                    ['name' => 'Kelas Bahasa Isyarat Online', 'description' => 'Kelas online bahasa isyarat dasar untuk umum, gratis akses materi selama 1 bulan.', 'price' => 150000, 'type' => 'service', 'tag' => 'Tuli, Edukasi'],
                ],
            ],
            'Difabel Berkarya' => [
                'Kerajinan' => [
                    ['name' => 'Gelang Handmade Difabel', 'description' => 'Gelang anyaman tangan eksklusif buatan pengrajin difabel. Unik dan penuh makna.', 'price' => 35000, 'tag' => 'Best Seller'],
                    ['name' => 'Kerajinan Rajut Inklusif', 'description' => 'Syal dan topi rajut berkualitas tinggi buatan komunitas disabilitas, bahan wool premium.', 'price' => 55000, 'tag' => ''],
                    ['name' => 'Tas Anyaman Difabel', 'description' => 'Tas anyaman rotan sintetis buatan pengrajin difabel. Cocok untuk sehari-hari.', 'price' => 75000, 'tag' => 'Best Seller'],
                    ['name' => 'Dompet Kulit Sintetis', 'description' => 'Dompet kulit sintetis ramah lingkungan dengan jahitan tangan presisi buatan difabel.', 'price' => 45000, 'tag' => ''],
                ],
            ],
            'Inklusi Kreatif' => [
                'Pendidikan Inklusif' => [
                    ['name' => 'Flashcard Braille Anak', 'description' => 'Kartu belajar dengan huruf braille dan gambar timbul untuk anak tunanetra usia dini.', 'price' => 45000, 'tag' => 'Braille, Edukasi, Baru'],
                    ['name' => 'Buku Belajar Braille Dasar', 'description' => 'Buku pengantar membaca dan menulis braille untuk pemula, dilengkapi panduan praktis.', 'price' => 60000, 'tag' => 'Braille, Edukasi'],
                    ['name' => 'Puzzle Edukasi Adaptif', 'description' => 'Puzzle kayu dengan pegangan besar untuk anak dengan kesulitan motorik halus.', 'price' => 50000, 'tag' => 'Edukasi'],
                    ['name' => 'Mainan Sensorik Anak', 'description' => 'Set mainan sensorik dengan berbagai tekstur dan suara untuk stimulasi perkembangan anak.', 'price' => 85000, 'tag' => 'Edukasi, Baru'],
                ],
            ],
            'Toko Braille Nusantara' => [
                'Alat Bantu Disabilitas' => [
                    ['name' => 'Label Braille Custom', 'description' => 'Stiker label braille custom untuk keperluan marking, dapat dipesan sesuai teks yang diinginkan.', 'price' => 25000, 'tag' => 'Braille'],
                    ['name' => 'Mesin Ketik Braille', 'description' => 'Mesin ketik braille portable 6 tuts untuk menulis dokumen braille secara manual.', 'price' => 3500000, 'tag' => 'Braille, Best Seller'],
                    ['name' => 'Buku Tulis Braille', 'description' => 'Buku tulis bergaris braille untuk latihan menulis tunanetra, ukuran A4.', 'price' => 35000, 'tag' => 'Braille, Edukasi'],
                    ['name' => 'Papan Stensil Braille', 'description' => 'Papan stensil dan stylus untuk menulis braille manual, cocok untuk pemula.', 'price' => 40000, 'tag' => 'Braille, Edukasi'],
                ],
            ],
            'Rumah Terapi Anak' => [
                'Kesehatan' => [
                    ['name' => 'Sarung Tangan Terapi', 'description' => 'Sarung tangan terapi sensorik untuk stimulasi dan latihan motorik anak berkebutuhan khusus.', 'price' => 40000, 'tag' => 'Edukasi'],
                    ['name' => 'Bola Terapi Sensorik', 'description' => 'Set bola terapi dengan berbagai ukuran, tekstur, dan warna untuk stimulasi sensorik anak.', 'price' => 35000, 'tag' => 'Edukasi, Baru'],
                    ['name' => 'Alat Pijat Refleksi', 'description' => 'Alat pijat refleksi kaki kayu dengan rolling pin, membantu relaksasi dan terapi.', 'price' => 55000, 'tag' => ''],
                    ['name' => 'Matras Terapi Anak', 'description' => 'Matras lipat busa padat untuk terapi fisik dan bermain anak, ukuran 120x180cm.', 'price' => 120000, 'tag' => ''],
                ],
            ],
            'Kursi Roda Sejahtera' => [
                'Alat Bantu Disabilitas' => [
                    ['name' => 'Kursi Roda Ringan', 'description' => 'Kursi roda rangka aluminium super ringan, mudah dilipat dan dibawa bepergian.', 'price' => 1500000, 'tag' => 'Mobilitas, Best Seller'],
                    ['name' => 'Kursi Roda Motor Elektrik', 'description' => 'Kursi roda elektrik dengan motor penggerak, baterai tahan 8 jam untuk mobilitas mandiri.', 'price' => 4500000, 'tag' => 'Mobilitas'],
                    ['name' => 'Bantal Kursi Roda Anti Luka', 'description' => 'Bantal gel anti decubitus untuk mencegah luka tekan pada pengguna kursi roda.', 'price' => 85000, 'tag' => 'Mobilitas, Baru'],
                    ['name' => 'Tas Kursi Roda Multifungsi', 'description' => 'Tas gantung multifungsi untuk menyimpan barang di kursi roda, tahan air.', 'price' => 65000, 'tag' => 'Mobilitas'],
                ],
            ],
            'Karya Disabilitas Indonesia' => [
                'Kerajinan' => [
                    ['name' => 'Batik Tulis Disabilitas', 'description' => 'Kain batik tulis motif khas Indonesia, dibuat dengan penuh ketelitian oleh pengrajin difabel.', 'price' => 150000, 'tag' => 'Best Seller'],
                    ['name' => 'Lukisan Tangan Difabel', 'description' => 'Lukisan abstrak handmade di atas kanvas, karya seniman difabel Indonesia.', 'price' => 200000, 'tag' => ''],
                    ['name' => 'Gantungan Kunci Rajut', 'description' => 'Gantungan kunci rajut mini lucu, berbagai pilihan karakter dan warna.', 'price' => 15000, 'tag' => ''],
                    ['name' => 'Bros Handmade Eksklusif', 'description' => 'Bros cantik handmade dari manik-manik dan kain perca, eksklusif dan unik.', 'price' => 30000, 'tag' => ''],
                ],
            ],
            'Dengar Lebih Baik' => [
                'Alat Bantu Disabilitas' => [
                    ['name' => 'Hearing Aid Digital', 'description' => 'Alat bantu dengar digital dengan noise cancellation untuk kualitas suara jernih.', 'price' => 1200000, 'tag' => 'Tuli, Best Seller'],
                    ['name' => 'Alat Bantu Dengar Anak', 'description' => 'Alat bantu dengar khusus anak dengan desain ramah anak dan volume aman.', 'price' => 850000, 'tag' => 'Tuli'],
                    ['name' => 'Charger Hearing Aid', 'description' => 'Charger portable untuk alat bantu dengar digital dengan indikator LED.', 'price' => 75000, 'tag' => 'Tuli'],
                    ['name' => 'Silicone Ear Mould', 'description' => 'Cetakan telinga silikon custom untuk kenyamanan maksimal pemakaian alat bantu dengar.', 'price' => 50000, 'tag' => 'Tuli'],
                ],
            ],
            'Adaptif Fashion' => [
                'Fashion Adaptif' => [
                    ['name' => 'Baju Adaptif Lansia', 'description' => 'Kemeja dengan kancing magnet yang mudah dibuka-tutup untuk lansia dan difabel.', 'price' => 120000, 'tag' => 'Adaptif, Best Seller'],
                    ['name' => 'Sepatu Adaptif Difabel', 'description' => 'Sepatu dengan velcro dan elastis untuk memudahkan pemakaian tanpa perlu mengikat tali.', 'price' => 150000, 'tag' => 'Adaptif'],
                    ['name' => 'Celana Mudah Pakai', 'description' => 'Celana dengan karet pinggang elastis dan bukaan samping untuk kemudahan berganti.', 'price' => 95000, 'tag' => 'Adaptif'],
                    ['name' => 'Jaket Magnetic Adaptif', 'description' => 'Jaket dengan penutup magnetic dan resleting ringan yang mudah dijangkau.', 'price' => 135000, 'tag' => 'Adaptif, Baru'],
                ],
            ],
            'Sentra UMKM Inklusif' => [
                'Makanan & Minuman' => [
                    ['name' => 'Kopi UMKM Inklusif', 'description' => 'Kopi bubuk arabika pilihan hasil roasting UMKM binaan. Aroma kuat dan cita rasa khas.', 'price' => 25000, 'tag' => 'Best Seller'],
                    ['name' => 'Kue Kering Komunitas Difabel', 'description' => 'Aneka kue kering produksi komunitas difabel. Renyah, lezat, dan higienis.', 'price' => 45000, 'tag' => 'Best Seller'],
                    ['name' => 'Sirup Herbal UMKM', 'description' => 'Sirup herbal jahe dan lemon segar, tanpa pengawet buatan UMKM inklusif.', 'price' => 30000, 'tag' => ''],
                    ['name' => 'Camilan Sehat Inklusif', 'description' => 'Aneka camilan sehat rendah gula produksi UMKM binaan komunitas disabilitas.', 'price' => 20000, 'tag' => 'Baru'],
                ],
            ],
            'Tangan Terampil' => [
                'Kerajinan' => [
                    ['name' => 'Vas Bunga Keramik', 'description' => 'Vas bunga keramik buatan tangan dengan glasir berkualitas, cocok untuk dekorasi rumah.', 'price' => 65000, 'tag' => ''],
                    ['name' => 'Tempat Pensil Rajut', 'description' => 'Tempat pensil rajut warna-warni handmade, cocok untuk meja belajar atau kantor.', 'price' => 35000, 'tag' => ''],
                    ['name' => 'Taplak Meja Bordir', 'description' => 'Taplak meja bordir tangan dengan motif bunga tradisional Indonesia.', 'price' => 55000, 'tag' => ''],
                    ['name' => 'Keset Kaki Handmade', 'description' => 'Keset kaki dari serat alam buatan pengrajin difabel, tahan lama dan mudah dibersihkan.', 'price' => 40000, 'tag' => ''],
                ],
            ],
            'Lentera Inklusi' => [
                'Pendidikan Inklusif' => [
                    ['name' => 'Buku Cerita Inklusif', 'description' => 'Buku cerita bergambar dengan karakter disabilitas untuk menanamkan nilai inklusif pada anak.', 'price' => 45000, 'tag' => 'Edukasi, Baru'],
                    ['name' => 'Poster Edukasi Adaptif', 'description' => 'Set poster edukasi dengan huruf besar dan kontras tinggi untuk anak dengan low vision.', 'price' => 25000, 'tag' => 'Edukasi'],
                    ['name' => 'Kartu Belajar Sensorik', 'description' => 'Kartu belajar dengan berbagai tekstur untuk stimulasi taktil dan pengenalan bentuk.', 'price' => 35000, 'tag' => 'Edukasi'],
                    ['name' => 'Alat Peraga Matematika Adaptif', 'description' => 'Alat peraga berhitung dengan blok warna-warni untuk memudahkan belajar matematika.', 'price' => 60000, 'tag' => 'Edukasi'],
                ],
            ],
            'Alat Bantu Nusantara' => [
                'Alat Bantu Disabilitas' => [
                    ['name' => 'Tongkat Kaki Lipat', 'description' => 'Tongkat kaki 1 lipat adjustable dengan pegangan ergonomis untuk kenyamanan maksimal.', 'price' => 95000, 'tag' => 'Mobilitas'],
                    ['name' => 'Alat Bantu Jalan 3 Kaki', 'description' => 'Tripod walking stick dengan 3 titik tumpu untuk keseimbangan ekstra saat berjalan.', 'price' => 135000, 'tag' => 'Mobilitas'],
                    ['name' => 'Sabuk Keamanan Lansia', 'description' => 'Sabuk keamanan pinggang untuk membantu lansia saat berjalan atau berdiri.', 'price' => 65000, 'tag' => 'Mobilitas'],
                    ['name' => 'Handrail Toilet', 'description' => 'Handrail stainless steel untuk kamar mandi, membantu keseimbangan saat duduk dan berdiri.', 'price' => 85000, 'tag' => 'Mobilitas'],
                ],
            ],
            'Toko Edukasi Braille' => [
                'Pendidikan Inklusif' => [
                    ['name' => 'Buku Belajar Braille Lanjutan', 'description' => 'Buku panduan membaca braille tingkat lanjut untuk pengguna yang sudah menguasai dasar.', 'price' => 70000, 'tag' => 'Braille, Edukasi'],
                    ['name' => 'Alat Tulis Braille', 'description' => 'Stylus dan papan landasan untuk menulis braille manual, alat penting belajar braille.', 'price' => 20000, 'tag' => 'Braille, Edukasi'],
                    ['name' => 'Papan Tulisan Braille', 'description' => 'Papan tulis braille 4 baris dengan pemegang kertas untuk latihan menulis.', 'price' => 45000, 'tag' => 'Braille, Edukasi'],
                    ['name' => 'Kartu Angka Braille', 'description' => 'Set kartu angka braille dari 1-50 dengan timbulan rapi untuk belajar berhitung tunanetra.', 'price' => 25000, 'tag' => 'Braille, Edukasi'],
                ],
            ],
            'Maju Bersama Difabel' => [
                'Jasa' => [
                    ['name' => 'Jasa Desain Grafis Inklusif', 'description' => 'Jasa desain grafis ramah disabilitas untuk konten sosial media dan branding UMKM.', 'price' => 100000, 'type' => 'service', 'tag' => ''],
                    ['name' => 'Jasa Foto Produk', 'description' => 'Jasa foto produk untuk katalog UMKM dengan hasil berkualitas dan harga terjangkau.', 'price' => 75000, 'type' => 'service', 'tag' => ''],
                    ['name' => 'Pelatihan Digital Marketing UMKM', 'description' => 'Pelatihan pemasaran digital untuk UMKM pemula, dipandu oleh mentor berpengalaman.', 'price' => 50000, 'type' => 'service', 'tag' => 'Edukasi, Baru'],
                    ['name' => 'Jasa Pembuatan Katalog Digital', 'description' => 'Jasa pembuatan katalog digital untuk UMKM dengan tampilan profesional dan responsif.', 'price' => 250000, 'type' => 'service', 'tag' => ''],
                ],
            ],
            'Pelita Akses' => [
                'Alat Bantu Disabilitas' => [
                    ['name' => 'Keyboard Huruf Besar', 'description' => 'Keyboard USB dengan huruf besar kontras tinggi untuk pengguna low vision.', 'price' => 85000, 'tag' => 'Adaptif'],
                    ['name' => 'Mouse Ergonomis', 'description' => 'Mouse vertikal ergonomis untuk mengurangi kelelahan tangan, cocok untuk disabilitas motorik.', 'price' => 65000, 'tag' => 'Adaptif, Best Seller'],
                    ['name' => 'Meja Belajar Adaptif', 'description' => 'Meja belajar dengan tinggi adjustable dan kemiringan bidang untuk postur duduk yang tepat.', 'price' => 350000, 'tag' => 'Adaptif'],
                    ['name' => 'Lampu Belajar Adaptif', 'description' => 'Lampu belajar LED dengan intensitas cahaya adjustable dan sudut fleksibel.', 'price' => 55000, 'tag' => ''],
                ],
            ],
            'Bina Mandiri UMKM' => [
                'Makanan & Minuman' => [
                    ['name' => 'Abon Ikan UMKM', 'description' => 'Abon ikan tenggiri khas produksi komunitas difabel. Tanpa MSG dan pengawet.', 'price' => 35000, 'tag' => 'Best Seller'],
                    ['name' => 'Keripik Pisang Inklusif', 'description' => 'Keripik pisang manis dan gurih produksi UMKM binaan, dikemas dalam standing pouch.', 'price' => 15000, 'tag' => ''],
                    ['name' => 'Madu Asli UMKM', 'description' => 'Madu asli hutan Indonesia kemasan botol kaca, kaya khasiat untuk kesehatan.', 'price' => 50000, 'tag' => 'Best Seller'],
                    ['name' => 'Stik Keju Renyah', 'description' => 'Stik keju renyah produksi komunitas disabilitas, camilan sehat untuk segala usia.', 'price' => 18000, 'tag' => 'Baru'],
                    ['name' => 'Minuman Herbal Jahe', 'description' => 'Minuman herbal jahe instan kemasan sachet, hangat dan menyehatkan.', 'price' => 22000, 'tag' => ''],
                ],
            ],
            'Solusi Mobilitas' => [
                'Alat Bantu Disabilitas' => [
                    ['name' => 'Skuter Listrik Disabilitas', 'description' => 'Skuter roda 3 elektrik untuk mobilitas mandiri dengan kecepatan aman dan baterai tahan lama.', 'price' => 3500000, 'tag' => 'Mobilitas, Baru'],
                    ['name' => 'Kursi Roda Lipat Premium', 'description' => 'Kursi roda lipat premium rangka aluminium, dilengkapi footrest dan armrest adjustable.', 'price' => 2000000, 'tag' => 'Mobilitas, Best Seller'],
                    ['name' => 'Ramp Portable', 'description' => 'Ramp lipat aluminium untuk kursi roda, mudah dibawa dan dipasang di tangga/teras.', 'price' => 450000, 'tag' => 'Mobilitas'],
                    ['name' => 'Tongkat Lipat Carbon', 'description' => 'Tongkat lipat serat carbon ultra ringan untuk tunanetra, bobot hanya 200 gram.', 'price' => 175000, 'tag' => 'Mobilitas'],
                ],
            ],
            'Komunitas Karya Inklusif' => [
                'Kerajinan' => [
                    ['name' => 'Kain Tenun Inklusif', 'description' => 'Kain tenun tradisional Nusantara hasil karya pengrajin difabel, kualitas ekspor.', 'price' => 120000, 'tag' => 'Best Seller'],
                    ['name' => 'Gelang Persahabatan Difabel', 'description' => 'Gelang tali anyaman dengan simbol inklusivitas, cocok sebagai suvenir dan tanda persahabatan.', 'price' => 25000, 'tag' => ''],
                    ['name' => 'Hiasan Dinding Rajut', 'description' => 'Hiasan dinding makrame rajut handmade, estetik dan unik buatan komunitas difabel.', 'price' => 45000, 'tag' => 'Baru'],
                    ['name' => 'Gantungan Kunci Etnik', 'description' => 'Gantungan kunci etnik dari anyaman benang, khas Indonesia, buatan pengrajin difabel.', 'price' => 15000, 'tag' => ''],
                    ['name' => 'Tas Jinjing Handmade', 'description' => 'Tas jinjing from kain perca dengan kombinasi warna unik, ramah lingkungan.', 'price' => 55000, 'tag' => ''],
                ],
            ],
        ];

        foreach ($merchants as $merchant) {
            $store = Store::where('user_id', $merchant->id)->first();
            $merchantProducts = $productData[$merchant->store_name] ?? [];

            $merchantProductList = collect();
            foreach ($merchantProducts as $categoryName => $products) {
                $categoryId = $categoryMap[$categoryName] ?? null;

                foreach ($products as $p) {
                    $merchantProductList->push(Product::create([
                        'user_id' => $merchant->id,
                        'store_id' => $store?->id,
                        'category_id' => $categoryId,
                        'name' => $p['name'],
                        'description' => $p['description'],
                        'price' => $p['price'],
                        'type' => $p['type'] ?? 'physical',
                        'pricing_type' => $p['pricing_type'] ?? 'fixed',
                        'category' => $categoryName,
                        'tag' => $p['tag'],
                        'alt_text' => "Foto produk " . $p['name'] . " dari " . $merchant->store_name,
                        'is_active' => true,
                    ]));
                }
            }

            $productsByMerchant->put($merchant->id, $merchantProductList);
        }

        return $productsByMerchant;
    }

    private function createCheckouts(\Illuminate\Support\Collection $merchants, \Illuminate\Support\Collection $allProducts): \Illuminate\Support\Collection
    {
        $buyers = [
            ['name' => 'Ani Rahmawati', 'phone' => '6281211110001', 'email' => 'ani.rahmawati@gmail.com'],
            ['name' => 'Budi Santoso', 'phone' => '6281211110002', 'email' => 'budi.santoso@yahoo.com'],
            ['name' => 'Citra Dewi', 'phone' => '6281211110003', 'email' => 'citra.dewi@gmail.com'],
            ['name' => 'Deni Prakoso', 'phone' => '6281211110004', 'email' => 'deni.prakoso@gmail.com'],
            ['name' => 'Eka Fitriani', 'phone' => '6281211110005', 'email' => 'eka.fitriani@yahoo.com'],
            ['name' => 'Fajar Nugroho', 'phone' => '6281211110006', 'email' => 'fajar.nugroho@gmail.com'],
            ['name' => 'Gita Permata Sari', 'phone' => '6281211110007', 'email' => 'gita.permata@gmail.com'],
            ['name' => 'Hendra Gunawan', 'phone' => '6281211110008', 'email' => 'hendra.gunawan@gmail.com'],
            ['name' => 'Indah Lestari', 'phone' => '6281211110009', 'email' => 'indah.lestari@yahoo.com'],
            ['name' => 'Joko Susilo', 'phone' => '6281211110010', 'email' => 'joko.susilo@gmail.com'],
            ['name' => 'Kartika Sari Dewi', 'phone' => '6281211110011', 'email' => 'kartika.sari@gmail.com'],
            ['name' => 'Lukman Hakim', 'phone' => '6281211110012', 'email' => 'lukman.hakim@gmail.com'],
            ['name' => 'Maya Anggraini', 'phone' => '6281211110013', 'email' => 'maya.anggraini@yahoo.com'],
            ['name' => 'Nanda Pratama', 'phone' => '6281211110014', 'email' => 'nanda.pratama@gmail.com'],
            ['name' => 'Olivia Putri', 'phone' => '6281211110015', 'email' => 'olivia.putri@gmail.com'],
        ];

        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        $checkoutNotes = [
            null,
            'Terima kasih, mohon segera diproses',
            'Sudah transfer via BCA',
            'Alamat ada di profil WhatsApp',
            'Bisa dikirim hari ini?',
            'Ukuran tersedia semua ya?',
            'Sudah transfer Rp ',
        ];

        $checkouts = collect();
        $productIds = $allProducts->pluck('id')->toArray();
        $pickedIndices = [];

        for ($i = 0; $i < 30; $i++) {
            $buyer = $buyers[array_rand($buyers)];
            $productIdx = array_rand($productIds);
            while (in_array($productIdx, $pickedIndices) && count($pickedIndices) < count($productIds)) {
                $productIdx = array_rand($productIds);
            }
            $pickedIndices[] = $productIdx;

            $product = $allProducts->firstWhere('id', $productIds[$productIdx]);
            if (!$product) continue;

            $quantity = rand(1, 3);
            $totalPrice = $product->price * $quantity;
            $status = $statuses[array_rand($statuses)];
            $note = $checkoutNotes[array_rand($checkoutNotes)];

            if ($note && str_contains($note, 'Rp ')) {
                $note = $note . number_format($totalPrice, 0, ',', '.');
            }

            $daysAgo = rand(1, 25);
            $createdAt = now()->subDays($daysAgo)->addHours(rand(8, 20))->addMinutes(rand(0, 59));

            $checkouts->push(Checkout::create([
                'user_id' => $product->user_id,
                'product_id' => $product->id,
                'buyer_name' => $buyer['name'],
                'buyer_email' => $buyer['email'],
                'buyer_phone' => $buyer['phone'],
                'notes' => $note,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]));
        }

        return $checkouts;
    }

    private function createCatalogVisits(\Illuminate\Support\Collection $merchants): \Illuminate\Support\Collection
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148',
            'Mozilla/5.0 (Linux; Android 13; SM-S908E) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/120.0.0.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 Safari/605.1.15',
            'Mozilla/5.0 (Linux; Android 12; Redmi Note 11) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/121.0',
        ];

        $ips = [
            '192.168.1.' . rand(10, 200),
            '10.0.0.' . rand(10, 200),
            '172.16.0.' . rand(10, 200),
            '114.10.' . rand(1, 255) . '.' . rand(1, 255),
            '36.68.' . rand(1, 255) . '.' . rand(1, 255),
            '125.166.' . rand(1, 255) . '.' . rand(1, 255),
            '103.' . rand(10, 200) . '.' . rand(1, 255) . '.' . rand(1, 255),
        ];

        $visits = collect();
        $merchantIds = $merchants->pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            $merchantId = $merchantIds[array_rand($merchantIds)];
            $daysAgo = rand(0, 30);
            $hour = rand(7, 22);
            $minute = rand(0, 59);
            $visitedAt = now()->subDays($daysAgo)->setTime($hour, $minute, rand(0, 59));

            $visits->push(CatalogVisit::create([
                'user_id' => $merchantId,
                'visitor_ip' => $ips[array_rand($ips)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'visited_at' => $visitedAt,
                'created_at' => $visitedAt,
                'updated_at' => $visitedAt,
            ]));
        }

        return $visits;
    }

    protected function step(string $message): void
    {
        $this->output->write("  > $message ");
    }
}
