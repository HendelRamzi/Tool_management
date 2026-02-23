<?php

use App\Enums\LoanStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_mouvements', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->integer('remaining_quantity');
            $table->enum('status', LoanStatus::getValues())
                ->default(LoanStatus::Pending);

            $table->unsignedBigInteger('tool_id');
            $table->foreign('tool_id')->references('id')->on('tools');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_mouvements');
    }
};
