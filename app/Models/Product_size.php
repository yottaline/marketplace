<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_size extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'prodsize_product',
        'prodsize_size',
        'prodsize_code',
        'prodsize_cost',
        'prodsize_sellprice',
        'prodsize_price',
        'prodsize_qty',
        'prodsize_discount',
        'prodsize_discount_start',
        'prodsize_discount_end',
        'prodsize_status'
    ];

}
