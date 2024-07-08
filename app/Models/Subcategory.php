<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Subcategory extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'subcategory_code',
        'subcategory_name',
        'subcategory_category',
        'subcategory_status'
    ];

    static function fetch($id = 0, $param = null, $limit = null, $lastId = null)
    {
        $subcategories = self::join('categories', 'subcategory_category', 'category_id')->orderBy('subcategory_id', 'DESC')->limit($limit);

        if (isset($param['q'])) {
            $subcategories->where(function (Builder $query) use ($param) {
                $query->where('category_name',  $param['q'])
                ->orWhere('category_code', 'like', '%' . $param['q'] . '%')
                ->orWhere('subcategory_code', 'like', '%' . $param['q'] . '%')
                ->orWhere('subcategory_name',  $param['q']);
            });
            unset($param['q']);
        }

        if($param) $subcategories->where($param);

        if($lastId) $subcategories->where('subcategory_id', '<', $lastId);

        if($id) $subcategories->where('subcategory_id', $id);

        return $id ? $subcategories->first() : $subcategories->get();

    }

    static function submit($params, $id)
    {
        if($id) return self::where('subcategory_id', $id)->update($params) ? $id : false;
        $status = self::create($params);
        return $status ? $status->id : false;
    }
}