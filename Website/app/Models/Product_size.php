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
        'prodsize_color',
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


    static function fetch($id = 0, $params = null, $ids = null)
    {
        $ws_products_sizes = self::join('products', 'prodsize_product', 'product_id')
            ->join('sizes', 'prodsize_size', 'size_id')->join('product_colors', 'prodsize_color', 'prodcolor_code')->join('product_media', 'prodsize_product', 'media_product')->groupBy('prodsize_id');

        if ($params) $ws_products_sizes->where($params);
        if ($id) $ws_products_sizes->where('prodsize_id', $id);
        if ($ids) $ws_products_sizes->whereIn('prodsize_id', $ids);

        return $id ? $ws_products_sizes->first() : $ws_products_sizes->get();
    }
}