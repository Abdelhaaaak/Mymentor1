<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_session_id')
                  ->constrained('mentor_sessions')
                  ->onDelete('cascade');
            $table->foreignId('author_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->text('comment');
            $table->unsignedTinyInteger('rating')->index();
            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
