<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('athletes', function (Blueprint $table) {
            $table->string('gender')->after('id');
            $table->string('family_name')->after('gender');
            $table->string('given_name')->after('family_name');
            $table->date('date_of_birth')->nullable()->after('given_name'); // Make it nullable
            $table->string('nation')->after('date_of_birth');
            $table->string('region')->after('nation');
            $table->string('adams_id')->unique()->after('region');
            $table->string('picture')->nullable()->after('adams_id');
        });
    }
    

    public function down() {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn(['gender', 'family_name', 'given_name', 'date_of_birth', 'nation', 'region', 'adams_id', 'picture']);
        });
    }
};
