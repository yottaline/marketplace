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


    static function fetch($id = 0, $params = null, $ids = null)
    {
        $ws_products_sizes = self::join('products', 'prodsize_product', 'product_id')
            ->join('sizes', 'prodsize_size', 'size_id')->join('subcategories', 'size_id', 'size_subcategory');

        if ($params) $ws_products_sizes->where($params);
        if ($id) $ws_products_sizes->where('prodsize_id', $id);
        if ($ids) $ws_products_sizes->whereIn('prodsize_id', $ids);

        return $id ? $ws_products_sizes->first() : $ws_products_sizes->get();
    }

    static function submit($param, $id)
    {
        if ($id) return self::where('prodsize_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}