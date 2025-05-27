<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration
{
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The user being followed
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade'); // The user who follows
            $table->timestamps();
            
            // Ensure user_id and follower_id are unique pairs
            $table->unique(['user_id', 'follower_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
