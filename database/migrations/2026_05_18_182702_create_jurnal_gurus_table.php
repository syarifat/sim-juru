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
        Schema::create('jurnal_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->cascadeOnDelete();
            $table->date('tanggal_mengajar');
            $table->foreignId('guru_pengisi_id')->constrained('gurus')->cascadeOnDelete();
            $table->text('materi_pembelajaran');
            $table->text('catatan_tambahan')->nullable();
            $table->enum('status_validasi', ['Pending', 'Disetujui', 'Revisi'])->default('Pending');
            $table->text('catatan_kepsek')->nullable();
            $table->timestamps(); // Ini otomatis membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_gurus');
    }
};
