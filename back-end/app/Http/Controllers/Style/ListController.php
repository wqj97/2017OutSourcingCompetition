<?php
/**
 * Created by PhpStorm.
 * User: wanqianjun
 * Date: 2017/7/28
 * Time: 21:57
 */

namespace App\Http\Controllers\Style;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{

    public function getIndex (Request $request)
    {
        $category = DB::select('SELECT SC_Id,SC_name FROM style_category');
        $styles = [];
        $styleFlat = [];
        foreach ($category as $row) {
            $temp = [];
            $Db_result = DB::select('SELECT S_source_img,S_info,S_Id FROM style_list WHERE S_parent = ?',
                [$row->SC_Id]);
            $temp[] = $Db_result;
            foreach ($Db_result as $value) {
                $styleFlat[] = $value;
            }
            $row->style_list = $temp[0];
            unset($row->SC_Id);
            $styles[] = $row;

        }
        return ["category" => $styles, 'recommend' => $this->getRecommend($styleFlat)];
    }

    public function getClean ()
    {
        return Cache::put('hot', [], 0);
    }

    public function getDump ()
    {
        return Cache::get('hot');
    }

    private function getRecommend ($styles)
    {
        // 降序排列最多使用的风格
        $recommend = [];
        $hot = Cache::get('hot', []);
        $hot = array_count_values($hot);
        krsort($hot);
        //
        // 取出最多的五个
        $hotCount = count($hot);
        if ($hotCount > 5) {
            $hot = array_slice($hot, 0, 5,true);
        }
        //
        // 去除styles里已经取出的五个最多风格, 以方便后面的随机推荐
        $styleTemp = [];
        foreach ($styles as $style) {
            if (array_search($style->S_Id, array_keys($hot)) === false) {
                $styleTemp[] = $style;
            }
        }
        $styles = $styleTemp;
        foreach ($hot as $styleId=>$count) {
            $recommend[] = DB::select('SELECT S_source_img,S_info,S_Id FROM style_list WHERE S_Id = ?', [$styleId])[0];
        }
        if ($hotCount <= 5) {
            shuffle($styles);
            foreach (array_splice($styles, 0, 5 - $hotCount) as $style) {
                $recommend[] = $style;
            }
        }
        foreach (array_splice($styles, 0, 2) as $style) {
            array_unshift($recommend, $style);
        }

        return $recommend;
    }
}