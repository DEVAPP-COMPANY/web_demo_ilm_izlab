@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Training Center</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('all.training_center') }}">Training Centers</a></li>
          <li class="breadcrumb-item active">Edit Training Center</li>
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
            <h3 class="card-title">Edit Training Center</h3>
            {{-- <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-lg">Get Images</a> --}}
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('training_center.update', $training_center->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="old_image" value="{{ $training_center->main_image }}">
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" name="name" value="{{ $training_center->name }}" class="form-control" placeholder="Name">
                      @error('name')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label>Phone</label>
                      <input type="tel" name="phone" value="{{ $training_center->phone }}" class="form-control" placeholder="+998994630613">
                      @error('phone')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label>Regions</label>
                      <select name="region_id" class="form-control select2" style="width: 100%;">
                        <option value="" selected="" disabled>Select Region</option>
                        @foreach ($regions as $item)
                        <option value="{{ $item->id }}">{{ $item->name_uz }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Districts</label>
                      <select  name="district_id" class="form-control select2" style="width: 100%;">
                        <option selected="selected" disabled>Select District</option>
                        
                      </select>
                      @error('district_id')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label>Address</label>
                      <input type="text" name="address" value="{{ $training_center->address }}" class="form-control" placeholder="Address">
                      @error('address')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label>Comment</label>
                      <textarea name="comment" class="form-control">{{ $training_center->comment }}</textarea>
                      @error('comment')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleFormControlFile1">Main Image</label>
                      <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1">
                      @error('image')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label>Latitude</label>
                      <input type="text" name="latitude" value="{{ $training_center->latitude }}" class="form-control">
                      @error('latitude')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror                 
                    </div>
                    <div class="form-group">
                      <label>Longitude</label>
                      <input type="text" name="longitude" value="{{ $training_center->longitude }}" class="form-control">
                      @error('longitude')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror                 
                    </div>
                    <div class="form-group">
                      <label>Monthly Payment Min</label>
                      <input type="number" name="monthly_payment_min" value="{{ $training_center->monthly_payment_min }}" class="form-control">
                      @error('monthly_payment_min')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror                 
                    </div>
                    <div class="form-group">
                      <label>Monthly Payment Max</label>
                      <input type="number" name="monthly_payment_max" value="{{ $training_center->monthly_payment_max }}" class="form-control">
                      @error('monthly_payment_max')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror                 
                    </div>
                    <div class="form-group">
                      <label>Parol</label>
                      <input type="text" name="parol" value="{{ $training_center->parol }}" class="form-control">
                      @error('parol')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror                 
                    </div>
                  </div>
                </div>
                <div class="">
                  <input type="submit" class="btn btn-rounded btn-primary" value="Update">
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

