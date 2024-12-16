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
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('Matricula')->unique();
            $table->string('VIN')->unique();
            $table->foreignId('modelos_id')->constrained('modelos');
            $table->integer('estado')->default(1);
            $table->integer('capacidade');
            $table->unsignedBigInteger('escolas_id');
            $table->foreign('escolas_id')->references('id')->on('escolas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
