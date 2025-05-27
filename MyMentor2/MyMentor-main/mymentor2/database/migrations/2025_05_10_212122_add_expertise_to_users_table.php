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
    Schema::table('users', function (Blueprint $table) {
        $table->string('expertise')->nullable(); // Add expertise column for mentors
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('expertise'); // Drop expertise column
    });
}
};