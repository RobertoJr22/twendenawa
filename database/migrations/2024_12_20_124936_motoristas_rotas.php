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
        Schema::create('motoristas_rotas', function(Blueprint $table){
            $table->id();
            $table->foreignId('motoristas_id')->constrained('motoristas');
            $table->foreignId('rotas_id')->constrained('rotas');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motoristas_rotas');
    }
};
