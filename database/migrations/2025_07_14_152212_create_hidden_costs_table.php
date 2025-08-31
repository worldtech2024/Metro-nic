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
        Schema::create('hidden_costs', function (Blueprint $table) {
            $table->id();
            $table->double('workWages'); // أجور العمالة
            $table->double('generalCost'); // التكاليف العامة
            $table->double('profitMargin'); // هامش الربح
            $table->double('tax'); // الضريبة
            $table->double('wirePrice')->default(55);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hidden_costs');
    }
};