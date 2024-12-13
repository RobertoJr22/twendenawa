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
        Schema::create('estudantes', function (Blueprint $table) {
            $table->id();
            $table->string('foto');
            $table->string('nome');
            $table->date('DataNascimento');
            $table->string('endereco')->nullable();
            $table->integer('telefone');
            $table->foreignId('sexos_id')->constrained('sexos');
            $table->foreignId('turnos_id')->constrained('turnos');
            $table->foreignId('users_id')->constrained('Users');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudantes');
    }
};
