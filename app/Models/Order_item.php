<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order_item extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'orderItem_order',
        'orderItem_product',
        'orderItem_size',
        'orderItem_productPrice',
        'orderItem_subtotal',
        'orderItem_qty',
        'orderItem_disc',
        'orderItem_total'
    ];


    static function fetch($id = 0, $params = null)
    {
        $oder_products = self::join('products', 'orderItem_product', 'product_id')
            ->join('product_sizes', 'orderItem_size', 'prodsize_id')
            ->join('sizes', 'product_sizes.prodsize_size', 'size_id')
            ->join('product_colors', 'prodcolor_code', 'prodsize_color')
            ->join('orders', 'orderItem_order', 'order_id');

        if ($params) $oder_products->where($params);

        if ($id) $oder_products->where('orderItem_id', $id);

        return $id ? $oder_products->first() : $oder_products->get();
    }

    static function submit($product, $order = null)
    {
        try {
            DB::beginTransaction();
            $status = $product[0] ? self::where('ordprod_id', $product[0])->update($product[1]) : self::create($product[1]);
            $id = $product[0] ? $product[0] : $status->id;
            if (!empty($order)) order::where('order_id', $order[0])->update($order[1]);
            DB::commit();
            return ['status' => true, 'id' => $id];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'error: ' . $e->getMessage()];
        }
    }

    static function delSize($size, $order = null)
    {
        try {
            DB::beginTransaction();
            self::where('orderItem_product', $size)->delete();
            if (!empty($order)) Order::where('order_id', $order[0])->update($order[1]);
            DB::commit();
            return ['status' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'error: ' . $e->getMessage()];
        }
    }
}