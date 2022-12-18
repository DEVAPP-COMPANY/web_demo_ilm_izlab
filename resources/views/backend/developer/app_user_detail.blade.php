@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
  .checked {
    color: orange;
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>App User</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('app_user.index') }}">App Users</a></li>
          <li class="breadcrumb-item active">App User</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">About App User</h3>
            </div>
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user img-fluid img-circle"
                     src="{{ asset($user->avatar) }}"
                     alt="picture">
              </div>

              <h3 class="profile-username text-center">{{ $user->fullname }}</h3>

              <p class="text-muted text-center">{{ $user->phone }}</p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b><i class="fas fa-list-ol mr-1"></i>ID :</b> <a class="float-right">{{ $user->id }}</a>
                </li>
                <li class="list-group-item">
                  <b><i class="fas fa-user mr-1"></i>Fullname :</b> <a class="float-right">{{ $user->fullname }}</a>
                </li>
                <li class="list-group-item">
                  <b><i class="fas fa-at mr-1"></i>Email :</b> <a class="float-right">{{ $user->email }}</a>
                </li>
                <li class="list-group-item">
                  <b><i class="fab fa-buffer mr-1"></i>Status :</b> <a class="float-right"><span class="badge badge-primary p-1">{{ ucfirst($user->status) }}</span></a>
                </li>
              </ul>

                <a href="{{ route('app_user.status', ['id'=>$user->id, 'status'=>'accept']) }}" class="mr-1 btn btn-success btn-block"><b>Accept</b></a>
                {{-- <a href="{{ route('app_user.status', ['id'=>$user->id, 'status'=>'waiting']) }}" class="mr-1 btn btn-primary btn-block mt-1"><b>Waiting</b></a>
                <a href="{{ route('app_user.status', ['id'=>$user->id, 'status'=>'rejected']) }}" class="mr-1 btn btn-warning btn-block mt-1"><b>Rejected</b></a> --}}
                <a href="{{ route('app_user.status', ['id'=>$user->id, 'status'=>'blocked']) }}" class="btn btn-secondary btn-block mt-1"><b>Blocked</b></a>
              {{-- <a href="{{ route('app_user.status.delete', $user->id) }}" class="btn btn-danger btn-block mt-2" id="delete"><b>Delete</b></a> --}}
              </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#fcm" data-toggle="tab">FCM Messages</a></li>
                {{-- <li class="nav-item"><a class="nav-link" href="#course" data-toggle="tab">Center Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="#teacher" data-toggle="tab">Course Teachers</a></li>
                <li class="nav-item"><a class="nav-link" href="#rating" data-toggle="tab">Center Ratings</a></li>
                <li class="nav-item"><a class="nav-link" href="#edit" data-toggle="tab">Center Edit</a></li> --}}
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <!-- /.tab-pane -->
                <div class="active tab-pane" id="fcm">
                  <!-- The timeline -->
                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">App User FCM Messages</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                              <th>ID</th>
                              <th>Title</th>
                              <th>Body</th>
                              <th>Time Sended</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach ($messages as $item)
                              <tr>
                                <td width="1%">{{ $item->id }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->body }}</td>
                                <td>{{ $item->created_at }}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                    </div>
                    <div class="col-md-4">
                      <!-- general form elements -->
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">Send FCM Message</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('fcm_user.store', $user->id) }}" enctype="multipart/form-data">
                          @csrf
                          <div class="card-body">
                              <div class="row">
                                  <div class="form-group">
                                    <label>Title</label>
                                    <textarea name="title" class="form-control" id="textarea" cols="24" rows="2"></textarea>
                                    @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                  </div>
                                  <div class="form-group">
                                    <label>Body</label>
                                    <textarea name="body" class="form-control" id="textarea" cols="24" rows="2"></textarea>
                                    @error('body')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                  </div>
                              </div>
                              <div class="">
                                <input type="submit" class="btn btn-rounded btn-primary" value="Send">
                              </div>
                          </div>
                          <!-- /.card-body -->
              
                        </form>
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
                </div>
                <!-- /.tab-pane -->

                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <script type="text/javascript">
    $(document).ready(function () {
        $('select[name="region_id"]').on('change', function () {
            var region_id = $(this).val();
            if (region_id) {
                $.ajax({
                    url: "{{  url('/training/center/region/district/ajax') }}/" + region_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        var d = $('select[name="district_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="district_id"]').append('<option value="' + value.id + '">' + value.name_uz + '</option>');
                        });
                    },
                });
            } else {
                alert('danger');
            }
        });
    });
  </script>

<script type="text/javascript">
    $(document).ready(function () {
        $('a[name="center_id"]').on('change', function () {
            var center_id = $(this).val();
            if (center_id) {
                $.ajax({
                    url: "{{  url('/training/center/image/ajax') }}/" + center_id,
                    type: "GET",
                    dataType: "json",
                    // success: function (data) {
                    //     var d = $('select[name="district_id"]').empty();
                    //     $.each(data, function (key, value) {
                    //         $('select[name="district_id"]').append('<option value="' + value.id + '">' + value.name_uz + '</option>');
                    //     });
                    // },
                });
            } else {
                alert('danger');
            }
        });
    });
</script>
  
<script type="text/javascript">
    

  function courseEdit(id){
    // alert(id)
    $.ajax({
      type: 'POST',
      url: '/training/center/course/edit/'+id,
      dataType: 'json',
      success:function(data){
        // alert(data.name)
        $('#cid').val(data.course.id);
        $('#cname').val(data.course.name);
        $('#cpay').val(data.course.monthly_payment);
      }
    })
  }



  function courseUpdate(){
    var id = $('#cid').val();
    var name = $('#cname').val();
    var monthly_payment = $('#cpay').val();
    var science_id = $('#science_id option:selected').val();
    $.ajax({
      type: "POST",
      dataType: "json",
      data: {
        name:name, monthly_payment:monthly_payment, science_id:science_id
      },
      url: "/training/center/course/update/"+id,
      success:function(data){
        location.reload();
        toastr.info("Center Course Updated Successfully");
      }
    })
  }

</script>

<script>
  function courseTeach(id){
    var center_id = $('#centerid').val();

    // alert(center_id)
    $.ajax({
      type: "POST",
      dataType: "json",
      data: {
        center_id:center_id
      },
      url: '/training/center/course/edit/'+id,
      success:function(data){
        console.log(data.teachers);
        var rows = ""
        $.each(data.teachers, function(key, value){
          rows += `
          <div class="icheck-primary d-inline">
            <input type="checkbox" name="checked" ${ value.checked != null ? 'checked' : ''} onclick="checkbox(${value.id})" id="${value.name}">
            <label for="${value.name}">
              ${value.name}
            </label>
          </div>
          `
        });
        $('#teach').html(rows);

        // alert(data.name)
        $('#courseid').val(data.course.id);
        $('#coursename').text(data.course.name);
        // $('#checked').id(data.id);
      }
    })
  }


  function checkbox(teacher_id){
    var course_id = $('#courseid').val();
    $.ajax({
      type: "POST",
      dataType: "json",
      data: {
        teacher_id:teacher_id, course_id:course_id
      },
      url: "/training/center/course/teacher/connect",
      success:function(data){
        // console.log(data)
        // location.reload();
        toastr.success(data.success);
      }
    })
  }
</script>

  @endsection
  
  