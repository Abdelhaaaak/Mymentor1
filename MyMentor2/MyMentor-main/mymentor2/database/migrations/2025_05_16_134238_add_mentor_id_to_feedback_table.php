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
    Schema::table('feedback', function (Blueprint $table) {
        $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('feedback', function (Blueprint $table) {
        $table->dropForeign(['mentor_id']);
        $table->dropColumn('mentor_id');
    });
}

};
