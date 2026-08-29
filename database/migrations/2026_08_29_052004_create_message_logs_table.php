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
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->onDelete('cascade');
            $table->string('kontak_wa');
            $table->enum('arah', ['keluar', 'masuk'])->default('keluar');
            $table->string('tipe_pesan')->default('manual'); // invoice_dp, project_selesai, reminder_maintenance, manual, webhook_masuk
            $table->text('isi_pesan');
            $table->string('status_kirim')->default('sent'); // queued, sent, delivered, failed, received
            $table->json('response_payload')->nullable();
            $table->timestamps();

            $table->index('lead_id');
            $table->index('kontak_wa');
            $table->index('arah');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_logs');
    }
};
