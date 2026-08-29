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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->string('nama_project');
            $table->string('paket')->default('landing_page');
            $table->unsignedBigInteger('harga')->default(0);
            $table->enum('status', [
                'draft',
                'dp_diterima',
                'dikerjakan',
                'review',
                'selesai',
                'dibatalkan'
            ])->default('draft');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('link_website')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
