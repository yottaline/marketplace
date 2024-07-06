<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}