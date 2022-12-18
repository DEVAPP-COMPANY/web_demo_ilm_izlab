@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Add Teacher</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('all.training_center') }}">Training Centers</a></li>
          <li class="breadcrumb-item"><a href="{{ route('training_center.detail', $center->id) }}">This Teacher Training Center</a></li>
          <li class="breadcrumb-item active">Add Teacher</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Add Teacher</h3>
            {{-- <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-lg">Get Images</a> --}}
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('teacher.store', $center->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" name="name" class="form-control" placeholder="Name">
                      @error('name')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label>Profile Link</label>
                      <input type="text" name="info_link" value="https://" class="form-control" placeholder="https://t.me/abdullajon_soliyev">
                      @error('info_link')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label>Specialization</label>
                      <input type="text" name="specialization" class="form-control" placeholder="Mobile Developer">
                      @error('specialization')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Experience</label>
                      <input type="text" name="experience" class="form-control" placeholder="4 year">
                      @error('experience')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlFile1">Avatar</label>
                      <input type="file" name="avatar" class="form-control-file" id="exampleFormControlFile1">
                      @error('avatar')
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
      <!--/.col (left) -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>

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

@endsection

