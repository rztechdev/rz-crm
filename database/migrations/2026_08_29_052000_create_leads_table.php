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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usaha');
            $table->string('nama_kontak')->nullable();
            $table->string('kontak_wa');
            $table->enum('sumber', [
                'warm_network',
                'cold_outreach',
                'komunitas',
                'marketplace',
                'referral',
                'website',
                'lainnya'
            ])->default('warm_network');
            $table->enum('status', [
                'belum_dihubungi',
                'sudah_chat',
                'nego',
                'deal',
                'tidak_lanjut'
            ])->default('belum_dihubungi');
            $table->enum('paket_diminati', [
                'landing_page',
                'company_profile',
                'toko_kasir',
                'custom',
                'belum_tahu'
            ])->default('belum_tahu');
            $table->text('catatan')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('follow_up_date');
            $table->index('kontak_wa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
