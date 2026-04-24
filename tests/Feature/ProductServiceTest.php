<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesTenant;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase, CreatesTenant;

    private ProductService $service;
    private int $tenantId;
    private int $userId;
    private Category $category;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ProductService::class);

        $tenant  = $this->makeTenant();
        $admin   = $this->makeAdminFor($tenant);
        $this->actingAs($admin);

        $this->tenantId = $tenant->id;
        $this->userId   = $admin->id;

        $this->category = Category::create(['name' => 'Catégorie Test', 'tenant_id' => $tenant->id]);
        $this->supplier = Supplier::create(['name' => 'Fournisseur Test', 'tenant_id' => $tenant->id]);
    }

    private function productData(array $overrides = []): array
    {
        return array_merge([
            'name'           => 'Produit Test',
            'stock'          => 50,
            'purchase_price' => 100.0,
            'sale_price'     => 150.0,
            'category_id'    => $this->category->id,
            'supplier_id'    => $this->supplier->id,
        ], $overrides);
    }

    public function test_create_new_product(): void
    {
        $result = $this->service->createOrCumulate(
            $this->productData(), $this->tenantId, $this->userId
        );

        $this->assertEquals('created', $result['type']);
        $this->assertDatabaseHas('products', ['name' => 'Produit Test', 'stock' => 50]);
    }

    public function test_creates_initial_stock_movement_on_creation(): void
    {
        $result = $this->service->createOrCumulate(
            $this->productData(['stock' => 30]), $this->tenantId, $this->userId
        );

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $result['product']->id,
            'type'       => 'entree',
            'quantity'   => 30,
        ]);
    }

    public function test_cumulates_stock_when_same_product_exists(): void
    {
        $this->service->createOrCumulate(
            $this->productData(['stock' => 50]), $this->tenantId, $this->userId
        );

        $result = $this->service->createOrCumulate(
            $this->productData(['stock' => 30]), $this->tenantId, $this->userId
        );

        $this->assertEquals('restocked', $result['type']);
        $this->assertDatabaseCount('products', 1);
        $this->assertEquals(80, $result['product']->fresh()->stock);
    }

    public function test_weighted_average_purchase_price_on_restock(): void
    {
        // 100 unités à 50 FCFA
        $this->service->createOrCumulate(
            $this->productData(['stock' => 100, 'purchase_price' => 50.0]),
            $this->tenantId, $this->userId
        );

        // 100 unités à 70 FCFA
        $this->service->createOrCumulate(
            $this->productData(['stock' => 100, 'purchase_price' => 70.0]),
            $this->tenantId, $this->userId
        );

        $product = Product::first();
        // Moyenne pondérée : (100*50 + 100*70) / 200 = 60
        $this->assertEquals(60.0, (float) $product->purchase_price);
        $this->assertEquals(200, $product->stock);
    }

    public function test_restock_creates_stock_movement(): void
    {
        $this->service->createOrCumulate(
            $this->productData(['stock' => 50]), $this->tenantId, $this->userId
        );

        $this->service->createOrCumulate(
            $this->productData(['stock' => 25]), $this->tenantId, $this->userId
        );

        $product = Product::first();
        $movements = StockMovement::where('product_id', $product->id)->where('type', 'entree')->get();

        // 1 mouvement initial + 1 réappro
        $this->assertCount(2, $movements);
    }

    public function test_zero_stock_product_creates_no_movement(): void
    {
        $result = $this->service->createOrCumulate(
            $this->productData(['stock' => 0]), $this->tenantId, $this->userId
        );

        $this->assertDatabaseMissing('stock_movements', [
            'product_id' => $result['product']->id,
            'type'       => 'entree',
        ]);
    }

    public function test_sale_price_is_updated_on_restock(): void
    {
        $this->service->createOrCumulate(
            $this->productData(['sale_price' => 150.0]), $this->tenantId, $this->userId
        );

        $this->service->createOrCumulate(
            $this->productData(['sale_price' => 200.0]), $this->tenantId, $this->userId
        );

        $this->assertEquals(200.0, (float) Product::first()->sale_price);
    }
}
