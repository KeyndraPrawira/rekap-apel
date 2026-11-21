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
       

        Schema::create('rekap', function (Blueprint $table) {
            $table->id();

            $table->enum('jenis_apel', ['Apel Pagi', 'Apel Siang']);
            $table->date('tanggal');
            $table->timestamps();
        });

        Schema::create('detail_rekap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekap_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->foreign('rekap_id')->references('id')->on('rekap')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Tidak Hadir', 'Izin', 'Sakit', 'Dinas Luar', 'Cuti', 'Terlambat']);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap');
        Schema::dropIfExists('detail_rekap');
        
    }
};
