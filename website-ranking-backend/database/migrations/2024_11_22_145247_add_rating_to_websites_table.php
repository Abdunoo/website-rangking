<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingToWebsitesTable extends Migration
{
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->decimal('rating', 2, 1)->default(4.5)->after('previous_rank');
        });
    }

    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
}
