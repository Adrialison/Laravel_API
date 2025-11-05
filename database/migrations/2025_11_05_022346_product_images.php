<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('idImage');
            $table->unsignedBigInteger('idProduct');
            $table->string('imagen'); // ruta de la imagen
            $table->timestamps();

            $table->foreign('idProduct')
                ->references('idProduct')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
