@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            @include('dashboard.report._sidenav')
        </div>

        <div class="col-sm-10">
            <div id="section-breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/dashboard') }}">ArcInPic</a></li>
                    <li><a href="{{ url('/dashboard/report') }}">{{ trans('dashboard.report') }}</a></li>
                    <li class="active">{{ trans('dashboard.List') }}</li>
                </ol>
            </div>

            <div id="section-mainbody">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>被举报话题ID</th>
                            <th>被举报用户名</th>
                            <th>被举报内容</th>
                            <th>举报图片</th>
                            <th>举报时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timelines as $timeline)
                        <tr>
                            <td>{{ $timeline->id }}</td>
                            <td>{{ $timeline->author->nickname }}</td>
                            <td>{{ $timeline->content }}</td>
                            <?php
                            $imgs = \App\TimelineImg::getImgs($timeline->imgs);
                            ?>
                            <td>
                                @foreach ($imgs as $img)
                                    <img style="width:60px" src="{{ \App\TimelineImg::getImgUrl($img->uri) }}">
                                @endforeach
                            </td>
                            <td>{{ $timeline->created_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        操作<span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">封禁该条</a></li>
                                        <li><a href="#">回复举报用户</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li class="text-danger"><a href="#">删除</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div>
                    {!! $timelines->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

