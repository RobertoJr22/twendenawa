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
        Schema::create('dados_viagems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudantes_id')->constrained('estudantes');
            $table->foreignId('motoristas_id')->constrained('motoristas');
            $table->foreignId('viagems_id')->constrained('viagems');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dados_viagems');
    }
};
