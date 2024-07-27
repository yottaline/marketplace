<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'customer_password',
        'customer_email',
        'customer_phone',
        'customer_note',
        'customer_address',
        'customer_status',
        'customer_created'
    ];

    static function fetch($id = 0, $params = null, $limit = null, $listId = null)
    {
        $customers = self::limit($limit)->orderByDesc('customer_created');

        if($listId) $customers->where('customer_id', '<', $listId);

        if (isset($params['q']))
        {
            $customers->where(function (Builder $query) use ($params) {
                $query->where('customer_name', 'like', '%' . $params['q'] . '%')
                        ->orWhere('customer_phone', $params['q'])
                        ->orWhere('customer_email', $params['q']);
            });
            unset($params['q']);
        }

        if($params) $customers->where($params);

        return $id ? $customers->first() : $customers->get();
    }

    static function submit($param, $id)
    {
        if($id) return self::where('customer_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}