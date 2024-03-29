<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Topic;
use App\TopicNode;

class TopicController extends Controller
{
    /**
     *
     */
    public function getIndex()
    {
        $assign['topics'] = Topic::orderBy('id', 'desc')->paginate();
        return view('dashboard.topic.index', $assign);
    }

    public function getById (Request $request)
    {
        return DB::select('select title,content from topics WHERE id = ?',[$request->id]);
    }

    public function postUpdate (Request $request) {
        return DB::update('update topics set content = ?,title=? WHERE id = ?',[$request->content,$request->title,$request->id]);
    }

    public function postDestroy(Request $request){
        $this->validate($request, [
            'id' => 'required',
        ]);

        $Topic = Topic::findOrFail($request->id);

        if ($Topic->user_id === Auth::user()->user()->id || Auth::user()->user()->is_admin) {
            return $Topic->delete() ? ['success'] : response('fail', 500);
        }

        return abort(403, 'Insufficient permissions');
    }

    /**
     *
     */
    public function getNodes()
    {
        $assign['rootNodes'] = TopicNode::roots()->get();
        return view('dashboard.topic.nodes', $assign);
    }

    /**
     *
     */
    public function getNodeMoveLeft(Request $request)
    {
        $this->validate($request, [
            'id'        =>      'required|integer',
        ]);

        $Node = TopicNode::findOrFail($request->id);
        $r = $Node->moveLeft();

        Notification::success(trans('dashboard.Successful Operation'));
        return redirect()->to('dashboard/topic/nodes?edit=true');
    }

    /**
     *
     */
    public function getNodeMoveRight(Request $request)
    {
        $this->validate($request, [
            'id'        =>      'required|integer',
        ]);

        $Node = TopicNode::findOrFail($request->id);
        $r = $Node->moveRight();

        Notification::success(trans('dashboard.Successful Operation'));
        return redirect()->to('dashboard/topic/nodes?edit=true');
    }

    /**
     *
     */
    public function postNodeDestroy(Request $request)
    {
        $this->validate($request, [
            'id'        =>      'required|integer',
        ]);

        $Node = TopicNode::findOrFail($request->id);
        Topic::destroy($Node->topics->lists('id')->toArray());
        $Node->delete();

        Notification::success(trans('dashboard.Successful Operation'));
        return redirect()->to('dashboard/topic/nodes?edit=true');
    }

    /**
     *
     */
    public function postNodeAdd(Request $request)
    {
        $this->validate($request, [
            'parent_id'     =>      'required|integer',
            'name'          =>      'required|string',
        ]);

        $ParentNode = TopicNode::find($request->parent_id);
        $TopicNode = TopicNode::create(['name' => $request->name]);

        if ($ParentNode) {
            $TopicNode->makeChildOf($ParentNode);
        } else {
            $TopicNode->makeRoot();
        }

        Notification::success(trans('dashboard.Successful Operation'));
        return redirect()->to('dashboard/topic/nodes?edit=true');
    }

    /**
     *
     */
    public function postNodeRename(Request $request)
    {
        $this->validate($request, [
            'id'        =>      'required|integer',
            'name'      =>      'required|string',
        ]);

        TopicNode::findOrFail($request->id)->update(['name' => $request->name]);

        Notification::success(trans('dashboard.Successful Operation'));
        return redirect()->to('dashboard/topic/nodes?edit=true');
    }
}
