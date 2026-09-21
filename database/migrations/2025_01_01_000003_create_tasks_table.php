<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            // Статус: todo | in_progress | done
            $table->string('status')->default('todo');
            // Приоритет: low | medium | high
            $table->string('priority')->default('medium');
            $table->date('deadline')->nullable();
            // Постановщик задачи
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            // Исполнитель (может быть не назначен)
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
