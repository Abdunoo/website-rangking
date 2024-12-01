<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteTrendsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_trends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_trends');
    }
}
