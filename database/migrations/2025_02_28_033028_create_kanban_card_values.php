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
        Schema::create('kanban_card_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('kanban_cards')->onDelete('cascade');
            $table->string('key');  // misal: "Deadline", "Priority", "Estimated Time"
            $table->string('value'); // isinya bisa bebas
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
        Schema::dropIfExists('kanban_card_values');
    }
};
