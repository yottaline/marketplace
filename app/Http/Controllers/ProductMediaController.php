<?php

namespace App\Http\Controllers;

use App\Models\Product_media;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductMediaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function load(Request $request)
    {
        echo json_encode(Product_media::fetch(0, [['product_id', $request->product_id]]));
    }


    function submit(Request $request)
    {
        $id = $request->id;

        $param = [
            'media_product'  => $request->product_id,
            'media_type'  => 'image',
        ];

        $media = $request->file('media');

        foreach ($media as $image) {
            $imagesPath = public_path('images');
            $thumbnailPath = public_path('media/product/'. $request->product_id );
            $name = uniqidReal(8) . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());

                 // Create the directory if it does not exist
                 if (!file_exists($thumbnailPath)) {
                    mkdir($thumbnailPath, 0755, true);
                }
                if (!file_exists($imagesPath)) {
                    mkdir($imagesPath, 0755, true);
                }

                 // Get image dimensions
        $width = $img->width();
        $height = $img->height();
            if($width !== 200 || $height !== 200){
                // $img->resize(300, 300, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($thumbnailPath. '/' .$name);

                $img->resize(300, 300);
                $img->save($thumbnailPath. '/' .$name);
            }

            $param['media_url'] = $name;
            $result = Product_media::submit($param, $id);
        }
        echo json_encode([
            'status' => boolval($result),
            'data' => $result ? Product_media::fetch(0, [['media_id', $result],['product_id', $request->product_id]]) : []
        ]);
    }

    function destroy(Request $request)
    {
        $id = $request->id;
        $product = $request->product_id;
        $record = Product_media::fetch($id);
        if ($record->media_url) {
          $result =  File::delete('media/product/'. $product .'/' . $record->media_url);
          Product_media::destroyed($id);
        }
        echo json_encode(['status' => boolval($result)]);
    }

}