<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sticky_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // note creator
            $table->string('title')->nullable();
            $table->text('content');
            $table->enum('color', ['red', 'yellow', 'green'])->default('yellow');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sticky_notes');
    }
};
