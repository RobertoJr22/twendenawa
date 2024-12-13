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
        Schema::create('responsavels', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('DataNascimento');
            $table->string('foto')->nullable();
            $table->string('endereco')->nullable();
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('sexos_id')->constrained('sexos');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsavels');
    }
};
