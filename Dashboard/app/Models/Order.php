<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\Order_item;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'order_code',
        'order_customer',
        'order_tax',
        'order_subtotal',
        'order_discount',
        'order_total',
        'order_status',
        'order_note',
        'order_modified',
        'order_exec',
        'order_approved',
        'order_delivered',
        'order_create_by',
        'order_created'
    ];


    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $orders = self::join('customers', 'order_customer', 'customer_id');
            // ->join('users', 'order_create_by', 'id');

        if ($lastId) $orders->where('order_id', '<', $lastId);

        if (isset($params['q'])) {
            $orders->where(function (Builder $query) use ($params) {
                $query->where('order_code', 'like', '%' . $params['q'] . '%')
                    ->orWhere('order_discount', 'like', '%' . $params['q'] . '%')
                    ->orWhere('order_created', 'like', '%' . $params['q'] . '%');
            });

            unset($params['q']);
        }

        if ($params) $orders->where($params);
        if ($id) $orders->where('order_id', $id);

        return $id ? $orders->first() : $orders->get();
    }

    public static function submit(int $id, array $orderParam = null, array $orderItemParam = null)
    {
        try {
            DB::beginTransaction();
            $status = $id ? self::where('order_id', $id)->update($orderParam) : self::create($orderParam);
            $id = $id ? $id : $status->id;
            if (!empty($orderItemParam)) {
                for ($i = 0; $i < count($orderItemParam); $i++) {
                    $orderItemParam[$i]['orderItem_order'] = $id;
                }
                Order_item::insert($orderItemParam);
            }
            DB::commit();
            return ['status' => true, 'id' => $id];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'error: ' . $e->getMessage()];
        }
    }
}