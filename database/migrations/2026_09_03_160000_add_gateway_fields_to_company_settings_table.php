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
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('wa_api_url')->default('https://wa.flustra.id/api/v1/messages/text')->after('phone_admin_alerts');
            $table->text('wa_api_key')->nullable()->after('wa_api_url');
            $table->string('wa_sender_phone')->nullable()->default('0823-1828-0376')->after('wa_api_key');
            $table->string('portal_sync_url')->default('http://localhost:8021/api/internal/v1/sync-client-project')->after('invoice_terms');
            $table->string('portal_sync_secret')->default('rz_portal_sync_secret_key_2026')->after('portal_sync_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'wa_api_url',
                'wa_api_key',
                'wa_sender_phone',
                'portal_sync_url',
                'portal_sync_secret',
            ]);
        });
    }
};
