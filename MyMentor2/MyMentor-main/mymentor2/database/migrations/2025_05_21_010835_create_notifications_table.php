<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            // Identifiant unique par UUID pour coller au comportement de Laravel
            $table->uuid('id')->primary();

            // Le type de notification (class)
            $table->string('type');

            // Morph to notifiable (User, autre modèle, etc.)
            $table->morphs('notifiable');

            // Données JSON de la notification
            $table->text('data');

            // Horodatage de lecture
            $table->timestamp('read_at')->nullable();

            // created_at / updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
