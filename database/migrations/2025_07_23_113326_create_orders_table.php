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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullable();
            $table->foreignId('admin_buy_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('admin_install_id')->nullable()->constrained('admins')->nullOnDelete();


            // --------------------Data of the project-----------------------------------
            $table->string('orderNumber')->unique();
            $table->string('projectName');
            $table->enum('send',['control','power']);
            $table->string('CustomerFileNumber')->nullable();
            $table->text('description')->nullable();
            $table->date('deadline')->nullable();
            // -----------------------Financial details---------------------------------
            $table->double('subTotal')->default(0);
            $table->double('DiscountTotal')->default(0);
              $table->double('totalBusbar')->default(0);
            $table->double('totalVAT')->default(0);
            $table->double('totalPrice')->default(0);
            // ---------------------------------------------------
            $table->enum('status', [
                'createRequest',// انشاء طلب
                'addRequest',// اضافة طلب
                'negotiationStage',// مراحل التفاوض
                'sendPurchase', // ارسال الشراء
                'purchased', // تم الشراء
                'sendInstall', // ارسال التثبيت
                'installed', // تم التثبيت
                'clientDidNotRespond', // لم يرد العميل
                'projectCancelled' // تم الغاء المشروع
            ])->default('createRequest');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
