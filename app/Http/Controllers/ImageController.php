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

        if (isset($fields['namesize'])) $md5['namesize'] = $fields['namesize'];
        if (isset($fields['namecolor'])) $md5['namecolor'] = $fields['namecolor'];
        if (isset($fields['namex'])) $md5['namex'] = $fields['namex'];
        if (isset($fields['namey'])) $md5['namey'] = $fields['namey'];
        if (isset($fields['position'])) $md5['position'] = $fields['position'];
        if (isset($fields['size'])) $md5['size'] = $fields['size'];
        if (isset($fields['x'])) $md5['x'] = $fields['x'];
        if (isset($fields['y'])) $md5['y'] = $fields['y'];

        $key = md5(serialize($md5));

        if (isset($fields['name'])) $md5['name'] = $fields['name'];
        if (!Cache::has($key)) {
            Cache::put($key, $md5, 60*24*30);
        }
        return $this->composeImage($id, $md5);
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

    public function fileCached($id, $key, Request $request){
        $fields = $request->all();
        if (Cache::has($key)) {
            $md5 = Cache::get($key);

            return $this->composeImage($id, $md5);
        } else return "chave errada";
    }

    /**
     * @param $id
     * @param $md5
     * @return mixed
     */
    protected function composeImage($id, $md5)
    {
        $manager = new ImageManager(array('driver' => 'gd', 'allow_url_fopen' => true));

        $size = isset($md5['size']) ? $md5['size'] : '116x116';
        switch ($size) {
            case "large":
                $source = 'https://graph.facebook.com/' . $id . '/picture?type=' . $size;
                $image = $manager->make($source);
                break;
            case "normal":
                $source = 'https://graph.facebook.com/' . $id . '/picture?type=' . $size;
                $image = $manager->make($source);
                break;
            case "small":
                $source = 'https://graph.facebook.com/' . $id . '/picture?type=' . $size;
                $image = $manager->make($source);
                break;
            case "album":
                $source = 'https://graph.facebook.com/' . $id . '/picture?type=' . $size;
                $image = $manager->make($source);
                break;
            case "square":
                $source = 'https://graph.facebook.com/' . $id . '/picture?type=' . $size;
                $image = $manager->make($source);
                break;
            default:
                $source = 'https://graph.facebook.com/' . $id . '/picture?type=large';
                $resize = explode('x', $size);
                $image = $manager->make($source)->resize($resize[0], $resize[1]);
                break;
        }

        $background = $manager->make($md5['file']);

        $position = isset($md5['position']) ? $md5['position'] : 'center';
        $x = isset($md5['x']) ? $md5['x'] : 0;
        $y = isset($md5['y']) ? $md5['y'] : 0;
        $background->insert($image, $position, $x, $y);

        $namesize = isset($md5['namesize']) ? $md5['namesize'] : 24;
        $namecolor = isset($md5['namecolor']) ? $md5['namecolor'] : '#000000';
        $namex = isset($md5['namex']) ? $md5['namex'] : 270;
        $namey = isset($md5['namey']) ? $md5['namey'] : 230;
        if (isset($md5['name']))
            $background->text($md5['name'], $namex, $namey, function ($font) use ($namesize,$namecolor) {
//                $font->file(5);
                $font->file(base_path('resources/fonts').'/arial.ttf');
                $font->size($namesize);
                $font->color($namecolor);
                $font->align('left');
                $font->valign('top');
            });

        return $background->response('jpg', 40);
    }
}
