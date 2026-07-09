<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Umkm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin User
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // 2. Create Owner 1 (Kuliner)
        $owner1 = User::create([
            'name' => 'Pemilik Kuliner',
            'email' => 'owner@mail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        // 3. Create Owner 2 (Fashion)
        $owner2 = User::create([
            'name' => 'Pemilik Fashion',
            'email' => 'owner_fashion@mail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        // 4. Create Owner 3 (Kerajinan)
        $owner3 = User::create([
            'name' => 'Pemilik Kerajinan',
            'email' => 'owner_craft@mail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        // 5. Create 3 UMKMs
        $umkm1 = Umkm::create([
            'owner_id' => $owner1->id,
            'name' => 'Warung Sedap Nusantara',
            'business_type' => 'kuliner',
            'address' => 'Jl. Kuliner No. 10, Jakarta',
            'phone' => '081234567890',
            'description' => 'Warung kuliner khas Nusantara dengan citarasa autentik.',
            'status' => 'active',
        ]);

        $umkm2 = Umkm::create([
            'owner_id' => $owner2->id,
            'name' => 'Butik Trendi Fashion',
            'business_type' => 'fashion',
            'address' => 'Jl. Mode No. 45, Bandung',
            'phone' => '082345678901',
            'description' => 'Pusat pakaian modern dan stylish terupdate.',
            'status' => 'active',
        ]);

        $umkm3 = Umkm::create([
            'owner_id' => $owner3->id,
            'name' => 'Kriya Kayu Craft',
            'business_type' => 'kerajinan',
            'address' => 'Jl. Pengrajin No. 7, Yogyakarta',
            'phone' => '083456789012',
            'description' => 'Kerajinan seni pahat kayu bermutu tinggi.',
            'status' => 'active',
        ]);

        // Link owners back to their respective UMKMs
        $owner1->update(['umkm_id' => $umkm1->id]);
        $owner2->update(['umkm_id' => $umkm2->id]);
        $owner3->update(['umkm_id' => $umkm3->id]);

        // 6. Create Staff for UMKM 1 (Kuliner)
        $staff = User::create([
            'name' => 'Kasir Kuliner',
            'email' => 'staff@mail.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => 'active',
            'umkm_id' => $umkm1->id,
        ]);

        // 7. Create 5 Categories
        $cat1 = Category::create(['umkm_id' => $umkm1->id, 'name' => 'Makanan Utama', 'status' => 'active']);
        $cat2 = Category::create(['umkm_id' => $umkm1->id, 'name' => 'Minuman Dingin', 'status' => 'active']);
        $cat3 = Category::create(['umkm_id' => $umkm2->id, 'name' => 'Pakaian Pria', 'status' => 'active']);
        $cat4 = Category::create(['umkm_id' => $umkm2->id, 'name' => 'Pakaian Wanita', 'status' => 'active']);
        $cat5 = Category::create(['umkm_id' => $umkm3->id, 'name' => 'Kerajinan Seni Pahat', 'status' => 'active']);

        // 8. Create 15 Products
        // UMKM 1 Products
        $p1 = Product::create([
            'umkm_id' => $umkm1->id, 'category_id' => $cat1->id, 'name' => 'Nasi Goreng Kampung',
            'sku' => 'NG001', 'description' => 'Nasi goreng tradisional dengan telur mata sapi.',
            'price' => 15000.00, 'stock' => 30, 'unit' => 'porsi', 'status' => 'active'
        ]);
        $p2 = Product::create([
            'umkm_id' => $umkm1->id, 'category_id' => $cat1->id, 'name' => 'Ayam Bakar Taliwang',
            'sku' => 'AB002', 'description' => 'Ayam bakar dengan bumbu khas Taliwang yang pedas manis.',
            'price' => 22000.00, 'stock' => 20, 'unit' => 'porsi', 'status' => 'active'
        ]);
        $p3 = Product::create([
            'umkm_id' => $umkm1->id, 'category_id' => $cat2->id, 'name' => 'Es Teh Manis Selasih',
            'sku' => 'ET003', 'description' => 'Es teh segar manis dengan biji selasih.',
            'price' => 5000.00, 'stock' => 100, 'unit' => 'gelas', 'status' => 'active'
        ]);
        $p4 = Product::create([
            'umkm_id' => $umkm1->id, 'category_id' => $cat2->id, 'name' => 'Jus Alpukat Kerok',
            'sku' => 'JA004', 'description' => 'Jus alpukat segar kental manis cokelat.',
            'price' => 10000.00, 'stock' => 3, 'unit' => 'gelas', 'status' => 'active' // warning stock
        ]);
        $p5 = Product::create([
            'umkm_id' => $umkm1->id, 'category_id' => $cat1->id, 'name' => 'Tempe Mendoan Hangat',
            'sku' => 'TM005', 'description' => 'Tempe mendoan digoreng dadakan 5 pcs.',
            'price' => 8000.00, 'stock' => 2, 'unit' => 'porsi', 'status' => 'active' // warning stock
        ]);

        // UMKM 2 Products
        $p6 = Product::create([
            'umkm_id' => $umkm2->id, 'category_id' => $cat3->id, 'name' => 'Kaos Polos Cotton 30s',
            'sku' => 'KP001', 'description' => 'Kaos polos katun dingin kualitas premium.',
            'price' => 45000.00, 'stock' => 50, 'unit' => 'pcs', 'status' => 'active'
        ]);
        $p7 = Product::create([
            'umkm_id' => $umkm2->id, 'category_id' => $cat3->id, 'name' => 'Kemeja Flanel Kotak',
            'sku' => 'KF002', 'description' => 'Kemeja flanel lengan panjang trendi.',
            'price' => 120000.00, 'stock' => 15, 'unit' => 'pcs', 'status' => 'active'
        ]);
        $p8 = Product::create([
            'umkm_id' => $umkm2->id, 'category_id' => $cat4->id, 'name' => 'Celana Chino Slimfit',
            'sku' => 'CC003', 'description' => 'Celana chino stretch slimfit modern.',
            'price' => 135000.00, 'stock' => 12, 'unit' => 'pcs', 'status' => 'active'
        ]);
        $p9 = Product::create([
            'umkm_id' => $umkm2->id, 'category_id' => $cat4->id, 'name' => 'Jaket Denim Vintage',
            'sku' => 'JD004', 'description' => 'Jaket denim tebal gaya vintage retro.',
            'price' => 195000.00, 'stock' => 4, 'unit' => 'pcs', 'status' => 'active' // warning stock
        ]);
        $p10 = Product::create([
            'umkm_id' => $umkm2->id, 'category_id' => $cat4->id, 'name' => 'Hijab Pashmina Plisket',
            'sku' => 'HP005', 'description' => 'Pashmina ceruty babydoll plisket rapi.',
            'price' => 25000.00, 'stock' => 40, 'unit' => 'pcs', 'status' => 'active'
        ]);

        // UMKM 3 Products
        $p11 = Product::create([
            'umkm_id' => $umkm3->id, 'category_id' => $cat5->id, 'name' => 'Patung Garuda Jati',
            'sku' => 'PG001', 'description' => 'Patung Garuda dipahat dari kayu jati solid.',
            'price' => 450000.00, 'stock' => 5, 'unit' => 'pcs', 'status' => 'active'
        ]);
        $p12 = Product::create([
            'umkm_id' => $umkm3->id, 'category_id' => $cat5->id, 'name' => 'Gantungan Kunci Kayu',
            'sku' => 'GK002', 'description' => 'Gantungan kunci pahat nama kustom.',
            'price' => 5000.00, 'stock' => 200, 'unit' => 'pcs', 'status' => 'active'
        ]);
        $p13 = Product::create([
            'umkm_id' => $umkm3->id, 'category_id' => $cat5->id, 'name' => 'Kotak Tisu Ukir Jepara',
            'sku' => 'KT003', 'description' => 'Kotak tisu dengan ukiran khas Jepara mewah.',
            'price' => 55000.00, 'stock' => 15, 'unit' => 'pcs', 'status' => 'active'
        ]);
        $p14 = Product::create([
            'umkm_id' => $umkm3->id, 'category_id' => $cat5->id, 'name' => 'Piring Anyaman Rotan',
            'sku' => 'PA004', 'description' => 'Piring anyaman rotan tradisional ramah lingkungan.',
            'price' => 7000.00, 'stock' => 100, 'unit' => 'pcs', 'status' => 'active'
        ]);
        $p15 = Product::create([
            'umkm_id' => $umkm3->id, 'category_id' => $cat5->id, 'name' => 'Lukisan Kaligrafi Kuningan',
            'sku' => 'LK005', 'description' => 'Lukisan kaligrafi timbul berlapis kuningan indah.',
            'price' => 250000.00, 'stock' => 1, 'unit' => 'pcs', 'status' => 'active' // warning stock
        ]);

        // Log Initial Stock Movements
        $allProducts = [$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14, $p15];
        foreach ($allProducts as $p) {
            StockMovement::create([
                'product_id' => $p->id,
                'type' => 'in',
                'qty' => $p->stock,
                'before_stock' => 0,
                'after_stock' => $p->stock,
                'notes' => 'Stok awal sistem',
                'created_by' => $admin->id,
            ]);
        }

        // 9. Create 5 Dummy Orders for UMKM 1 (Kuliner)
        // Order 1: Completed & Paid via Cash
        $o1 = Order::create([
            'umkm_id' => $umkm1->id, 'created_by' => $staff->id, 'customer_name' => 'Budi Santoso',
            'customer_phone' => '08122334455', 'order_date' => Carbon::now()->subHours(5),
            'total_amount' => 35000.00, 'status' => 'completed', 'notes' => 'Bungkus rapi.'
        ]);
        OrderItem::create(['order_id' => $o1->id, 'product_id' => $p1->id, 'qty' => 1, 'price' => 15000.00, 'subtotal' => 15000.00]);
        OrderItem::create(['order_id' => $o1->id, 'product_id' => $p2->id, 'qty' => 1, 'price' => 20000.00, 'subtotal' => 20000.00]);
        Payment::create([
            'order_id' => $o1->id, 'amount' => 40000.00, 'payment_method' => 'cash',
            'payment_status' => 'paid', 'paid_at' => Carbon::now()->subHours(5)
        ]);
        StockMovement::create([
            'product_id' => $p1->id, 'type' => 'out', 'qty' => 1,
            'before_stock' => 31, 'after_stock' => 30, 'notes' => "Pesanan #{$o1->id} selesai", 'created_by' => $staff->id
        ]);
        StockMovement::create([
            'product_id' => $p2->id, 'type' => 'out', 'qty' => 1,
            'before_stock' => 21, 'after_stock' => 20, 'notes' => "Pesanan #{$o1->id} selesai", 'created_by' => $staff->id
        ]);

        // Order 2: Completed & Paid via Transfer
        $o2 = Order::create([
            'umkm_id' => $umkm1->id, 'created_by' => $staff->id, 'customer_name' => 'Dewi Lestari',
            'customer_phone' => '08133445566', 'order_date' => Carbon::now()->subDays(1),
            'total_amount' => 40000.00, 'status' => 'completed', 'notes' => ''
        ]);
        OrderItem::create(['order_id' => $o2->id, 'product_id' => $p1->id, 'qty' => 2, 'price' => 15000.00, 'subtotal' => 30000.00]);
        OrderItem::create(['order_id' => $o2->id, 'product_id' => $p3->id, 'qty' => 2, 'price' => 5000.00, 'subtotal' => 10000.00]);
        Payment::create([
            'order_id' => $o2->id, 'amount' => 40000.00, 'payment_method' => 'transfer',
            'payment_status' => 'paid', 'paid_at' => Carbon::now()->subDays(1)
        ]);
        StockMovement::create([
            'product_id' => $p1->id, 'type' => 'out', 'qty' => 2,
            'before_stock' => 33, 'after_stock' => 31, 'notes' => "Pesanan #{$o2->id} selesai", 'created_by' => $staff->id
        ]);
        StockMovement::create([
            'product_id' => $p3->id, 'type' => 'out', 'qty' => 2,
            'before_stock' => 102, 'after_stock' => 100, 'notes' => "Pesanan #{$o2->id} selesai", 'created_by' => $staff->id
        ]);

        // Order 3: Pending & Unpaid via Transfer
        $o3 = Order::create([
            'umkm_id' => $umkm1->id, 'created_by' => $staff->id, 'customer_name' => 'Eko Prasetyo',
            'customer_phone' => '08144556677', 'order_date' => Carbon::now()->subHours(2),
            'total_amount' => 20000.00, 'status' => 'pending', 'notes' => 'Tunggu transfer bank.'
        ]);
        OrderItem::create(['order_id' => $o3->id, 'product_id' => $p4->id, 'qty' => 2, 'price' => 10000.00, 'subtotal' => 20000.00]);
        Payment::create([
            'order_id' => $o3->id, 'amount' => 20000.00, 'payment_method' => 'transfer',
            'payment_status' => 'unpaid', 'paid_at' => null
        ]);

        // Order 4: Processed & Unpaid via QRIS
        $o4 = Order::create([
            'umkm_id' => $umkm1->id, 'created_by' => $staff->id, 'customer_name' => 'Fani Safitri',
            'customer_phone' => '08155667788', 'order_date' => Carbon::now()->subHours(1),
            'total_amount' => 23000.00, 'status' => 'processed', 'notes' => 'Makan di tempat.'
        ]);
        OrderItem::create(['order_id' => $o4->id, 'product_id' => $p1->id, 'qty' => 1, 'price' => 15000.00, 'subtotal' => 15000.00]);
        OrderItem::create(['order_id' => $o4->id, 'product_id' => $p5->id, 'qty' => 1, 'price' => 8000.00, 'subtotal' => 8000.00]);
        Payment::create([
            'order_id' => $o4->id, 'amount' => 23000.00, 'payment_method' => 'qris',
            'payment_status' => 'unpaid', 'paid_at' => null
        ]);

        // Order 5: Cancelled (Never paid)
        $o5 = Order::create([
            'umkm_id' => $umkm1->id, 'created_by' => $staff->id, 'customer_name' => 'Gita Permata',
            'customer_phone' => '08166778899', 'order_date' => Carbon::now()->subHours(4),
            'total_amount' => 15000.00, 'status' => 'cancelled', 'notes' => 'Dibatalkan pelanggan.'
        ]);
        OrderItem::create(['order_id' => $o5->id, 'product_id' => $p1->id, 'qty' => 1, 'price' => 15000.00, 'subtotal' => 15000.00]);
    }
}
