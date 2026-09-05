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
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'email')) {
                $table->string('email')->nullable()->after('kontak_wa');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'portal_project_id')) {
                $table->unsignedBigInteger('portal_project_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('projects', 'portal_user_id')) {
                $table->unsignedBigInteger('portal_user_id')->nullable()->after('portal_project_id');
            }
            if (!Schema::hasColumn('projects', 'synced_to_portal_at')) {
                $table->timestamp('synced_to_portal_at')->nullable()->after('portal_user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['portal_project_id', 'portal_user_id', 'synced_to_portal_at']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['email']);
        });
    }
};
