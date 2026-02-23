<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_mouvements', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');


            $table->unsignedBigInteger('tool_id');
            $table->foreign('tool_id')->references('id')->on('tools');

            $table->unsignedBigInteger('loan_mouvements_id');
            $table->foreign('loan_mouvements_id')->references('id')->on('loan_mouvements');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_mouvements');
    }
};
