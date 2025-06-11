<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCardPicturesToAthletesTable extends Migration
{
    public function up()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->string('id_card_picture')->nullable(); // ID card picture
            $table->string('certificate')->nullable(); // Second ID picture
        });
    }

    public function down()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn('id_card_picture');
            $table->dropColumn('certificate');
        });
    }
}
