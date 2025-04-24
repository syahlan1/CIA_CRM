<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kanban_histories', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('kanban_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // create, update, delete, move
            $table->string('target_type'); // column, card, value
            $table->unsignedBigInteger('target_id'); // ID dari data yang dimodifikasi
            $table->text('description')->nullable(); // penjelasan aktivitas
            $table->timestamps();

            $table->foreign('kanban_id')->references('id')->on('kanbans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanban_histories');
    }
};
