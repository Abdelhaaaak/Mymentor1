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
    Schema::table('sessions', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('cascade');
        $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('sessions', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->dropForeign(['mentor_id']);
        $table->dropForeign(['student_id']);
        $table->dropColumn(['mentor_id', 'student_id']);
    });
}

};
