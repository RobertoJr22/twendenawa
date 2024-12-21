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
        Schema::create('estudantes_responsavels', function(Blueprint $table){
            $table->id();
            $table->foreignId('estudantes_id')->constrained('estudantes');
            $table->foreignId('responsavels_id')->constrained('responsavels');
            $table->integer('estado')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudantes_responsavels');
    }
};
