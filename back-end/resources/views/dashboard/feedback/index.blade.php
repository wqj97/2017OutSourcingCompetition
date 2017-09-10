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
                                            <li><a href="#"
                                                   onclick="replyUser({{$feed_back->F_user_Id}},0,{{$feed_back->id}})">答复</a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li class="text-danger"><a href="#" onclick="sendDelete({{$feed_back->id}})">删除</a></li>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">答复内容:</label>
                        <input type="text" class="form-control" id="recipient-name" v-model="content">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="sendReply()">发送答复</button>
                </div>
            </div>
        </div>
    </div>
    <script>
      let vm = new Vue({
        el: '#editModal',
        data () {
          return {
            reply_id: 0,
            userId: 0,
            content: '',
            relatedId: 0
          }
        },
        methods: {
          sendReply () {
            $.ajax({
              url: '/api/feedBack/reply',
              type: 'post',
              data: {
                id: this.reply_id,
                userId: this.userId,
                content: this.content,
                timeLineId: this.relatedId
              },
              success () {
                $('#editModal').modal('hide')
                window.location.reload()
              }
            })
          }

        }
      })

      function replyUser (target_user_id, relatedId, reply_id) {
        vm.userId = target_user_id
        vm.relatedId = relatedId
        vm.reply_id = reply_id
        $('#editModal').modal()
      }

      function sendDelete (id) {
        if (confirm('确认要删除吗!!')) {
          $.ajax({
                url: '/api/feedBack/destroy',
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

