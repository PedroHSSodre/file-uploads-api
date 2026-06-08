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
        Schema::create('folders', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('folder_name');
            $table->uuid('folder_parent')->nullable();
            $table->uuid('folder_user_id');
            $table->timestamps();

            $table
                ->foreign('folder_parent')
                ->references('id')
                ->on('folders')
                ->nullOnDelete();

            $table
                ->foreign('folder_user_id')
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
        Schema::dropIfExists('folders');
    }
};
