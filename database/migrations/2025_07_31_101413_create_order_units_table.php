<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_units', function (Blueprint $table) {
            $table->id();
            // Data of the order unit
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('name')->nullable();

            // Financial details of the order unit
            $table->double('subTotal')->default(0);
            $table->double('brandDiscount')->default(0);
            $table->double('totalBusbar')->default(0);
            $table->double('workWagesPercentage')->default(0); // أجور العمالة
            $table->double('workWages')->default(0); // أجور العمالة
            $table->double('generalCost')->default(0); // التكاليف العامة
            $table->double('generalCostPercentage')->default(0); // التكاليف العامة
            $table->double('profitMargin')->default(0); // هامش الربح
            $table->double('profitMarginPercentage')->default(0); // هامش الربح
            $table->double('vat')->default(0); // الضريبة
            $table->double('vatPercentage')->default(0); // الضريبة
            $table->double('finalDiscount')->default(0);
            $table->double('totalPrice')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_units');
    }
};