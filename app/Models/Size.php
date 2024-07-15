<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Size extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'size_code',
        'size_name',
        'size_sign',
        'size_status',
        'size_subcategory'
    ];

    static function fetch($id = 0, $param = null, $limit = null, $lastId = null)
    {
        $sizes = self::join('subcategories', 'size_subcategory', 'subcategory_id')->limit($limit)->orderBy('size_id', 'DESC');

        if (isset($param['q'])) {
            $sizes->where(function (Builder $query) use ($param) {
                $query->where('size_name',  $param['q'])
                ->orWhere('size_sign',  $param['q'])
                ->orWhere('size_code', 'like', '%' . $param['q'] . '%')
                ->orWhere('subcategory_code', 'like', '%' . $param['q'] . '%')
                ->orWhere('subcategory_name',  $param['q']);
            });
            unset($param['q']);
        }

        if($param) $sizes->where($param);

        if($lastId) $sizes->where('size_id', '<', $lastId);

        if($id) $sizes->where('size_id', $id);

        return $id ? $sizes->first() : $sizes->get();
    }


    static function submit($params, $id)
    {
        if($id) return self::where('size_id', $id)->update($params) ? $id : false;
        $status = self::create($params);
        return $status ? $status->id : false;
    }
}