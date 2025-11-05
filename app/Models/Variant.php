<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $primaryKey = 'idVariant';
    protected $fillable = ['idProduct', 'color', 'capacidad', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct');
    }
}
