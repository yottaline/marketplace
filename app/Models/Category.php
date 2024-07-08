<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_code',
        'category_name',
        'category_brand',
        'category_status'
    ];

    static function fetch($id = 0, $param = null, $limit = null, $lastId = null)
    {
        $categories = self::join('brands', 'category_brand', 'brand_id')->limit($limit)->orderBy('category_id', 'DESC');

        if (isset($param['q'])) {
            $categories->where(function (Builder $query) use ($param) {
                $query->where('category_name',  $param['q'])
                ->orWhere('category_code', 'like', '%' . $param['q'] . '%')
                ->orWhere('brand_name',  $param['q']);
            });
            unset($param['q']);
        }

        if($param) $categories->where($param);

        if($lastId) $categories->where('category_id', '<', $lastId);

        if($id) $categories->where('category_id', $id);

        return $id ? $categories->first() : $categories->get();
    }

    static function submit($params, $id)
    {
        if($id) return self::where('category_id', $id)->update($params) ? $id : false;
        $status = self::create($params);
        return $status ? $status->id : false;
    }
}