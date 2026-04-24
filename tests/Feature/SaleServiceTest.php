<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\CreatesTenant;
use Tests\TestCase;

class SaleServiceTest extends TestCase
{
    use RefreshDatabase, CreatesTenant;

    private SaleService $service;
    private Product $product;
    private int $tenantId;
    private int $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(SaleService::class);

        $tenant  = $this->makeTenant();
        $admin   = $this->makeAdminFor($tenant);
        $this->actingAs($admin);

        $this->tenantId = $tenant->id;
        $this->userId   = $admin->id;

        $category = Category::create(['name' => 'Test', 'tenant_id' => $tenant->id]);
        $supplier = Supplier::create(['name' => 'Fournisseur', 'tenant_id' => $tenant->id]);

        $this->product = Product::create([
            'name'           => 'Produit Test',
            'stock'          => 20,
            'purchase_price' => 100,
            'sale_price'     => 150,
            'tenant_id'      => $tenant->id,
            'category_id'    => $category->id,
            'supplier_id'    => $supplier->id,
        ]);
    }

    public function test_create_throws_when_stock_insufficient(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Stock insuffisant/');

        $this->service->create([
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 999, 'unit_price' => 150],
            ],
        ], $this->tenantId, $this->userId);
    }

    public function test_create_throws_when_product_not_visible_to_tenant(): void
    {
        $otherTenant = $this->makeTenant(['email' => 'other2@test.com']);

        // TenantScope creating hook auto-assigns tenant_id from Auth::user(), so we bypass
        // Eloquent and insert directly to guarantee the product truly belongs to another tenant.
        $otherProductId = \DB::table('products')->insertGetId([
            'name'           => 'Produit Autre',
            'stock'          => 10,
            'purchase_price' => 100,
            'sale_price'     => 150,
            'tenant_id'      => $otherTenant->id,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/introuvable/');

        $this->service->create([
            'products' => [
                ['product_id' => $otherProductId, 'quantity' => 1, 'unit_price' => 150],
            ],
        ], $this->tenantId, $this->userId);
    }

    public function test_create_decrements_stock_correctly(): void
    {
        $this->service->create([
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 5, 'unit_price' => 150],
            ],
        ], $this->tenantId, $this->userId);

        $this->assertEquals(15, $this->product->fresh()->stock);
    }

    public function test_create_applies_discount_to_total(): void
    {
        $sale = $this->service->create([
            'discount' => 1000,
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 2, 'unit_price' => 150],
            ],
        ], $this->tenantId, $this->userId);

        // 2 * 150 - 1000 = max(0, -700) → 0 (discount capped)
        // Actually 2 * 150 = 300 - 1000 = max(0, -700) = 0
        $this->assertEquals(0, (float) $sale->total_price);
    }

    public function test_create_records_stock_movement(): void
    {
        $this->service->create([
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 3, 'unit_price' => 150],
            ],
        ], $this->tenantId, $this->userId);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type'       => 'sortie',
            'quantity'   => 3,
        ]);
    }

    public function test_cancel_throws_when_already_cancelled(): void
    {
        $sale = Sale::create([
            'user_id'     => $this->userId,
            'tenant_id'   => $this->tenantId,
            'total_price' => 0,
            'status'      => 'cancelled',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/déjà annulée/');

        $this->service->cancel($sale, $this->userId);
    }

    public function test_cancel_restores_stock(): void
    {
        $sale = $this->service->create([
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 5, 'unit_price' => 150],
            ],
        ], $this->tenantId, $this->userId);

        $this->assertEquals(15, $this->product->fresh()->stock);

        $sale->loadMissing('items');
        $this->service->cancel($sale, $this->userId);

        $this->assertEquals(20, $this->product->fresh()->stock);
    }

    public function test_cancel_marks_sale_as_cancelled(): void
    {
        $sale = $this->service->create([
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 1, 'unit_price' => 150],
            ],
        ], $this->tenantId, $this->userId);

        $sale->loadMissing('items');
        $this->service->cancel($sale, $this->userId);

        $this->assertEquals('cancelled', $sale->fresh()->status);
    }
}
