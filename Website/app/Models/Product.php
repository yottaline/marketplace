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

    static function fetch($id = 0, $param = null, $limit = 24, $offset = 0)
    {
        $products = self::join('categories', 'product_category', 'category_id')->join('subcategories', 'product_subcategory', 'subcategory_id')
        ->leftJoin('product_media', 'product_id', 'media_product')
        ->limit($limit)->offset($offset)->groupBy('product_id');

        if($param) $products->where($param);

        if($id) $products->where('product_id', $id);

        return $id ? $products->first() : $products->get();
    }

}