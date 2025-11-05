<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'idProduct'; // tu PK real
    protected $fillable = [
        'idCategory',
        'idBrand',
        'nombre',
        'precio',
        'descripcion',
        'modelo'
    ];

    // Relaciones
    public function category()
    {
        return $this->belongsTo(Category::class, 'idCategory');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'idBrand');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'idProduct');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'idProduct', 'idProduct');
    }
}
