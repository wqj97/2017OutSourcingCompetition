@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                @include('dashboard.feedback._sidenav')
            </div>

            <div class="col-sm-10">
                <div id="section-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/dashboard') }}">ArcInPic</a></li>
                        <li><a href="{{ url('/dashboard/feedback') }}">{{ trans('dashboard.feedback') }}</a></li>
                        <li class="active">{{ trans('dashboard.List') }}</li>
                    </ol>
                </div>

                <div id="section-mainbody">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>{{ trans('dashboard.Nickname') }}</th>
                            <th>{{ trans('dashboard.Content') }}</th>
                            <th>{{ trans('dashboard.Date') }}</th>
                            <th>{{ trans('dashboard.Operations') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($feed_backs as $feed_back)
                            <tr>
                                <td>{{ $feed_back->nickname }}</td>
                                <td>{{ $feed_back->F_content }}</td>
                                <td>{{ $feed_back->F_date }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            操作<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">回复</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li class="text-danger"><a href="#">删除</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

