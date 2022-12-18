@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Training Center</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('all.training_center') }}">Training Centers</a></li>
          <li class="breadcrumb-item active">Training Center</li>
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
              <h3 class="card-title">About Center</h3>
            </div>
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="{{ asset($training_center->main_image) }}"
                     alt="picture">
              </div>

              <h3 class="profile-username text-center">{{ $training_center->name }}</h3>

              <p class="text-muted text-center">{{ $training_center->phone }}</p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b><i class="fas fa-list-ol mr-1"></i>ID :</b> <a class="float-right">{{ $training_center->id }}</a>
                </li>
                <li class="list-group-item">
                  <b><i class="fas fa-book mr-1"></i>Education :</b> <a class="float-right">{{ $training_center->name }}</a>
                </li>
                <li class="list-group-item">
                  <b><i class="fas fa-map-marker-alt mr-1"></i>Location :</b> <a class="float-right">{{ $training_center->address }}</a>
                </li>
                <li class="list-group-item">
                  <b><i class="fas fa-comment-alt mr-1"></i>Comment :</b> <a class="float-right">{{ $training_center->comment }}</a>
                </li>
              </ul>

              <a href="{{ route('training_center.delete', $training_center->id) }}" class="btn btn-danger btn-block" id="delete"><b>Delete</b></a>
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
                <li class="nav-item"><a class="nav-link active" href="#image" data-toggle="tab">Center Images</a></li>
                <li class="nav-item"><a class="nav-link" href="#edit" data-toggle="tab">Center Edit</a></li>
                <li class="nav-item"><a class="nav-link" href="#course" data-toggle="tab">Center Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="#teacher" data-toggle="tab">Course Teachers</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane" id="course">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">Training Center Courses</h3>
                        <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-lg-course">Add Course</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                              <th>ID</th>
                              <th>Science</th>
                              <th>Name</th>
                              <th>Monthly Payment</th>
                              <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach ($courses as $item)
                              <tr>
                                <td width="1%">{{ $item->id }}</td>
                                <td>{{ $item->science->title }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->monthly_payment }}</td>
                                {{-- <td>
                                  <img
                                  src="{{ asset($item->image) }}"
                                  style="width: 90px; height: 60px;">
                                </td> --}}
                                <td width="16%">
                                  <a href="" id="{{ $item->id }}" onclick="courseEdit(this.id)" class="btn btn-info" data-toggle="modal" data-target="#modal-lg-course-edit" title="Edit Data"><i class="fas fa-edit"></i></a>
                                  <a href="{{ route('course.delete', $item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                                </td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="active tab-pane" id="image">
                  <!-- The timeline -->
                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">Training Center Images</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                              <th>ID</th>
                              <th>Image</th>
                              <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach ($center_images as $item)
                              <tr>
                                <td width="1%">{{ $item->id }}</td>
                                <td>
                                  <img
                                  src="{{ asset($item->image) }}"
                                  style="width: 90px; height: 60px;">
                                </td>
                                <td width="10%">
                                  <a href="{{ route('center_image.delete', $item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                                </td>
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
                          <h3 class="card-title">Add Image</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('center_image.store', $training_center->id) }}" enctype="multipart/form-data">
                          @csrf
                          <div class="card-body">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label for="exampleFormControlFile1">Image</label>
                                      <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1">
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                  </div>
                                </div>
                              </div>
                              <div class="">
                                <input type="submit" class="btn btn-rounded btn-primary" value="Add New">
                              </div>
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

                <div class="tab-pane" id="edit">
                  <form method="POST" action="{{ route('training_center.update', $training_center->id) }}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="old_image" value="{{ $training_center->main_image }}">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Name</label>
                      <div class="col-sm-10">
                        <input type="text" name="name" value="{{ $training_center->name }}" class="form-control" placeholder="Name">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Phone</label>
                      <div class="col-sm-10">
                        <input type="tel" name="phone" value="{{ $training_center->phone }}" class="form-control" placeholder="+998994630613">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror                      
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Regions</label>
                      <div class="col-sm-4">
                        <select name="region_id" required class="form-control select2" style="width: 100%;">
                          <option value="" selected="" disabled>Select Region</option>
                          @foreach ($regions as $item)
                          <option value="{{ $item->id }}">{{ $item->name_uz }}</option>
                          @endforeach
                        </select>                      
                      </div>
                      <label class="col-sm-2 col-form-label">Districts</label>
                      <div class="col-sm-4">
                        <select  name="district_id" required class="form-control select2" style="width: 100%;">
                          <option selected="selected" disabled>Select District</option>
                          
                        </select>
                        @error('district_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror                      
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Address</label>
                      <div class="col-sm-10">
                        <input type="text" name="address" value="{{ $training_center->address }}" class="form-control" placeholder="Address">
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Comment</label>
                      <div class="col-sm-10">
                        <textarea name="comment" class="form-control">{{ $training_center->comment }}</textarea>
                        @error('comment')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror                      
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Main Image</label>
                      <div class="col-sm-10">
                        <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1">
                        @error('image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Latitude</label>
                      <div class="col-sm-4">
                        <input type="text" name="latitude" value="{{ $training_center->latitude }}" class="form-control">
                        @error('latitude')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror                   
                      </div>
                      <label class="col-sm-2 col-form-label">Longitude</label>
                      <div class="col-sm-4">
                        <input type="text" name="longitude" value="{{ $training_center->longitude }}" class="form-control">
                        @error('longitude')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror                      
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Monthly Payment Min</label>
                      <div class="col-sm-4">
                        <input type="number" name="monthly_payment_min" value="{{ $training_center->monthly_payment_min }}" class="form-control">
                        @error('monthly_payment_min')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror                    
                      </div>
                      <label class="col-sm-2 col-form-label">Monthly Payment Max</label>
                      <div class="col-sm-4">
                        <input type="number" name="monthly_payment_max" value="{{ $training_center->monthly_payment_max }}" class="form-control">
                        @error('monthly_payment_max')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror                      
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <input type="submit" class="btn btn-primary" value="Update">
                      </div>
                    </div>
                  </form>
                </div>

                <div class="tab-pane" id="teacher">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">Course Teachers</h3>
                          <a href="{{ route('training_center.add') }}" class="btn btn-primary float-right">Add Teacher</a>
                        </div>
                        <!-- /.card-header -->
                        {{-- <div class="card card-solid"> --}}
                          <div class="card-body pb-0">
                            <div class="row">
                              @foreach ($teachers as $item)
              
                              <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                  <div class="card-header text-muted border-bottom-0">
                                  </div>
                                  <div class="card-body pt-0">
                                    <div class="row">
                                      <div class="col-7">
                                        <h2 class="lead"><b>{{ $item->name }}</b></h2>
                                        <p class="text-muted text-sm"><i class="fas fa-pen-fancy"></i> <b>About: </b> 
                                        </p>
                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                          <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> <b>Address</b> : </li>
                                          <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b>Phone</b> :</li>
                                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-money-check-alt"></i></i></span> <b>Monthly Payment</b> : <br> </li>
                                        </ul>
                                      </div>
                                      <div class="col-5 text-center">
                                        <img src="" style="width: 150px; height: 100px;" alt="image" class="profile-user-img img-fluid img-circle">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card-footer">
                                    <div class="text-right">
                                      {{-- <a href="#" class="btn btn-sm bg-teal">
                                        <i class="fas fa-comments"></i>
                                      </a> --}}
                                      {{-- <a href="{{ route('training_center.detail', $item->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-building"></i> View Center
                                      </a> --}}
                                  </div>
                                  </div>
                                </div>
                              </div>
                              @endforeach
                              
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <div class="card-footer">
                            <nav aria-label="Contacts Page Navigation">
                              <ul class="pagination justify-content-center m-0">
                                {{ $teachers->links()}}
                              </ul>
                            </nav>
                          </div>
                          <!-- /.card-footer -->
                        {{-- </div> --}}
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
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
  
  
  <div class="modal fade" id="modal-lg-course">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Course</h4>
          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <form method="POST" action="{{ route('course.store', $training_center->id) }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label>Name</label>
                  <input type="text" required name="name" class="form-control" placeholder="Name">
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Sciences</label>
                    <select name="science_id" required class="form-control select2" style="width: 100%;">
                      <option value="" selected="" disabled>Select Science</option>
                      @foreach ($sciences as $item)
                      <option value="{{ $item->id }}">{{ $item->title }}</option>
                      @endforeach
                    </select>     
                    @error('science_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror                 
                </div>
                <div class="form-group col-md-6">
                  <label>Monthly Payment</label>
                    <input type="number" required name="monthly_payment" class="form-control">
                    @error('monthly_payment')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror                     
                </div>
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Add New">
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<script type="text/javascript">
    

  function courseEdit(id){
    // alert(id)
    $.ajax({
      type: 'GET',
      url: '/training/center/course/edit/'+id,
      dataType: 'json',
      success:function(data){
        // alert(data.name)
        $('#cid').val(data.id);
        $('#cname').val(data.name);
        $('#cpay').val(data.monthly_payment);
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

  <div class="modal fade" id="modal-lg-course-edit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Course</h4>
          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <form>
              {{-- @csrf --}}
              <div class="form-group">
                <label>Name</label>
                  <input type="text" id="cname" required name="name" class="form-control" placeholder="Name">
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Sciences</label>
                    <select name="science_id" id="science_id" required class="form-control select2" style="width: 100%;">
                      <option value="" selected="" disabled>Select Science</option>
                      @foreach ($sciences as $item)
                      <option value="{{ $item->id }}">{{ $item->title }}</option>
                      @endforeach
                    </select>     
                    @error('science_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror                 
                </div>
                <div class="form-group col-md-6">
                  <label>Monthly Payment</label>
                    <input type="number" id="cpay" required name="monthly_payment" class="form-control">
                    @error('monthly_payment')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror                     
                </div>
              </div>
              <div class="form-group">
                  <input type="hidden"  id="cid">
                  <button onclick="courseUpdate()" type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  
  @endsection
  
  