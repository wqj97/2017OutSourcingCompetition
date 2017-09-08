<?php

namespace App\Http\Controllers\Dashboard;

use App\Timeline;
use App\Http\Controllers\Controller;


class ReportController extends Controller
{
    /**
     *
     */
    public function getIndex()
    {
        $assign['timelines'] = Timeline::orderBy('id')->paginate();
        return view('dashboard.report.index', $assign);
    }
}
