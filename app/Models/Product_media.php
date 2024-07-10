<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_media extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'media_product',
        'media_url',
        'media_type',
        'media_status'
    ];


    static function fetch($id = 0, $params = null, $ids = null)
    {
        $medias = self::join('products', 'media_product', 'product_id')->orderBy('media_id', 'DESC');

        if($params) $medias->where($params);

        if($id) $medias->where('media_id', $id);

        return $id ? $medias->first() : $medias->get();
    }

    static function submit($params, $id)
    {
        if($id) return self::where('media_id', $id)->update($params) ? $id : false;
        $status = self::create($params);
        return $status ? $status->id : false;
    }
}