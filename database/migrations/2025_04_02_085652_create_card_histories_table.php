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
        Schema::create('card_histories', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->foreignId('card_id')->constrained('kanban_cards')->onDelete('cascade');
            $table->foreignId('column_id')->constrained('kanban_columns')->onDelete('cascade');
            $table->integer('position');
            $table->json('updated_value'); // menyimpan data card dalam format JSON
            $table->timestamp('created_date')->useCurrent();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_histories');
    }
};
