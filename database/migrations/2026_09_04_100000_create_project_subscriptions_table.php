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
        Schema::create('project_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->enum('tipe', ['tahunan', 'bulanan', '6_bulan', 'custom'])->default('tahunan');
            $table->unsignedBigInteger('harga')->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_expired');
            $table->enum('status', ['aktif', 'akan_expired', 'expired', 'diperpanjang', 'nonaktif'])->default('aktif');
            $table->boolean('auto_renew')->default(false);
            $table->timestamp('terakhir_diingatkan_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('project_id');
            $table->index('lead_id');
            $table->index('status');
            $table->index('tanggal_expired');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_subscriptions');
    }
};
