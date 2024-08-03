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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            // info perso
            $table->string('inscription_number');
            $table->string('CIN')->nullable()->unique();
            $table->string('id_massar')->nullable()->unique();
            $table->string('full_name')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('gender')->nullable();
            $table->string('school_level')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('plain_password')->nullable();
            $table->string('responsable')->nullable();
            $table->string('photo')->nullable();
            // info pro
            $table->string('training_duration')->nullable();
            $table->string('sector')->nullable();
            $table->string('filières_formation')->nullable();
            $table->string('training_level')->nullable();
            $table->string('group_uuid')->nullable();
            $table->string('monthly_amount')->nullable();
            $table->string('registration_fee')->nullable();
            $table->string('product')->nullable();
            $table->string('frais_diplôme')->nullable();
            $table->string('annual_amount')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('date_start_at')->nullable();
            $table->timestamp('date_fin_at')->nullable();

            $table->foreign('group_uuid')
                ->references('uuid')
                ->on('groups')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
