<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $sizes = self::join('size_subcategories', 'size_subcategory', 'subcategory_id')->limit($limit)->orderBy('size_id', 'DESC');

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
