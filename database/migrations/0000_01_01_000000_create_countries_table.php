<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('address');
            $table->string('phone');
            $table->string('mobile');
            $table->string('email');
            $table->string('faxNumber');
            $table->string('code');
            $table->string('currency');
            $table->double('workWages');    // أجور العمالة
            $table->double('generalCost');  // التكاليف العامة
            $table->double('profitMargin'); // هامش الربح
            $table->double('tax');          // الضريبة
            $table->double('wirePrice')->default(55);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
