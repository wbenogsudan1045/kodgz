<?php

// database/migrations/xxxx_xx_xx_create_note_links_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('note_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_a_id');
            $table->unsignedBigInteger('note_b_id');
            $table->string('relation_type')->nullable(); // e.g. "linked", "related", etc.
            $table->timestamps();

            // Relationships
            $table->foreign('note_a_id')->references('id')->on('sticky_notes')->onDelete('cascade');
            $table->foreign('note_b_id')->references('id')->on('sticky_notes')->onDelete('cascade');

            // Prevent duplicates in the same direction
            $table->unique(['note_a_id', 'note_b_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_links');
    }
};
