# Sistem Manajemen UMKM Terpadu (Laravel 11 & MySQL)

Sistem Manajemen UMKM Terpadu adalah aplikasi web berbasis Laravel 11 yang digunakan untuk mengelola data UMKM, kategori, produk, transaksi, laporan omzet harian/bulanan, stok, dan mutasi barang dengan sistem pembagian hak akses (role-based leveling).

## 🚀 Fitur Utama
1. **Multi-Role Access Control (Authentication & Authorization)**:
   - **Super Admin**: Mengelola seluruh UMKM, data user, melihat laporan dan omzet global.
   - **Pemilik UMKM (Owner)**: Mengelola profil UMKM miliknya, kategori, produk (stok, harga, info), mengelola staf, melihat pesanan, pembayaran, dan laporan omzet harian/bulanan toko sendiri dengan grafik.
   - **Staf / Kasir**: Membuat pesanan, mencatat pembayaran, mengupdate status pesanan pending.
   - **Customer / Guest**: Melihat profil UMKM aktif, menjelajahi katalog produk aktif, dan melakukan order/pemesanan mandiri.
2. **Keamanan Data Isolation (Policy)**: Mencegah kebocoran data antar-UMKM (Owner/Staf toko A tidak dapat melihat/mengedit data toko B).
3. **Pencatatan Audit Stok (Stock Movement)**: Otomatis memotong stok saat pesanan diselesaikan (Completed) dan mengembalikan stok jika pesanan dibatalkan (Cancelled).
4. **Grafik Omzet & Ekspor Laporan**: Visualisasi omzet harian 7 hari terakhir (Chart.js) dan ekspor laporan transaksi ke file Excel/CSV.

---

## 🛠️ Langkah Instalasi & Cara Menjalankan

Ikuti langkah-langkah di bawah ini untuk menjalankan project di lokal Anda:

1. **Clone/Buka Project**:
   Pastikan Anda berada di direktori project `C:\laragon\www\magang`.

2. **Instal Dependensi**:
   ```bash
   composer install
   ```

3. **Duplikat & Atur File `.env`**:
   Salin file konfigurasi lingkungan:
   ```bash
   cp .env.example .env
   ```
   *Secara default, konfigurasi database di `.env` sudah diatur untuk MySQL Laragon:*
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=magang_umkm
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding Data**:
   Pastikan MySQL server Laragon Anda sudah aktif, lalu jalankan:
   ```bash
   php artisan migrate --seed
   ```

