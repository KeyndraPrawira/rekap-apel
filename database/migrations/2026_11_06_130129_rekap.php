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
        Schema::create('jenis_apel', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis')->unique();
            $table->timestamps();
        });

        Schema::create('rekap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_apel_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal');
            $table->foreign('jenis_apel_id')->references('id')->on('jenis_apel')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('detail_rekap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekap_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->foreign('rekap_id')->references('id')->on('rekap')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa']);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_apel');
        Schema::dropIfExists('rekap');
        Schema::dropIfExists('detail_rekap');
        
    }
};
