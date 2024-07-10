<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'prodcolor_code',
        'prodcolor_name',
        'prodcolor_product',
        'prodcolor_media',
        'prodcolor_minqty',
        'prodcolor_maxqty',
        'prodcolor_status'
    ];
}