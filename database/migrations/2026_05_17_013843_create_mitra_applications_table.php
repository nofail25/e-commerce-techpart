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
        Schema::create('mitra_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Data Diri
            $table->string('name');
            $table->string('email');
            $table->string('no_whatsapp')->nullable();
            
            // Data Usaha
            $table->string('nama_toko')->nullable();
            $table->text('alamat_toko')->nullable();
            $table->string('kota')->nullable();
            
            // Pengalaman
            $table->integer('pengalaman_tahun')->nullable();
            $table->string('spesialisasi')->nullable(); // JSON atau comma-separated
            
            // Dokumen
            $table->string('foto_toko')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('sertifikat')->nullable();
            
            // Informasi tambahan
            $table->text('alasan_bergabung')->nullable();
            $table->string('sumber_info')->nullable();
            
            // Status pengajuan
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_applications');
    }
};