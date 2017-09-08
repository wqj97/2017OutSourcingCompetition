@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            @include('dashboard.topic._sidenav')
        </div>

        <div class="col-sm-10">
            <div id="section-breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/dashboard') }}">ArcInPic</a></li>
                    <li><a href="{{ url('/dashboard/topic') }}">{{ trans('dashboard.Topic') }}</a></li>
                    <li class="active">{{ trans('dashboard.List') }}</li>
                </ol>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="搜索内容或用户">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button">搜索内容或用户</button>
                        </span>
                    </div>
                </div>
            </div>

            <div id="section-mainbody">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('dashboard.Author') }}</th>
                            <th>{{ trans('dashboard.Title') }}</th>
                            <th>{{ trans('dashboard.Star Num') }}</th>
                            <th>{{ trans('dashboard.Thumb Up Num') }}</th>
                            <th>{{ trans('dashboard.Thumb Down Num') }}</th>
                            <th>{{ trans('dashboard.Comment Num') }}</th>
                            <th>{{ trans('dashboard.Created At') }}</th>
                            <th>{{ trans('dashboard.Operations') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topics as $topic)
                        <tr>
                            <td>{{ $topic->author->nickname }}</td>
                            <td>{{ $topic->title }}</td>
                            <td>{{ $topic->star_num }}</td>
                            <td>{{ $topic->thumb_up_num }}</td>
                            <td>{{ $topic->thumb_down_num }}</td>
                            <td>{{ $topic->comment_num }}</td>
                            <td>{{ $topic->created_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        操作<span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">修改</a></li>
                                        <li><a href="#">置顶</a></li>
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
                    {!! $topics->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

