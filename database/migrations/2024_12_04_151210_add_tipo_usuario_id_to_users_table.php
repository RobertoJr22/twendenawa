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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tipo_usuario_id') // Cria o campo tipo_usuario_id
                  ->constrained('tipo_usuarios') // Define a chave estrangeira para a tabela tipo_usuarios
                  ->onDelete('cascade');        // Remove usuários se o tipo de usuário for deletado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tipo_usuario_id']); // Remove a chave estrangeira
            $table->dropColumn('tipo_usuario_id');   // Remove a coluna
        });
    }
};
