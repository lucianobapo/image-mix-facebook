<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
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
        $md5['file'] = $fields['file'];
        if (isset($fields['position'])) $md5['position'] = $fields['position'];
        if (isset($fields['size'])) $md5['size'] = $fields['size'];
        if (isset($fields['x'])) $md5['x'] = $fields['x'];
        if (isset($fields['y'])) $md5['y'] = $fields['y'];

        $key = md5(serialize($md5));
        if (!Cache::has($key)) {
            Cache::put($key, $md5, 60*24*30);
        }
        $manager = new ImageManager(array('driver' => 'gd','allow_url_fopen'=>true));

        $size = isset($md5['size'])?$md5['size']:'116x116';
        $position = isset($md5['position'])?$md5['position']:'center';
        $x = isset($md5['x'])?$md5['x']:0;
        $y = isset($md5['y'])?$md5['y']:0;
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

        $background = $manager->make($md5['file']);
        $background->insert($image, $position, $x, $y);

        return $background->response('jpg',40);
    }

    public function page(Request $request){
        $fields = $request->all();
        $id = $fields['id'];
        $file = $fields['file'];
        $url = url();
        $url = $url.'/file?id='.$fields['id'];
        if (isset($fields['position'])) $url = $url.'&position='.$fields['position'];
        if (isset($fields['x'])) $url = $url.'&x='.$fields['x'];
        if (isset($fields['y'])) $url = $url.'&y='.$fields['y'];
        if (isset($fields['size'])) $url = $url.'&size='.$fields['size'];
        $url = $url.'&file='.$fields['file'];

        $app_id = isset($fields['app_id'])?$fields['app_id']:'';
        $site = isset($fields['site'])?$fields['site']:url();
        $title = isset($fields['title'])?$fields['title']:'teste';

        return view('page', compact('url','app_id','site','title'));
    }

    public function pageCached($id, $key, Request $request){
        $fields = $request->all();
        if (Cache::has($key)) {
            $md5 = Cache::get($key);
            $url = url();
            $url = $url.'/file?id='.$id;
            if (isset($md5['position'])) $url = $url.'&position='.$md5['position'];
            if (isset($md5['x'])) $url = $url.'&x='.$md5['x'];
            if (isset($md5['y'])) $url = $url.'&y='.$md5['y'];
            if (isset($md5['size'])) $url = $url.'&size='.$md5['size'];
            $url = $url.'&file='.$md5['file'];

            $app_id = isset($fields['app_id'])?$fields['app_id']:'';
            $site = isset($fields['site'])?$fields['site']:'';
            $title = isset($fields['title'])?$fields['title']:'';

            return view('page', compact('url','app_id','site','title'));
        } else return "chave errada";
    }
}
