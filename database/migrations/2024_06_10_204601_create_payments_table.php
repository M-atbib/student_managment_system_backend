<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('student_uuid');
            $table->string('type');
            $table->string('methode'); // add is 'espece' or 'cheque' or 'virement'
            $table->string('montant');
            $table->string('month')->nullable();
            $table->date('date_payment');
            $table->foreign('student_uuid')
                ->references('uuid')
                ->on('students')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
