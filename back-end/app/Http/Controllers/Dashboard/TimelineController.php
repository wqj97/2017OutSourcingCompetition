<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Timeline;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
    /**
     *
     */
    public function getIndex()
    {
        $assign['timelines'] = Timeline::orderBy('id', 'desc')->paginate();
        return view('dashboard.timeline.index', $assign);
    }


    public function getById (Request $request)
    {
        return DB::select('SELECT content FROM timelines WHERE id = ?', [$request->id])[0]->content;
    }

    public function postUpdate (Request $request) {
        return DB::update('update timelines set content = ? WHERE id = ?',[$request->content,$request->id]);
    }
}
