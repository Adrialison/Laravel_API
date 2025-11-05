<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brands';
    protected $primaryKey = 'idBrand';
    protected $fillable = ['nombre', 'descripcion'];

    public function products()
    {
        return $this->hasMany(Product::class, 'idBrand');
    }
}
