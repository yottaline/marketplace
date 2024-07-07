<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Retailer extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'retailer_user',
        'retailer_phone',
        'retailer_logo',
        'retailer_store',
        'retailer_mobile',
        'retailer_note',
        'retailer_address',
        'retailer_vat',
        'retailer_status',
        'retailer_approved',
        'retailer_approved_by',
        'retailer_login'
    ];

    static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $retailers = self::join('users', 'retailer_user', 'id')->where('user_type', 2)->orderBy('user_created', 'DESC')->limit($limit);

        if (isset($params['q'])) {
            $retailers->where(function (Builder $query) use ($params) {
                $query->where('user_email', 'like', '%' . $params['q'] . '%')
                ->orWhere('retailer_phone', 'like', '%' . $params['q'] . '%')
                ->orWhere('user_name', $params['q'])
                ->orWhere('retailer_store', $params['q'])
                ->orWhere('retailer_address', $params['q'])
                ->orWhere('retailer_mobile', $params['q']);
            });

            unset($params['q']);
        }

        if($lastId) $retailers->where('retailer_id', '<', $lastId);

        if($params) $retailers->where($params);

        if($id) $retailers->where('retailer_id', $id);

        return $id ? $retailers->first() : $retailers->get();
    }

    static function submit($id, $user, $retailer)
    {
        if($id) return User::where('id', $id)->update($user) ? $id : false;
        $status = User::create($user);

        if($status){
            $retailer['retailer_user'] = $status->id;
            self::create($retailer);
            $status->assignRole('retailer');
            return $status->id;
        }
        return false;
    }
}