<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('clientName'); // Имя клиента
            $table->string('phone');      // Телефон
            $table->string('address');    // Адрес
            $table->text('problemText');  // Описание проблемы
            $table->enum('status', ['new', 'assigned', 'in_progress', 'done', 'canceled'])->default('new');
            $table->foreignId('assignedTo')->nullable()->constrained('users'); // Мастер
            $table->timestamps();         // createdAt и updatedAt (по ТЗ)
        });
    }

    public function down(): void {
        Schema::dropIfExists('requests');
    }
};
