<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Board name (e.g. Mathematics, Science)
            $table->string('thumbnail')->nullable(); // Board thumbnail image URL
            $table->text('description')->nullable(); // Optional description
            $table->unsignedBigInteger('user_id')->nullable(); // Owner/creator
            
            $table->timestamps();

            // If you want to link boards to users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
