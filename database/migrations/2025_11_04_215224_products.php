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
        Schema::create('products', function (Blueprint $table) {
            $table->id('idProduct');
            $table->unsignedBigInteger('idCategory');
            $table->unsignedBigInteger('idBrand');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->string('modelo')->nullable();
            $table->string('imagen')->nullable(); // ruta de imagen
            $table->timestamps();

            // Relaciones
            $table->foreign('idCategory')->references('idCategory')->on('categories')->onDelete('cascade');
            $table->foreign('idBrand')->references('idBrand')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
