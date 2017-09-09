<?php
/**
 * Created by PhpStorm.
 * User: wanqianjun
 * Date: 2017/8/4
 * Time: 11:14
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class feedBackController extends Controller
{
    public function getIndex ()
    {
        return view('api.index');
    }

    public function postIndex (Request $request)
    {
        $userId = $request->userId;
        $content = $request->content;
        $related_Id = $request->timeLineId;
        DB::insert('INSERT INTO feed_backs (F_user_Id,F_related_Id,F_content) VALUES (?,?,?)', [$userId, $related_Id, $content]);
        return '';
    }
}