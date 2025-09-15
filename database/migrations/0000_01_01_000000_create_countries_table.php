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
            $table->string('bankName');
            $table->string('IBAN');
            $table->string('accountNumber');
            $table->string('accountName');
            $table->string('code');
            $table->string('currency');
            $table->integer('workWages', 12);
            $table->integer('generalCost', 12);
            $table->integer('profitMargin', 12);
            $table->integer('tax', 12);
            $table->integer('wirePrice', 12)->default(55);
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
