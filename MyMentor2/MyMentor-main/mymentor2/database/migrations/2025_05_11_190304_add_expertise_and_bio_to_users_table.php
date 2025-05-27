<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpertiseAndBioToUsersTable extends Migration
{
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (! Schema::hasColumn('users', 'expertise')) {
            $table->string('expertise')->nullable()->after('role');
        }
        if (! Schema::hasColumn('users', 'bio')) {
            $table->text('bio')->nullable()->after('expertise');
        }
    });
}


    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['expertise','bio']);
        });
    }
}
