<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image', 'stock'];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $this->attributes['image'] = $value;
        } elseif ($this->exists) {
            // Preserve the existing image if no new image is uploaded
            $this->attributes['image'] = $this->getOriginal('image');
        }
    }


}
