<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Umkm;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $ownerA;
    private User $ownerB;
    private Umkm $umkmA;
    private Umkm $umkmB;
    private Category $categoryA;
    private Category $categoryB;

    protected function setUp(): void
    {
        parent::setUp();

        // Create UMKM A and Owner A
        $this->ownerA = User::create([
            'name' => 'Owner A',
            'email' => 'ownerA@mail.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        $this->umkmA = Umkm::create([
            'owner_id' => $this->ownerA->id,
            'name' => 'UMKM A',
            'business_type' => 'kuliner',
            'address' => 'Alamat A',
            'phone' => '0812',
            'status' => 'active',
        ]);
        $this->ownerA->update(['umkm_id' => $this->umkmA->id]);
        $this->categoryA = Category::create(['umkm_id' => $this->umkmA->id, 'name' => 'Kategori A', 'status' => 'active']);

        // Create UMKM B and Owner B
        $this->ownerB = User::create([
            'name' => 'Owner B',
            'email' => 'ownerB@mail.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        $this->umkmB = Umkm::create([
            'owner_id' => $this->ownerB->id,
            'name' => 'UMKM B',
            'business_type' => 'fashion',
            'address' => 'Alamat B',
            'phone' => '0813',
            'status' => 'active',
        ]);
        $this->ownerB->update(['umkm_id' => $this->umkmB->id]);
        $this->categoryB = Category::create(['umkm_id' => $this->umkmB->id, 'name' => 'Kategori B', 'status' => 'active']);
    }

    public function test_owner_can_create_product(): void
    {
        $response = $this->actingAs($this->ownerA)->post('/owner/products', [
            'category_id' => $this->categoryA->id,
            'name' => 'Produk Baru A',
            'sku' => 'SKU-A01',
            'description' => 'Deskripsi produk',
            'price' => 15000,
            'stock' => 10,
            'unit' => 'pcs',
            'status' => 'active',
        ]);

        $response->assertRedirect('/owner/products');
        $this->assertDatabaseHas('products', [
            'umkm_id' => $this->umkmA->id,
            'sku' => 'SKU-A01',
            'name' => 'Produk Baru A'
        ]);
    }

    public function test_owner_cannot_edit_other_umkm_product(): void
    {
        // Create product under UMKM B
        $productB = Product::create([
            'umkm_id' => $this->umkmB->id,
            'category_id' => $this->categoryB->id,
            'name' => 'Produk B',
            'sku' => 'SKU-B01',
            'price' => 20000,
            'stock' => 5,
            'unit' => 'pcs',
            'status' => 'active',
        ]);

        // Owner A tries to edit UMKM B's product
        $response = $this->actingAs($this->ownerA)->get("/owner/products/{$productB->id}/edit");
        
        $response->assertStatus(403); // Forbidden by policy
    }

    public function test_sku_must_be_unique_per_umkm(): void
    {
        // Create first product
        Product::create([
            'umkm_id' => $this->umkmA->id,
            'category_id' => $this->categoryA->id,
            'name' => 'Produk Pertama',
            'sku' => 'SKU-DUP',
            'price' => 10000,
            'stock' => 10,
            'unit' => 'pcs',
            'status' => 'active',
        ]);

        // Attempt to create second product with same SKU in same UMKM
        $response = $this->actingAs($this->ownerA)->post('/owner/products', [
            'category_id' => $this->categoryA->id,
            'name' => 'Produk Duplikat',
            'sku' => 'SKU-DUP',
            'price' => 12000,
            'stock' => 5,
            'unit' => 'pcs',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('sku');
    }
}
