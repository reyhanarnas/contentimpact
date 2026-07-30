<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users
        $admin = User::create([
            'name' => 'Admin ContentImpact',
            'email' => 'admin@contentimpact.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $editor = User::create([
            'name' => 'Editor CS',
            'email' => 'editor@contentimpact.com',
            'password' => bcrypt('password'),
            'role' => 'editor',
            'status' => 'active',
        ]);

        $journalist = User::create([
            'name' => 'Journalist Reyhan',
            'email' => 'journalist@contentimpact.com',
            'password' => bcrypt('password'),
            'role' => 'journalist',
            'status' => 'active',
        ]);



        // 2. Create Categories
        $categories = [
            [
                'name' => 'Politik',
                'slug' => 'politik',
                'description' => 'Berita politik nasional dan internasional terbaru.'
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Perkembangan gawai, software, AI, dan startup.'
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'ekonomi',
                'description' => 'Info keuangan, investasi, saham, dan pasar modal.'
            ],
            [
                'name' => 'Gaya Hidup',
                'slug' => 'gaya-hidup',
                'description' => 'Tips kesehatan, travel, kuliner, dan tren masa kini.'
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'description' => 'Jadwal, klasemen, dan hasil pertandingan sepakbola, basket, dan olahraga lainnya.'
            ]
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = \App\Models\Category::create($cat);
        }

        // 3. Create Articles
        $articles = [
            [
                'category_id' => $categoryModels[1]->id, // Teknologi
                'author_id' => $journalist->id,
                'title' => 'Revolusi AI: Bagaimana Model Bahasa Besar Mengubah Cara Kerja Programmer',
                'slug' => 'revolusi-ai-cara-kerja-programmer',
                'excerpt' => 'Kecerdasan Buatan (AI) kini bukan sekadar wacana. Dari Copilot hingga Gemini, alat bantu coding berbasis AI merevolusi kecepatan developer.',
                'content' => 'Kecerdasan Buatan (AI) telah menjadi pendorong utama transformasi digital di berbagai lini industri. Di dunia rekayasa perangkat lunak, kehadiran AI seperti GitHub Copilot, Gemini, dan ChatGPT telah mendefinisikan ulang cara kerja developer dalam menulis kode program. Menurut survei terbaru, lebih dari 70% pengembang perangkat lunak kini menggunakan setidaknya satu alat bantu kecerdasan buatan dalam pekerjaan harian mereka.

Akselerasi coding yang ditawarkan AI tidak main-main. Programmer kini dapat menulis fungsi kompleks, melakukan debugging, bahkan menerjemahkan kode antar bahasa pemrograman hanya dalam hitungan detik. AI bertindak sebagai asisten virtual yang cerdas, yang siap mendampingi 24 jam. Namun, hal ini juga memunculkan tantangan baru. Kebergantungan berlebih terhadap kode yang di-generate AI dapat mengakibatkan celah keamanan jika tidak divalidasi dengan cermat. Oleh karena itu, keterampilan membaca dan me-review kode menjadi jauh lebih penting dibanding sekadar menulis baris-baris kode kosong.',
                'cover_image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=600&auto=format&fit=crop',
                'status' => 'published',
                'views' => 1420,
                'published_at' => now()->subDays(2),
            ],
            [
                'category_id' => $categoryModels[0]->id, // Politik
                'author_id' => $journalist->id,
                'title' => 'Menakar Arah Kebijakan Ekonomi Pasca Pemilu Kepala Daerah Serentak',
                'slug' => 'menakar-arah-kebijakan-ekonomi-pasca-pilkada',
                'excerpt' => 'Pilkada serentak telah usai. Kini publik menanti bagaimana visi misi para pemimpin daerah terpilih bersinergi dengan program prioritas nasional.',
                'content' => 'Pelaksanaan Pemilihan Kepala Daerah (Pilkada) Serentak di seluruh wilayah Indonesia telah berjalan dengan aman dan kondusif. Agenda politik berskala masif ini menyisakan pekerjaan rumah krusial bagi kepala daerah terpilih: bagaimana merealisasikan janji kampanye di sektor ekonomi. Tantangan perlambatan ekonomi global dan inflasi lokal menuntut kolaborasi kuat antara pemerintah pusat dan daerah.

Pengamat kebijakan publik menilai bahwa sinergitas rencana pembangunan jangka menengah daerah (RPJMD) dengan program strategis nasional adalah kunci utama. Sektor investasi mikro, pengembangan pariwisata daerah, dan modernisasi UMKM berbasis digital diharapkan menjadi motor penggerak utama. Masyarakat berharap janji-janji kemudahan regulasi investasi dapat segera dieksekusi agar lapangan kerja baru segera terbuka lebar di daerah-daerah luar Jawa.',
                'cover_image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&w=600&auto=format&fit=crop',
                'status' => 'published',
                'views' => 875,
                'published_at' => now()->subDays(1),
            ],
            [
                'category_id' => $categoryModels[2]->id, // Ekonomi
                'author_id' => $journalist->id,
                'title' => 'Mengenal Investasi Hijau: Cuan Berkelanjutan untuk Masa Depan Bumi',
                'slug' => 'mengenal-investasi-hijau-cuan-berkelanjutan',
                'excerpt' => 'Investasi tidak lagi sekadar tentang margin keuntungan finansial semata. Tren ESG mendorong pertumbuhan portofolio hijau di kalangan gen-Z.',
                'content' => 'Dalam beberapa tahun terakhir, istilah investasi hijau (green investment) kian populer di kalangan investor muda. Konsep ini menekankan pada penempatan dana ke perusahaan atau proyek yang menerapkan praktik ramah lingkungan, ramah sosial, dan tata kelola yang baik (ESG - Environmental, Social, and Governance). Keuntungan investasi hijau tidak hanya diukur dari angka di neraca keuangan, namun juga dari dampak positifnya bagi pencegahan perubahan iklim global.

Laporan bursa efek menunjukkan peningkatan signifikan arus kas ke reksa dana bertema ESG. Investor menyadari bahwa emiten yang tidak peduli pada lingkungan cenderung menghadapi risiko regulasi dan reputasi yang tinggi di masa depan. Berbagai startup teknologi bersih (clean-tech), proyek energi terbarukan seperti pembangkit listrik tenaga surya (PLTS), dan pertanian organik kini menjadi target investasi utama. Memilih portofolio hijau kini dipandang sebagai langkah cerdas mengamankan profit sekaligus berkontribusi nyata demi keberlangsungan bumi.',
                'cover_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=600&auto=format&fit=crop',
                'status' => 'published',
                'views' => 195,
                'published_at' => now()->subHours(5),
            ],
            [
                'category_id' => $categoryModels[3]->id, // Gaya Hidup
                'author_id' => $journalist->id,
                'title' => 'Panduan Menjaga Work-Life Balance di Era Kerja Hybrid (WFA)',
                'slug' => 'panduan-work-life-balance-hybrid-wfa',
                'excerpt' => 'Bekerja dari mana saja memberikan fleksibilitas tinggi, namun batas antara pekerjaan dan kehidupan pribadi seringkali menjadi bias. Ini solusinya.',
                'content' => 'Konsep Work from Anywhere (WFA) atau kerja hybrid telah diadopsi secara luas oleh banyak perusahaan teknologi dan kreatif. Sistem ini dinilai meningkatkan produktivitas serta memberikan otonomi penuh pada karyawan. Sayangnya, tidak sedikit pekerja yang mengeluhkan burnout karena tidak mampu memisahkan jam kerja dengan waktu istirahat di rumah.

Untuk mengatasinya, diperlukan disiplin pribadi yang ketat. Pertama, buatlah ruang kerja khusus yang terpisah dari tempat tidur atau ruang keluarga. Kedua, tetapkan jam kerja yang konsisten dan komunikasikan ke rekan setim agar mereka tidak mengirimkan pesan di luar jam tersebut. Terakhir, matikan notifikasi aplikasi kerja setelah jam operasional berakhir. Memiliki waktu luang tanpa gadget kerja sangat krusial untuk mengisi ulang energi mental Anda.',
                'cover_image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=600&auto=format&fit=crop',
                'status' => 'pending_review',
                'views' => 0,
                'published_at' => null,
            ],
            [
                'category_id' => $categoryModels[4]->id, // Olahraga
                'author_id' => $journalist->id,
                'title' => 'Latihan Rutin 15 Menit di Rumah untuk Meningkatkan Kebugaran Jantung',
                'slug' => 'latihan-15-menit-rumah-kebugaran-jantung',
                'excerpt' => 'Kesibukan kerja tidak boleh menjadi alasan untuk mengabaikan olahraga. Simak panduan kardio intensitas sedang tanpa alat ini.',
                'content' => 'Banyak orang beranggapan bahwa untuk mendapatkan kebugaran kardiovaskular yang baik, mereka harus menghabiskan waktu berjam-jam di pusat kebugaran atau gym. Padahal, menurut para ahli kedokteran olahraga, aktivitas fisik dengan durasi singkat namun rutin jauh lebih bermanfaat bagi tubuh. Latihan sirkuit kardio selama 15 menit tanpa alat dapat menjadi solusi efektif di tengah padatnya aktivitas harian Anda.

Sesi latihan 15 menit ini dapat dibagi menjadi beberapa gerakan sederhana: jumping jacks, bodyweight squats, push-ups, mountain climbers, dan plank. Setiap gerakan dilakukan selama 45 detik, diikuti dengan istirahat 15 detik. Ulangi sirkuit ini sebanyak 3 kali putaran. Melakukan olahraga kardio rutin terbukti menurunkan risiko penyakit jantung koroner, melancarkan sirkulasi darah, serta memicu sekresi hormon endorfin yang meningkatkan suasana hati.',
                'cover_image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?q=80&w=600&auto=format&fit=crop',
                'status' => 'revision_required',
                'revision_note' => 'Mohon tambahkan ulasan dari ahli medis atau pelatih berlisensi untuk validitas data gerakan kardio.',
                'views' => 0,
                'published_at' => null,
            ]
        ];

        $articleModels = [];
        foreach ($articles as $art) {
            $articleModels[] = \App\Models\Article::create($art);
        }

        // Add a revision record for the article that requires revision
        \App\Models\ArticleRevision::create([
            'article_id' => $articleModels[4]->id,
            'editor_id' => $editor->id,
            'note' => 'Mohon tambahkan ulasan dari ahli medis atau pelatih berlisensi untuk validitas data gerakan kardio.',
        ]);

        // 4. Create Comments
        $comments = [
            [
                'article_id' => $articleModels[0]->id, // AI Article
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'comment' => 'Artikel yang sangat membuka wawasan! Sebagai programmer pemula, saya merasakan sekali bantuan AI dalam membantu belajar syntax baru dengan cepat.',
                'status' => 'approved'
            ],
            [
                'article_id' => $articleModels[0]->id, // AI Article
                'name' => 'Siti Aminah',
                'email' => 'siti@yahoo.com',
                'comment' => 'Betul sekali, validasi manual tetap nomor satu. Banyak rekan kerja saya yang asal copas codingan AI dan akhirnya aplikasinya banyak bug ketika dideploy.',
                'status' => 'approved'
            ],
            [
                'article_id' => $articleModels[1]->id, // Politik Article
                'name' => 'Fahri Hamza',
                'email' => 'fahri@outlook.com',
                'comment' => 'Semoga kepala daerah yang terpilih benar-benar peduli pada kemakmuran ekonomi rakyat kecil, bukan cuma modal retorika saat kampanye.',
                'status' => 'approved'
            ],
            [
                'article_id' => $articleModels[0]->id, // AI Article
                'name' => 'Spammer Toko',
                'email' => 'spammer@spambot.com',
                'comment' => 'Jual obat penurun berat badan murah! Klik link ini: http://spamlink.com',
                'status' => 'pending'
            ]
        ];

        foreach ($comments as $com) {
            \App\Models\Comment::create($com);
        }
    }
}
