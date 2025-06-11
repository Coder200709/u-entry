<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('athlete_competition', function (Blueprint $table) {
        $table->id();
        $table->foreignId('athlete_id')->constrained()->onDelete('cascade');
        $table->foreignId('competition_id')->constrained()->onDelete('cascade');
        $table->string('category');
        $table->integer('entry_total');
        $table->string('reserve'); // Yes or No
        $table->timestamps();
    });
}


    public function down() {
        Schema::table('athlete_competition', function (Blueprint $table) {
            $table->dropColumn(['category', 'entry_total', 'reserve']);
        });
    }
};
