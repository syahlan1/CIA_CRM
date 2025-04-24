<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanban_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('column_id')->constrained('kanban_columns')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('position');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kanban_cards');
    }
};
