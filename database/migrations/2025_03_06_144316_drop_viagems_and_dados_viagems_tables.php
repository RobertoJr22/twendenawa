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
        Schema::dropIfExists('dados_viagems');
        Schema::dropIfExists('viagems');
    }

    public function down(): void
    {
        Schema::create('viagems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('dados_viagems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
