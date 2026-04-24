<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesTenant;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase, CreatesTenant;

    private User $cashier;
    private Tenant $tenant;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant  = $this->makeTenant();
        $this->cashier = $this->makeCashierFor($this->tenant);

        $category = Category::create([
            'name'      => 'Matériaux',
            'tenant_id' => $this->tenant->id,
        ]);

        $supplier = Supplier::create([
            'name'      => 'Fournisseur Test',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->product = Product::create([
            'name'           => 'Ciment Portland',
            'stock'          => 50,
            'purchase_price' => 3500,
            'sale_price'     => 4500,
            'tenant_id'      => $this->tenant->id,
            'category_id'    => $category->id,
            'supplier_id'    => $supplier->id,
        ]);
    }

    public function test_cashier_can_create_sale(): void
    {
        $response = $this->actingAs($this->cashier)->post(route('sales.store'), [
            'client_id' => null,
            'products'  => [
                [
                    'product_id' => $this->product->id,
                    'quantity'   => 5,
                    'unit_price' => 4500,
                ],
            ],
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertDatabaseHas('sales', [
            'user_id'     => $this->cashier->id,
            'tenant_id'   => $this->tenant->id,
            'total_price' => 22500,
        ]);
        $this->assertEquals(45, $this->product->fresh()->stock);
    }

    public function test_sale_deducts_stock_correctly(): void
    {
        $initialStock = $this->product->stock;

        $this->actingAs($this->cashier)->post(route('sales.store'), [
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 10, 'unit_price' => 4500],
            ],
        ]);

        $this->assertEquals($initialStock - 10, $this->product->fresh()->stock);
    }

    public function test_sale_fails_when_stock_insufficient(): void
    {
        $response = $this->actingAs($this->cashier)->post(route('sales.store'), [
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 999, 'unit_price' => 4500],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('sales', 0);
        $this->assertEquals(50, $this->product->fresh()->stock);
    }

    public function test_sale_with_discount_reduces_total(): void
    {
        $this->actingAs($this->cashier)->post(route('sales.store'), [
            'discount' => 2000,
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 2, 'unit_price' => 4500],
            ],
        ]);

        $sale = Sale::where('tenant_id', $this->tenant->id)->latest()->first();
        $this->assertNotNull($sale);
        // 2 * 4500 - 2000 = 7000
        $this->assertEquals(7000, (float) $sale->total_price);
    }

    public function test_sale_with_client_links_client(): void
    {
        $client = Client::create([
            'name'      => 'Kouadio Kofi',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->actingAs($this->cashier)->post(route('sales.store'), [
            'client_id' => $client->id,
            'products'  => [
                ['product_id' => $this->product->id, 'quantity' => 1, 'unit_price' => 4500],
            ],
        ]);

        $sale = Sale::where('client_id', $client->id)->first();
        $this->assertNotNull($sale);
    }

    public function test_admin_can_cancel_sale_and_stock_is_restored(): void
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->actingAs($this->cashier)->post(route('sales.store'), [
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 5, 'unit_price' => 4500],
            ],
        ]);

        $this->assertEquals(45, $this->product->fresh()->stock);

        $sale = Sale::where('tenant_id', $this->tenant->id)->first();

        $response = $this->actingAs($admin)->delete(route('sales.destroy', $sale));

        $response->assertRedirect();
        $this->assertEquals(50, $this->product->fresh()->stock);
    }

    public function test_unauthenticated_user_cannot_create_sale(): void
    {
        $response = $this->post(route('sales.store'), [
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 1, 'unit_price' => 4500],
            ],
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('sales', 0);
    }
}
