<?php

namespace App\Http\Controllers;

use App\Models\Product_media;
use Illuminate\Http\Request;

class ProductMediaController extends Controller
{
    function fetch($id)
    {
        echo json_encode(Product_media::fetch(0, [['media_product', $id]]));
    }
}