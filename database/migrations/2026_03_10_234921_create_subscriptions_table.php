<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
        $table->string('plan_type'); // monthly, quarterly, etc.
        $table->decimal('amount', 10, 2);
        $table->date('start_date');
        $table->date('end_date');
        $table->enum('status', ['active', 'expired', 'cancelled', 'pending', 'trial']);
        $table->string('payment_method')->nullable();
        $table->string('transaction_id')->nullable();
        $table->json('metadata')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
