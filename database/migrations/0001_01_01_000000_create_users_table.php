<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('contraseña');
            $table->string('direccion')->nullable();
            $table->string('telefono', 15)->nullable();
            $table->enum('rol', ['admin', 'cliente'])->default('cliente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
