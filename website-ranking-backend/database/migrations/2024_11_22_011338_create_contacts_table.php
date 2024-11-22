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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id'); // Foreign key ke tabel websites
            $table->string('type'); // Jenis kontak (email, Skype, Telegram, dll.)
            $table->string('value'); // Nilai kontak (contoh: alamat email atau username Telegram)
            $table->unsignedBigInteger('user_id')->nullable(); // ID pengguna yang menambahkan
            $table->timestamps();

            // Relasi
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
