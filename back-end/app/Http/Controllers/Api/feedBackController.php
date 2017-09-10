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

    public function postDestroy (Request $request)
    {
        DB::delete('delete from feed_backs WHERE id = ?',[$request->id]);
    }

    public function postReply (Request $request)
    {
        DB::update('UPDATE feed_backs SET F_reply = ? WHERE id = ?', [$request->content, $request->id]);
        DB::insert('INSERT INTO notices (user_id, initiator_user_id, entity_id, entity_type, type_id) VALUES (?,?,?,?,?)',
            [
                1, $request->userId, $request->id, 'App\FeedBack', '23'
            ]);
    }

    public function postBan (Request $request)
    {
        DB::update('update timelines set deleted_at = current_time() WHERE id = ?',[$request]);
    }
}