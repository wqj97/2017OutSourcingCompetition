@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                @include('dashboard.user._sidenav')
            </div>

            <div class="col-sm-10">
                <div id="section-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/dashboard') }}">ArcInPic</a></li>
                        <li><a href="{{ url('/dashboard/user') }}">{{ trans('dashboard.User') }}</a></li>
                        <li class="active">{{ trans('dashboard.List') }}</li>
                    </ol>
                </div>

                <div id="section-mainbody">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ trans('dashboard.Nickname') }}</th>
                            <th>{{ trans('dashboard.Avatar') }}</th>
                            <th>{{ trans('dashboard.Bio') }}</th>
                            <th>{{ trans('dashboard.Gender') }}</th>
                            <th>{{ trans('dashboard.Timeline Num') }}</th>
                            <th>{{ trans('dashboard.Like Num') }}</th>
                            <th>{{ trans('dashboard.Comment Num') }}</th>
                            <th>{{ trans('dashboard.Created At') }}</th>
                            <th>{{ trans('dashboard.Operations') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->nickname }}</td>
                                <td>
                                    <img style="height:20px" src="{{ \App\User::getAvatarUrl($user->avatar) }}">
                                </td>
                                <td>{{ $user->bio }}</td>
                                <td>{{ \App\User::getGenderName($user->gender) }}</td>
                                <td>{{ $user->timelines->count() }}</td>
                                <td>{{ $user->timelineLikes->count() }}</td>
                                <td>{{ $user->timelineComments->count() }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            操作<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onclick="editModel({{$user->id}})">修改</a></li>
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
                        {!! $users->render() !!}
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
                        <label for="recipient-name" class="control-label">昵称:</label>
                        <input type="text" class="form-control" id="recipient-name" v-model="nickname">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">手机号:</label>
                        <input type="text" class="form-control" id="recipient-name" v-model="phone">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">性别:</label>
                        <select type="number" class="form-control" id="recipient-name" v-model="gender">
                            <option value="0">保密</option>
                            <option value="1">男</option>
                            <option value="2">女</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">签名:</label>
                        <input type="text" class="form-control" id="recipient-name" v-model="bio">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">管理员:</label>
                        <input type="checkbox" class="form-control" id="recipient-name" v-model="is_admin">
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
            nickname: '',
            phone: 0,
            is_admin: 0,
            gender: 0,
            bio: ''
          }
        },
        methods: {
          sendChange () {
            console.log(this.data)
            $.ajax({
              url: './user/update',
              type: 'post',
              data: {
                id: this.id,
                nickname: this.nickname,
                phone: this.phone,
                is_admin: this.is_admin,
                gender: this.gender,
                bio: this.bio
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
          url: './user/by-id',
          data: {
            id: id
          },
          success (data) {
            data = data[0]
            vm.id = id
            vm.nickname = data.nickname
            vm.phone = data.phone
            vm.is_admin = data.is_admin
            vm.gender = data.gender
            vm.bio = data.bio
            $('#editModal').modal()
          }
        })
      }
    </script>
@endsection

