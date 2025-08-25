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
        Schema::create('issues', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('tenant_id', 36);
            $table->char('project_id', 36);
            $table->char('board_id', 36)->nullable();
            $table->char('sprint_id', 36)->nullable();
            $table->char('reporter_id', 36);
            $table->char('assignee_id', 36)->nullable();
            $table->enum('type', ['task', 'bug', 'story'])->default('task');
            $table->enum('status', ['todo', 'in_progress', 'done', 'closed'])->default('todo');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('points')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('is_deleted')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('set null');
            $table->foreign('sprint_id')->references('id')->on('sprints')->onDelete('set null');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
