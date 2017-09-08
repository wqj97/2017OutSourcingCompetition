<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\TriggerNoticeEvent;
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
        $assign['feed_backs'] = DB::select('SELECT (SELECT nickname FROM users WHERE users.id = feed_back.F_user_Id) AS nickname,F_content,F_date FROM feed_back ORDER BY F_Id DESC');
        return view('dashboard.feedback.index', $assign);
    }

    public function getReply (Request $request)
    {
        event(new TriggerNoticeEvent(0, 0, 'feedBackNotice'));
    }
}
