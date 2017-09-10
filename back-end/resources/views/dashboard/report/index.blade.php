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
                            <th>举报理由</th>
                            <th>举报时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($feedbacks as $report)
                            <tr>
                                <td>{{ $report->F_related_Id }}</td>
                                <td>{{ $report->author }}</td>
                                <td>{{ $report->content }}</td>
                                <td>{{ $report->F_content }}</td>
                                <td>{{ $report->F_date }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            操作<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onclick="replyUser({{$report->F_user_Id}},{{$report->F_related_Id}},{{$report->id}})">答复举报用户</a>
                                            </li>
                                            <li><a href="#" onclick="banTimeline({{$report->F_related_Id}})">封禁该条</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li class="text-danger"><a href="#" onclick="sendDelete({{$report->id}})">删除</a></li>
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

      function banTimeline (id) {
        if (confirm('确定要封禁吗, 操作不可逆转')) {
          $.ajax({
            url:'/api/feedBack/ban',
            type:'post',
            data:{
              id:id
            },
            success(){
              window.location.reload()
            }
          })
        }

      }
    </script>
@endsection

