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
    Schema::table('sessions', function (Blueprint $table) {
    if (!Schema::hasColumn('sessions', 'mentor_id')) {
        $table->foreignId('mentor_id')->nullable()->constrained('users');
    }
});

}

public function down()
{
    Schema::table('sessions', function (Blueprint $table) {
        $table->dropForeign(['mentor_id']);
        $table->dropColumn('mentor_id');
    });
}

};
