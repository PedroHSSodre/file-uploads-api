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
        Schema::create('files', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('file_name');
            $table->unsignedBigInteger('file_size');
            $table->string('file_mimetype');
            $table->string('file_extension');
            $table->string('file_path');
            $table->uuid('file_folder_id');
            $table->uuid('file_user_id');
            $table->enum('file_upload_status', [
                'on_upload',
                'uploaded',
                'upload_failed',
            ]);
            $table->timestamps();

            $table
                ->foreign('file_folder_id')
                ->references('id')
                ->on('folders')
                ->cascadeOnDelete();

            $table
                ->foreign('file_user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
