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
            $table->foreignId('estudantes_id')->nullable()->constrained('estudantes')->onDelete('set null');
            $table->foreignId('viagems_id')->constrained('viagems')->onDelete('cascade');
            $table->text('relatorio')->nullable();
            $table->tinyInteger('estado')->default(1);
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
