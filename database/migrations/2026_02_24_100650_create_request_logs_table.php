<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            
            // Здесь я изменил на requests, чтобы связь сработала корректно
            $table->foreignId('repair_request_id')
                  ->constrained('requests')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->string('old_status')->nullable();
            $table->string('new_status');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
