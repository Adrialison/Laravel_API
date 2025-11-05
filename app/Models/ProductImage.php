<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images'; // nombre de la tabla
    protected $primaryKey = 'idImage';
    protected $fillable = ['idProduct', 'imagen'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct');
    }
}
