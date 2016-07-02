<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        if (isset($fields['name'])) $name = $fields['name'];
        else $name = null;

        if (isset($fields['namesize'])) $md5['namesize'] = $fields['namesize'];
        if (isset($fields['namecolor'])) $md5['namecolor'] = $fields['namecolor'];
        if (isset($fields['namex'])) $md5['namex'] = $fields['namex'];
        if (isset($fields['namey'])) $md5['namey'] = $fields['namey'];
        if (isset($fields['position'])) $md5['position'] = $fields['position'];
        if (isset($fields['size'])) $md5['size'] = $fields['size'];
        if (isset($fields['x'])) $md5['x'] = $fields['x'];
        if (isset($fields['y'])) $md5['y'] = $fields['y'];

        $key = md5(serialize($md5));

        if (!Cache::has($key)) {
            Cache::put($key, $md5, 60*24*30);
        }
//        dd($md5);
        return $this->composeImage($id, $md5, $name);
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

    public function pageCached($id, $key, $name=null, Request $request){
        $fields = $request->all();
        if (Cache::has($key)) {
            $md5 = Cache::get($key);
            $url = url();
            $url = $url.'/file?id='.$id;
            if (!is_null($name)) $url = $url.'&name='.$name;

            if (isset($md5['namesize'])) $url = $url.'&namesize='.$md5['namesize'];
            if (isset($md5['namecolor'])) $url = $url.'&namecolor='.$md5['namecolor'];
            if (isset($md5['namex'])) $url = $url.'&namex='.$md5['namex'];
            if (isset($md5['namey'])) $url = $url.'&namey='.$md5['namey'];
            if (isset($md5['position'])) $url = $url.'&position='.$md5['position'];
            if (isset($md5['size'])) $url = $url.'&size='.$md5['size'];
            if (isset($md5['x'])) $url = $url.'&x='.$md5['x'];
            if (isset($md5['y'])) $url = $url.'&y='.$md5['y'];

            $url = $url.'&file='.$md5['file'];

            $app_id = isset($fields['app_id'])?$fields['app_id']:'';
            $site = isset($fields['site'])?$fields['site']:'';
            $title = isset($fields['title'])?$fields['title']:'';

            $meta = [];
            $sitename = DB::select('select * from wp_options where `option_name` LIKE \'siteurl\'');
            if (isset($fields['post_name'])) $meta['url'] = $sitename[0]->option_value.'/'.$fields['post_name'];
//            if (isset($fields['post_name'])) $meta['url'] = url().'/'.$fields['post_name'];
            else $meta['url'] = '';
            if (isset($fields['post'])) {
                $postmeta = DB::select('select * from wp_postmeta where post_id = '.$fields['post']);
                foreach ($postmeta as $item) {
                    if ($item->meta_key=="_yoast_wpseo_opengraph-description") $meta['description'] = $item->meta_value;
                    if ($item->meta_key=="_yoast_wpseo_opengraph-title") $meta['title'] = $item->meta_value;
                }
            } else {
                $meta['description'] = '';
                $meta['title'] = '';
            }


            return view('page', compact('url','app_id','site','title','meta'));
        } else return "chave errada";
    }

    public function redirect($post){
        $sitename = DB::select('select * from wp_options where `option_name` LIKE \'siteurl\'');
        var_dump($sitename);
//        return redirect()->to($sitename[0]->option_value.'/'.$post);
//        return Redirect::to();
    }
    public function fileCached($id, $key, $name=null, Request $request){
        $fields = $request->all();
        if (Cache::has($key)) {
            $md5 = Cache::get($key);
            return $this->composeImage($id, $md5, $name);
        } else return "chave errada";
    }

    /**
     * @param $id
     * @param $md5
     * @return mixed
     */
    protected function composeImage($id, $md5, $name = null)
    {
        $manager = new ImageManager(array('driver' => 'gd', 'allow_url_fopen' => true));

        $size = isset($md5['size']) ? $md5['size'] : '116x116';
        if ($size=="large" || $size=="normal" || $size=="small" || $size=="album" || $size=="square") {
            $source = 'https://graph.facebook.com/' . $id . '/picture?type=' . $size;
//            $image = $manager->make($source);
            $image = $manager->cache(function($image) use ($source) {
                $image->make($source);
            }, (60*24*30), true);
        } else {
            $source = 'https://graph.facebook.com/' . $id . '/picture?type=large';
            $resize = explode('x', $size);
//            $image = $manager->make($source)->resize($resize[0], $resize[1]);
            $image = $manager->cache(function($image) use ($source, $resize) {
                $image->make($source)->resize($resize[0], $resize[1]);
            }, (60*24*30), true);
        }

//        $background = $manager->cache(function($image) use ($md5) {
//            $image->make($md5['file']);
//        }, (60*24*30), true);

        $background = $manager->make($md5['file']);

        $position = isset($md5['position']) ? $md5['position'] : 'center';
        $x = isset($md5['x']) ? $md5['x'] : 0;
        $y = isset($md5['y']) ? $md5['y'] : 0;
        $background->insert($image, $position, $x, $y);

        $namesize = isset($md5['namesize']) ? $md5['namesize'] : 24;
        $namecolor = isset($md5['namecolor']) ? $md5['namecolor'] : '000000';
        $namex = isset($md5['namex']) ? $md5['namex'] : 270;
        $namey = isset($md5['namey']) ? $md5['namey'] : 230;
        if (!is_null($name))
            $background->text(str_replace('-',' ',$name), $namex, $namey, function ($font) use ($namesize,$namecolor) {
//                $font->file(5);

                $font->file(base_path('resources/fonts').'/arialbd.ttf');
                $font->size($namesize);
                $font->color('#'.$namecolor);
                $font->align('left');
                $font->valign('top');
            });

        return $background->response('jpg', 70);
    }
}
