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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('PT RZ Digital Creative Artha');
            $table->string('brand_name')->default('RZ Digital Creative');
            $table->string('tagline')->default('Software House & Digital Solutions');
            $table->string('domicile_city')->default('Tangerang Selatan');
            $table->string('email_support')->default('support@rzdigitalcreative.my.id');
            $table->string('email_company')->default('company@rzdigitalcreative.my.id');
            $table->string('email_internal_alert')->default('rzsupportidn@gmail.com');
            $table->string('website_url')->default('https://rzdigitalcreative.my.id');
            $table->string('phone_support')->default('0858-0874-9131');
            $table->string('phone_support_2')->default('0821-1620-0363');
            $table->string('phone_admin_alerts')->default('085808749131,082116200363');
            $table->string('bank_name')->default('Bank Central Asia (BCA)');
            $table->string('bank_account_number')->default('4740769826');
            $table->string('bank_account_holder')->default('MUHAMAD RYAN RIZKI');
            $table->string('qris_image_path')->nullable()->default('images/qris.jpg');
            $table->string('logo_image_path')->nullable()->default('images/logo_rz_teks.png');
            $table->string('director_name')->default('MUHAMAD RYAN RIZKI');
            $table->string('director_title')->default('Finance & Executive Director');
            $table->text('invoice_terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
