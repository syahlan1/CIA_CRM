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
        Schema::create('kanban_invites', function (Blueprint $table) {
            $table->id();
            $table->uuid('kanban_id');
            $table->string('invite_token')->unique();
            $table->enum('role', ['editor', 'viewer'])->default('viewer');
            $table->timestamps();

            $table->foreign('kanban_id')->references('id')->on('kanbans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kanban_invites');
    }
};
