<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product_color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'prodcolor_code',
        'prodcolor_name',
        'prodcolor_product',
        'prodcolor_media',
        'prodcolor_minqty',
        'prodcolor_maxqty',
        'prodcolor_status'
    ];



    static function createProduct($colorParams, $sizeParams)
    {
        try {
            DB::beginTransaction();
            if (self::insert($colorParams)) {
                $status = Product_size::insert($sizeParams);
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => 'Failed to insert colors'];
            }
            DB::commit();
            return ['status' => boolval($status), 'id' => $status ? $status->id : false];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'error: ' . $e->getMessage()];
        }
    }
}