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
        Schema::table('products', function (Blueprint $table) {
            // Cek apakah kolom 'brand' belum ada, jika belum ada, baru buat
            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable()->after('name');
            }

            // Cek apakah kolom 'series' belum ada, jika belum ada, baru buat
            if (!Schema::hasColumn('products', 'series')) {
                $table->string('series')->nullable()->after('brand');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
