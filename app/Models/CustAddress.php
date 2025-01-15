<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustAddress extends Model
{

    use HasFactory;

    protected $table = 'cust_address';
    
    // app/Models/CustAddress.php
    protected $fillable = [
        'user_id',
        'address_line',
        'city',
        'state',
        'postal_code',
        'order_reference',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
