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

    public function file(Request $request){
//        dd($request->all()['file']);
        $fields = $request->all();
        $id = $fields['id'];
        $file = $fields['file'];
        $position = isset($fields['position'])?$fields['position']:'center';
        $x = isset($fields['x'])?$fields['x']:0;
        $y = isset($fields['y'])?$fields['y']:0;
        $size = isset($fields['size'])?$fields['size']:'116x116';

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

        $background = $manager->make($file);
        $background->insert($image, $position, $x, $y);

        return $background->response();
    }

    public function page(Request $request){
        $fields = $request->all();
        $id = $fields['id'];
        $file = $fields['file'];
        $position = isset($fields['position'])?$fields['position']:'center';
        $x = isset($fields['x'])?$fields['x']:0;
        $y = isset($fields['y'])?$fields['y']:0;
        $size = isset($fields['size'])?$fields['size']:'116x116';
        $url = url()."/file?id=$id&file=$file&position=$position&x=$x&y=$y&size=$size";
        return view('page')->with(['url'=>urlencode($url)]);

    }
}
