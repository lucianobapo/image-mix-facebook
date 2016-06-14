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

        $manager = new ImageManager(array('driver' => 'gd','allow_url_fopen'=>true));

        switch ($size){
            case "large":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$size;
                $image = $manager->make($source);
                break;
            case "normal":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$size;
                $image = $manager->make($source);
                break;
            case "small":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$size;
                $image = $manager->make($source);
                break;
            case "album":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$size;
                $image = $manager->make($source);
                break;
            case "square":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$size;
                $image = $manager->make($source);
                break;
            default:
                $source = 'https://graph.facebook.com/'.$id.'/picture?type=large';
                $resize = explode('x',$size);
                $image = $manager->make($source)->resize($resize[0], $resize[1]);
                break;
        }

        $background = $manager->make($request->all()['file']);
        $background->insert($image, 'center');

        return $background->response();
    }
}
