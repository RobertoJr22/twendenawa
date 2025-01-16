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
        Schema::create('motoristas_rotas_veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotas_id')->constrained('rotas');
            $table->foreignId('veiculos_id')->constrained('veiculos');
            $table->foreignId('motoristas_id')->constrained('motoristas')->nullable(); // Campo nullable, pois pode não ter motorista
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motoristas_rotas_veiculos');
    }
};
