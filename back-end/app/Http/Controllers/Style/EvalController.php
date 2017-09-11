<?php
/**
 * Created by PhpStorm.
 * User: wanqianjun
 * Date: 2017/7/31
 * Time: 10:59
 */

namespace App\Http\Controllers\Style;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EvalController extends Controller
{
    public function getIndex ()
    {
        return view('api.index');
    }

    public function postIndex (Request $request)
    {
        $this->addHot($request->styleId);
        $img = preg_replace('/data:image\/(jpeg|jpg|png);base64,/i', '', $request->image);
        $hash = sha1($request->image);
        $file_path = 'unStyleImage/' . $hash . '.jpg';
        $target_path = 'styledImage/' . $hash . '_' . $request->styleId . '.jpg';
        $in_path = storage_path() . '/app/' . $file_path;
        $out_path = storage_path() . '/app/' . $target_path;
        if (Storage::exists($target_path)) {
            return base64_encode(Storage::get($target_path));
        }
        if (!Storage::exists($file_path)) {
            file_put_contents($in_path, base64_decode($img));
        }
        $script = DB::select('SELECT S_script FROM style_list WHERE S_Id = ?', [$request->styleId])[0]->S_script;
        $this->evaluation($in_path, $out_path, $script);
        return base64_encode(Storage::get($target_path));
    }

    private function evaluation ($in_path, $out_path, $script)
    {
        return exec("source ~/tensorflow/bin/activate&&python " . $_SERVER['DOCUMENT_ROOT'] . "/pythonScript/" .
            $script . ' --image_file ' . $in_path . ' --out_put_path ' . $out_path);
//        return exec("python " . $_SERVER['DOCUMENT_ROOT'] . "/pythonScript/" .
//            $script . ' --image_file ' . $in_path . ' --out_put_path ' . $out_path);
    }

    private function addHot ($Id)
    {
        if (!isset($Id)) {
            return;
        }
        $hotHeap = Cache::get('hot', []);
        if (count($hotHeap) > 100) {
            array_splice($hotHeap, 0, 1);
        }
        $hotHeap[] = $Id;
        Cache::put('hot', $hotHeap, 7200);
    }
}