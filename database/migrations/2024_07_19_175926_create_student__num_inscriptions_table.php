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
        Schema::create('student__num_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inscription_num');
            $table->string('etab_uuid');
            $table->foreign('etab_uuid')
                ->references('uuid')
                ->on('etablissement')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student__num_inscriptions');
    }
};
