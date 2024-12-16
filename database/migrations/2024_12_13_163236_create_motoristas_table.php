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
        Schema::create('motoristas', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('users');
            $table->primary('id');
            $table->string('foto');
            $table->string('nome');
            $table->date('DataNascimento');
            $table->string('endereco')->nullable();
            $table->string('BI');
            $table->integer('telefone');
            $table->foreignId('turnos_id')->constrained('turnos');
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
        Schema::dropIfExists('motoristas');
    }
};
