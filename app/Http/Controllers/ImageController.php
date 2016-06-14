<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\ImageManager;
//use Intervention\Image\Facades\Image as Image;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function file($id, $size, Request $request){
//        dd($request->all()['file']);
        $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$size;
        // create an image manager instance with favored driver
        $manager = new ImageManager(array('driver' => 'imagick','allow_url_fopen'=>true));

        // to finally create image instances
        $image = $manager->make($source);
        $background = $manager->make($request->all()['file']);
        $background->insert($image, 'center');

//        $image = \Image::make($source);
        return $background->response();
//        dd($source);
    }
}
