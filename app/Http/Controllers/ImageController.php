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
        $md5 = [];
        $md5['file'] = $fields['file'];
        $md5['position'] = isset($fields['position'])?$fields['position']:'center';
        $md5['x'] = isset($fields['x'])?$fields['x']:0;
        $md5['y'] = isset($fields['y'])?$fields['y']:0;
        $md5['size'] = isset($fields['size'])?$fields['size']:'116x116';

        $key = md5($md5);
        if (!Cache::has($key)) {
            Cache::put($key, $md5, 60*24*30);
            dd($key);
        }

        $manager = new ImageManager(array('driver' => 'gd','allow_url_fopen'=>true));

        switch ($md5['size']){
            case "large":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$md5['size'];
                $image = $manager->make($source);
                break;
            case "normal":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$md5['size'];
                $image = $manager->make($source);
                break;
            case "small":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$md5['size'];
                $image = $manager->make($source);
                break;
            case "album":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$md5['size'];
                $image = $manager->make($source);
                break;
            case "square":
                $source = 'https://graph.facebook.com/'.$id.'/picture?type='.$md5['size'];
                $image = $manager->make($source);
                break;
            default:
                $source = 'https://graph.facebook.com/'.$id.'/picture?type=large';
                $resize = explode('x',$md5['size']);
                $image = $manager->make($source)->resize($resize[0], $resize[1]);
                break;
        }

        $background = $manager->make($md5['file']);
        $background->insert($image, $md5['position'], $md5['x'], $md5['x']);

        return $background->response();
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
        if (Cache::has($key)) {
            $md5 = Cache::get($key);
//            $fields = $request->all();
//            $id = $fields['id'];
//            $file = $fields['file'];
            $url = url();
            $url = $url.'/file?id='.$id;
            if (isset($md5['position'])) $url = $url.'&position='.$md5['position'];
            if (isset($md5['x'])) $url = $url.'&x='.$md5['x'];
            if (isset($md5['y'])) $url = $url.'&y='.$md5['y'];
            if (isset($md5['size'])) $url = $url.'&size='.$md5['size'];
            $url = $url.'&file='.$md5['file'];

            $app_id = isset($fields['app_id'])?$fields['app_id']:'';
            $site = isset($fields['site'])?$fields['site']:url();
            $title = isset($fields['title'])?$fields['title']:'teste';

            return view('page', compact('url','app_id','site','title'));
        }
    }
}