6. **Membuat Link Storage Gambar**:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Aplikasi**:
   Jalankan server development Laravel:
   ```bash
   php artisan serve
   ```
   Buka browser dan akses [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 🔑 Akun Login Default (Seeded)

Gunakan akun-akun di bawah ini untuk menguji leveling hak akses:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `admin@mail.com` | `password` |
| **Pemilik UMKM (Owner)** | `owner@mail.com` | `password` |
| **Staf / Kasir** | `staff@mail.com` | `password` |

---

## 🧪 Menjalankan Pengujian (Testing)
Pengujian fitur otentikasi login dan CRUD produk dengan policy isolasi data dapat dijalankan melalui:
```bash
php artisan test
```

---

## 📝 Jawaban Pertanyaan Wawancara Teknis (Section 17)

1. **Jelaskan alur data ketika staff membuat order sampai pembayaran menjadi paid.**
   - Kasir memilih produk dari drop-down POS &rarr; Data diinput ke form kasir (nama pelanggan, HP, produk, quantity) &rarr; Request dikirim ke `POST /staff/orders` &rarr; Controller memvalidasi stok dan menghitung total harga &rarr; Order disimpan ke tabel `orders` dengan status `pending`, item-item disimpan ke `order_items` &rarr; Kasir memproses pembayaran melalui `POST /staff/orders/{order}/payment` &rarr; Payment disimpan ke tabel `payments` dengan status `paid` dan mencatat waktu `paid_at` &rarr; Status order diperbarui ke `completed` (memotong stok dan mencatatnya ke `stock_movements`).

2. **Bagaimana cara memastikan pemilik UMKM A tidak bisa melihat produk UMKM B?**
   - Menggunakan **Laravel Policy** (misal: `ProductPolicy`). Setiap kali endpoint produk diakses, sistem memvalidasi `auth()->user()->umkm_id === $product->umkm_id`. Jika tidak sama, sistem merespons dengan HTTP status `403 Forbidden`. Selain itu, query default list produk disaring berdasarkan UMKM aktif: `Product::where('umkm_id', auth()->user()->umkm_id)->get()`.

3. **Apa perbedaan authentication dan authorization?**
   - **Authentication (Otentikasi)**: Proses pembuktian identitas user (siapa Anda? Misal: memasukkan email & password untuk login).
   - **Authorization (Otorisasi)**: Proses pembuktian hak akses (apa yang boleh Anda lakukan? Misal: Kasir tidak boleh menghapus produk, Owner hanya boleh edit produk tokonya sendiri).

4. **Kapan sebaiknya menggunakan middleware dan kapan menggunakan policy?**
   - **Middleware**: Digunakan untuk validasi akses tingkat global/rute (misal: mengecek apakah user sudah login, atau memastikan user memiliki role `owner` secara umum sebelum masuk ke kelompok route `/owner/*`).
   - **Policy**: Digunakan untuk otorisasi tingkat data/spesifik model (misal: mengecek apakah model `Product` dengan ID tertentu dimiliki oleh UMKM dari user yang sedang login).

5. **Jelaskan perbedaan route parameter, query parameter, dan request body/form parameter.**
   - **Route Parameter**: Variabel yang tertanam langsung di dalam path URL. Ditulis dengan kurung kurawal, contoh: `/owner/products/{product}`.
   - **Query Parameter**: Variabel tambahan di akhir URL setelah tanda tanya `?`. Digunakan untuk sorting/filtering, contoh: `/owner/orders?status=completed`.
   - **Request Body/Form Parameter**: Data tersembunyi yang dikirim lewat form payload body (misal: `POST` request saat submit input produk baru).

6. **Apa fungsi with() pada Eloquent dan kenapa bisa mengurangi N+1 query?**
   - Fungsi `with()` digunakan untuk **Eager Loading**, yaitu memuat relasi database terkait secara sekaligus dalam satu query di awal. Ini mencegah masalah N+1 query di mana sistem menjalankan 1 query utama lalu menjalankan query relasi tambahan sebanyak N kali untuk setiap baris data.

7. **Bagaimana cara menghitung omzet harian dari tabel payments?**
   - Menghitung sum kolom `amount` dari tabel `payments` dengan kondisi `payment_status = 'paid'` dan `paid_at` sama dengan tanggal hari ini. Contoh Query:
     ```php
     Payment::where('payment_status', 'paid')
         ->whereDate('paid_at', Carbon::today())
         ->sum('amount');
     ```

8. **Apa yang terjadi pada stok ketika order dibatalkan setelah sebelumnya completed?**
   - Stok produk harus dikembalikan (restored). Kita melakukan penambahan kembali jumlah stok sesuai quantity pesanan (`increment`) dan mencatat mutasi masuk baru di `stock_movements` dengan tipe `in` dan catatan "Pesanan dibatalkan (pengembalian stok)".

9. **Bagaimana cara membuat SKU unik hanya per UMKM, bukan global?**
   - Pada migrasi database, buat indeks komposit unik: `$table->unique(['umkm_id', 'sku']);`. Pada Form Request validation, gunakan aturan unik bersyarat:
     ```php
     Rule::unique('products')->where(fn ($q) => $q->where('umkm_id', $umkmId))->ignore($productId)
     ```

10. **Apa penyebab umum error 500 di Laravel dan bagaimana cara mengeceknya?**
    - Penyebab umum: Syntax error, Exception yang tidak tertangkap, salah konfigurasi database, file permissions, atau API pihak ketiga mati. Cara mengeceknya adalah dengan melihat file log Laravel di `storage/logs/laravel.log`.

11. **Apa fungsi migration dan seeder?**
    - **Migration**: Version control untuk skema database (membuat, mengubah, dan menghapus tabel secara konsisten di setiap mesin dev).
    - **Seeder**: Pengisian database dengan data awal / dummy (membuat akun admin, data kategori bawaan, atau data transaksi uji coba).

12. **Apa fungsi php artisan optimize:clear?**
    - Membersihkan seluruh cache di Laravel secara instan, meliputi cache konfigurasi, rute, view, events, dan cache aplikasi umum untuk menghindari error cache stale.

13. **Bagaimana cara menangani upload gambar produk di Laravel?**
    - Menerima file dari Request, memvalidasi tipe file (`mimes:jpeg,png,webp`) dan ukuran (maks 2MB), menyimpannya di folder storage disk public (`$request->file('image')->store('products', 'public'`), lalu menyimpan path relatif tersebut ke dalam database.

14. **Bagaimana cara membuat pagination dan mempertahankan query filter saat pindah halaman?**
    - Gunakan fungsi `paginate(N)` di controller, lalu di file Blade panggil `{{ $products->links() }}`. Untuk mempertahankan query string (seperti keyword pencarian/filter) saat ganti halaman, tambahkan method `withQueryString()` pada pagination di controller atau di view.

15. **Kenapa total_amount order tidak boleh langsung dipercaya dari input frontend?**
    - Frontend dapat dimanipulasi dengan mudah oleh user nakal (misalnya memodifikasi total belanja di DevTools sebelum dikirim). Oleh karena itu, backend harus menghitung ulang total harga berdasarkan harga asli produk di database dikalikan quantity yang dikirim.
#   T u g a s M a g a n g  
 