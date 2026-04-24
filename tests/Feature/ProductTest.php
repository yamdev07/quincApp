<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesTenant;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, CreatesTenant;

    private User $admin;
    private Tenant $tenant;
    private Category $category;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->makeTenant();
        $this->admin  = $this->makeAdminFor($this->tenant);

        $this->actingAs($this->admin);

        $this->category = Category::create([
            'name'      => 'Quincaillerie',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->supplier = Supplier::create([
            'name'      => 'CFAO Materials',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_manager_can_create_new_product(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name'           => 'Ciment Portland 50kg',
            'stock'          => 100,
            'stock_alert'    => 10,
            'purchase_price' => 3500,
            'sale_price'     => 4500,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name'      => 'Ciment Portland 50kg',
            'stock'     => 100,
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_creating_existing_product_cumulates_stock_not_duplicates(): void
    {
        // First creation
        $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name'           => 'Vis 8mm',
            'stock'          => 50,
            'purchase_price' => 100,
            'sale_price'     => 150,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $this->assertDatabaseCount('products', 1);
        $first = Product::first();

        // Same name → should restock, not create a new row
        $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name'           => 'Vis 8mm',
            'stock'          => 30,
            'purchase_price' => 120,
            'sale_price'     => 160,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $this->assertDatabaseCount('products', 1);
        $this->assertEquals(80, $first->fresh()->stock);
    }

    public function test_restocking_computes_weighted_average_price(): void
    {
        $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name'           => 'Clou 4 pouces',
            'stock'          => 100,
            'purchase_price' => 50,
            'sale_price'     => 80,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name'           => 'Clou 4 pouces',
            'stock'          => 100,
            'purchase_price' => 70,
            'sale_price'     => 80,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $product = Product::first();
        $this->assertEquals(200, $product->stock);
        // Weighted average: (100*50 + 100*70) / 200 = 60
        $this->assertEquals(60.00, (float) $product->purchase_price);
    }

    public function test_product_with_sale_items_is_soft_deleted_not_blocked(): void
    {
        $product = Product::create([
            'name'           => 'Produit vendu',
            'stock'          => 10,
            'purchase_price' => 100,
            'sale_price'     => 150,
            'tenant_id'      => $this->tenant->id,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $sale = Sale::create([
            'user_id'     => $this->admin->id,
            'tenant_id'   => $this->tenant->id,
            'total_price' => 150,
        ]);

        \App\Models\SaleItem::create([
            'sale_id'     => $sale->id,
            'product_id'  => $product->id,
            'quantity'    => 1,
            'unit_price'  => 150,
            'total_price' => 150,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        // Soft delete : ligne présente avec deleted_at, vente préservée
        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertDatabaseHas('sale_items', ['product_id' => $product->id]);
    }

    public function test_product_without_sales_is_soft_deleted(): void
    {
        $product = Product::create([
            'name'           => 'Produit neuf',
            'stock'          => 5,
            'purchase_price' => 100,
            'sale_price'     => 150,
            'tenant_id'      => $this->tenant->id,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_cashier_cannot_create_product(): void
    {
        $cashier = $this->makeCashierFor($this->tenant);

        $response = $this->actingAs($cashier)->post(route('admin.products.store'), [
            'name'           => 'Produit interdit',
            'stock'          => 10,
            'purchase_price' => 100,
            'sale_price'     => 150,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('products', ['name' => 'Produit interdit']);
    }
}
