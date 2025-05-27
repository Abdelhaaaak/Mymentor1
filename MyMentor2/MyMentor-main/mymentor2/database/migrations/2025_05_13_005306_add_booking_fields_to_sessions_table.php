<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingFieldsToSessionsTable extends Migration
{
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            // only add if missing
            if (! Schema::hasColumn('sessions', 'student_id')) {
                $table->foreignId('student_id')
                      ->nullable()
                      ->constrained('users')
                      ->cascadeOnDelete()
                      ->after('mentor_id');
            }

            if (! Schema::hasColumn('sessions', 'scheduled_at')) {
                $table->timestamp('scheduled_at')
                      ->nullable()
                      ->after('student_id');
            }

            if (! Schema::hasColumn('sessions', 'status')) {
                $table->string('status')
                      ->default('pending')
                      ->after('scheduled_at');
            }
        });
    }

    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'scheduled_at', 'status']);
        });
    }
}
