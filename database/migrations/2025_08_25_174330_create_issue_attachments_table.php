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
        Schema::create('issue_attachments', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('issue_id', 36);
            $table->char('uploader_id', 36);
            $table->string('path');
            $table->string('mime');
            $table->integer('size');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('issue_id')->references('id')->on('issues')->onDelete('cascade');
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_attachments');
    }
};
