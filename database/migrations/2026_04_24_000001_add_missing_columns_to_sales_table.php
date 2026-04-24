<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('id');
            }
            if (!Schema::hasColumn('sales', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('sales', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0)->after('discount');
            }
            if (!Schema::hasColumn('sales', 'final_price')) {
                $table->decimal('final_price', 10, 2)->default(0)->after('tax');
            }
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('final_price');
            }
            if (!Schema::hasColumn('sales', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('sales', 'notes')) {
                $table->text('notes')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('sales', 'status')) {
                $table->string('status')->default('completed')->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('sales', 'invoice_number') ? 'invoice_number' : null,
                Schema::hasColumn('sales', 'discount') ? 'discount' : null,
                Schema::hasColumn('sales', 'tax') ? 'tax' : null,
                Schema::hasColumn('sales', 'final_price') ? 'final_price' : null,
                Schema::hasColumn('sales', 'payment_method') ? 'payment_method' : null,
                Schema::hasColumn('sales', 'payment_status') ? 'payment_status' : null,
                Schema::hasColumn('sales', 'notes') ? 'notes' : null,
                Schema::hasColumn('sales', 'status') ? 'status' : null,
            ]));
        });
    }
};
