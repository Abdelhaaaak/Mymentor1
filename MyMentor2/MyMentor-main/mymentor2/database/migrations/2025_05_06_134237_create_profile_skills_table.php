<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileSkillsTable extends Migration
{
    public function up()
    {
        Schema::create('profile_skills', function (Blueprint $table) {
            // If you want a composite primary key:
            $table->foreignId('user_id')
                  ->constrained()              // assumes users.id
                  ->onDelete('cascade');

            $table->foreignId('skill_id')
                  ->constrained('skills')      // skills.id
                  ->onDelete('cascade');

            $table->primary(['user_id','skill_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('profile_skills');
    }
}
