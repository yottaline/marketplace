<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Brand extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'brand_code',
        'brand_name',
        'brand_status'
    ];

    static function fetch($id = 0, $param = null, $limit = null, $lastId = null)
    {
        $brands = self::limit($limit)->orderBy('brand_id', 'DESC');

        if (isset($params['q'])) {
            $brands->where(function (Builder $query) use ($params) {
                $query->where('brand_code', 'like', '%' . $params['q'] . '%')
                ->orWhere('brand_name', $params['q']);
            });

            unset($params['q']);
        }

        if($param) $brands->where($param);

        if($lastId) $brands->where('brand_id', '<', $lastId);

        if($id) $brands->where('brand_id', $id);

        return $id ? $brands->first() : $brands->get();
    }

    static function submit($params, $id)
    {
        if($id) return self::where('brand_id', $id)->update($params) ? $id : false;
        $status = self::create($params);
        return $status ? $status->id : false;
    }
}