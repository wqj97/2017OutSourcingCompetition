@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                @include('dashboard.timeline._sidenav')
            </div>

            <div class="col-sm-10">
                <div id="section-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/dashboard') }}">ArcInPic</a></li>
                        <li><a href="{{ url('/dashboard/timeline') }}">{{ trans('dashboard.Timeline') }}</a></li>
                        <li class="active">{{ trans('dashboard.List') }}</li>
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
                            <th>{{ trans('dashboard.Operations') }}</th>
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
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            操作<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onclick="editModel({{$timeline->id}})">修改</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li class="text-danger"><a href="#" onclick="sendDelete({{$timeline->id}})">删除</a></li>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">帖子内容:</label>
                        <input type="text" class="form-control" id="recipient-name" v-model="content">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="sendChange()">保存更改</button>
                </div>
            </div>
        </div>
    </div>
    <script>
      let vm = new Vue({
        el: '#editModal',
        data () {
          return {
            id: 0,
            content: ''
          }
        },
        methods: {
          sendChange () {
            $.ajax({
              url: './timeline/update',
              type: 'post',
              data: {
                id: this.id,
                content: this.content
              },
              success () {
                $('#editModal').modal('hide')
                window.location.reload()
              }
            })
          }

        }
      })

      function editModel (id) {
        $.ajax({
          url: './timeline/by-id',
          data: {
            id: id
          },
          success (data) {
            vm.id = id
            vm.content = data;
            $('#editModal').modal()
          }
        })
      }

      function sendDelete (id) {
        if (confirm('确认要删除吗!!')) {
          $.ajax({
                url: '/api/timeline/destroy',
                type: 'post',
                data: {
                  id: id
                },
                success () {
                  window.location.reload()
                }
              }
          )
        }
      }
    </script>
@endsection

