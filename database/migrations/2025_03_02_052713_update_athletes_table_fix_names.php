<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('athletes', function (Blueprint $table) {
        $table->dropColumn(['first_name', 'last_name']); // Remove old columns
    });
}

public function down()
{
    Schema::table('athletes', function (Blueprint $table) {
        $table->string('first_name');
        $table->string('last_name');
    });
}

};
