<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    /**
     *
     */
    public function getIndex ()
    {
        $assign['feedbacks'] = DB::select('SELECT id,(SELECT nickname FROM users WHERE feed_backs.F_user_Id = users.id) AS "author",(SELECT content FROM timelines WHERE feed_backs.F_related_Id = timelines.id) AS "content",F_related_Id,F_content,F_date,F_user_Id FROM feed_backs WHERE F_related_Id IS NOT NULL ORDER BY id DESC ');
        return view('dashboard.report.index', $assign);
    }
}
