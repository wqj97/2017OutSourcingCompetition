<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedBackController extends Controller
{
    /**
     *
     */
    public function getIndex ()
    {
        $assign['feed_backs'] = DB::select('SELECT id,(SELECT nickname FROM users WHERE users.id = feed_backs.F_user_Id) AS nickname,F_content,F_date,F_related_Id,F_user_Id FROM feed_backs WHERE F_related_Id is null ORDER BY id DESC');
        return view('dashboard.feedback.index', $assign);
    }

    public function getReply (Request $request)
    {
        Db::insert('UPDATE feed_backs SET F_reply = ? WHERE id = ?', ['这是测试', 1]);
        DB::insert('INSERT INTO notices (user_id, initiator_user_id,entity_type,entity_id,type_id) VALUES (?,?,?,?,?)', [3, 1, 'App\\FeedBack', 1, 23]);
    }
}
