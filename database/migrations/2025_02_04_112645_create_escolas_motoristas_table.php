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
        Schema::create('escolas_motoristas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escolas_id')->constrained('escolas');
            $table->foreignId('motoristas_id')->constrained('motoristas');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escolas_motoristas');
    }
};
