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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->enum('jenis', ['dp', 'pelunasan', 'maintenance', 'lainnya'])->default('dp');
            $table->unsignedBigInteger('jumlah')->default(0);
            $table->enum('status', ['pending', 'lunas'])->default('pending');
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('project_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
