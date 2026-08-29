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
        Schema::create('maintenance_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->unsignedBigInteger('harga_bulanan')->default(150000);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->date('tanggal_mulai');
            $table->date('tanggal_jatuh_tempo_berikutnya');
            $table->timestamp('terakhir_diingatkan_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('lead_id');
            $table->index('status');
            $table->index('tanggal_jatuh_tempo_berikutnya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_subscriptions');
    }
};
