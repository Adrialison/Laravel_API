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
        Schema::create('variants', function (Blueprint $table) {
            $table->id('idVariant');
            $table->unsignedBigInteger('idProduct');
            $table->string('color')->nullable();
            $table->string('capacidad')->nullable(); // o tamaño
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->foreign('idProduct')->references('idProduct')->on('products')->onDelete('cascade');
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
