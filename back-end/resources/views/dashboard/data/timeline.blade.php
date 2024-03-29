@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            @include('dashboard.data._sidenav')
        </div>

        <div class="col-sm-10">
            <div id="section-breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/dashboard') }}">ArcInPic</a></li>
                    <li><a href="{{ url('/dashboard/data') }}">{{ trans('dashboard.Data') }}</a></li>
                    <li class="active">{{ trans('dashboard.Timeline') }}</li>
                </ol>
            </div>

            <div id="section-mainbody">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ trans('dashboard.Author') }}</th>
                            <th>{{ trans('dashboard.Content') }}</th>
                            <th>{{ trans('dashboard.Imgs') }}</th>
                            <th>{{ trans('dashboard.Like Num') }}</th>
                            <th>{{ trans('dashboard.Comment Num') }}</th>
                            <th>{{ trans('dashboard.Created At') }}</th>
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
                            <td>{{ $timeline->like_num }}</td>
                            <td>{{ $timeline->comment_num }}</td>
                            <td>{{ $timeline->created_at }}</td>
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

