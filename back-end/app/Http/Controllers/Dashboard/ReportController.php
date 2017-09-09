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
        $assign['timelines'] = DB::select('SELECT (SELECT nickname FROM users WHERE feed_backs.F_user_Id = users.id) AS "author",(SELECT content FROM timelines WHERE feed_backs.F_related_Id = timelines.id) AS "content",F_related_Id,F_content,F_date FROM feed_backs WHERE F_related_Id IS NOT NULL ORDER BY id DESC ');
        return view('dashboard.report.index', $assign);
    }
}
