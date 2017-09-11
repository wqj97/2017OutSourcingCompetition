<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     *
     */
    public function getIndex ()
    {
        $assign['users'] = User::orderBy('id', 'desc')->paginate();
        return view('dashboard.user.index', $assign);
    }

    public function getById (Request $request)
    {
        return DB::select('SELECT nickname,phone,is_admin,gender,bio FROM users WHERE id = ?', [$request->id]);
    }

    public function postUpdate (Request $request)
    {
        return DB::update('UPDATE users SET nickname = ?, phone = ?, is_admin =?,gender = ?,bio = ? WHERE id = ?', [$request->nickname,
            $request->phone, $request->is_admin, $request->gender, $request->bio, $request->id]);
    }
}
