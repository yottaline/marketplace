<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_code',
        'product_name',
        'product_desc',
        'product_category',
        'product_subcategory',
        'product_created_by'
    ];

    static function fetch($id = 0, $param = null, $limit = null, $lastId = null)
    {
        $products = self::join('categories', 'product_category', 'category_id')->join('subcategories', 'product_subcategory', 'subcategory_id')
        ->join('retailers', 'product_created_by', 'retailer_id')->limit($limit)->orderBy('product_id', 'DESC');

        if($param) $products->where($param);

        if($lastId) $products->where('product_id', '<', $lastId);

        if($id) $products->where('product_id', $id);

        return $id ? $products->first() : $products->get();
    }
}