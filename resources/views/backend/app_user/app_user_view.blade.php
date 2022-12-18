@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>App Users</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">App Users</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
@php
    
use App\Models\Backend\Course;
use App\Models\Backend\Science;
use App\Models\Backend\Region;

$regions = Region::all();
@endphp
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">App Users</h3>
            {{-- <a href="{{ route('training_center.add') }}" class="btn btn-primary float-right">Add Training Center</a> --}}
          </div>
          {{-- <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h6>Select Region</h6>
                <select name="region_id" class="custom-select">
                  <option selected disabled>All</option>
                  @foreach ($regions as $item)
                  <option value="{{ $item->id }}">{{ $item->name_uz }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <h6>Select Status</h6>
                <select name="status" class="form-control">
                  <option value="" selected="" disabled="">All</option>
                  <option value="accept">Accept</option>
                  <option value="reject">Reject</option>
                  <option value="waiting">Waiting</option>
              </select>
              </div>
            </div>
          </div> --}}
          <!-- /.card-header -->
          {{-- <div class="card card-solid"> --}}
            <div class="card-body pb-0">
              <div name="center" class="row">
                @foreach ($users as $item)
<?php

    // $courses = Course::where('center_id', $item->id)->get();

?>
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                  <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0">
                      {{-- @foreach ($courses as $course)
                          @php
                              $sciences = Science::where('id', $course->id)->get();
                              // dd($sciences);
                          @endphp
                          @foreach ($sciences as $science)
                              {{ $science->title}},
                          @endforeach
                      @endforeach --}}
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col-7">
                          <h2 class="lead"><b>{{ $item->fullname }}</b></h2>
                          <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b>Phone</b> : {{ $item->phone }}</li>
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> <b>Email</b> : {{ $item->email }}</li>
                            <li class="small"><span class="fa-li"><i class="fab fa-lg fa-buffer"></i></i></i></span> <b>Status</b> : <span class="badge badge-primary p-1">{{ ucfirst($item->status) }}</span></li>
                          </ul>
                        </div>
                        <div class="col-5 text-center">
                          <img src="{{ asset($item->avatar) }}" style="width: 100px; height: 100px;" alt="image" class="profile-user-img img-fluid img-circle">
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="text-right">
                        <a href="{{ route('app_user.show', $item->id) }}" class="btn btn-sm btn-primary">
                          <i class="fas fa-user"></i> View User
                        </a>
                    </div>
                    </div>
                  </div>
                </div>
                @endforeach
                
              </div>
            </div>
            {{-- @if ($centers->links())
                <!-- /.card-body -->
            <div class="card-footer">
              <nav aria-label="Contacts Page Navigation">
                <ul class="pagination justify-content-center m-0">
                  {{ $centers->links()}}
                </ul>
              </nav>
            </div>
            <!-- /.card-footer -->
            @endif --}}
            
          {{-- </div> --}}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->



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


<div class="modal fade" id="modal-lg-image">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Images</h4>
        <form method="POST" id="center_id" action="{{ route('image.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="col-md-12">
              <div class="row">
                  <div class="col-md-8">
                      <div class="controls">
                          <input type="file" name="image" class="mt-1">

                        </div>
                      @error('image')
                          <span class="text-danger">{{ $message }}</span>
                          @enderror
                  </div>
                  <div class="col-md-2">
                      <div class="text-xs-right">
                          <input type="submit" class="btn btn-rounded btn-primary pull-right" value="Add New">
                      </div>
                  </div>
              </div>
          </div>
          
      </form>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <table id="example2" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Image</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
              {{-- @foreach ($images as $item)
                <tr>
                  <td width="15%">
                    <img src="{{ asset($item->url) }}" style="width: 90px; height: 60px;">
                  </td>
                  <td width="12%">
                    <a href="{{ route('image.delete', $item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                  </td>
                </tr>
              @endforeach --}}
            </tbody>
          </table>
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
  $(document).ready(function() {
      $('select[name="status"]').on('change', function(){
          var status = $(this).val();
          // alert(status)
          if(status) {
              $.ajax({
                  url: "{{  url('/filter/center/ajax') }}",
                  type:"GET",
                  dataType:"json",
                  data: {
                      status: status,
                      region_id: $('select[name="region_id"]').val()
                  },
                  success:function(data) {
                      var center = $('div[name="center"]').empty();
                      var i = 1;
                      // console.log(data);
                      $.each(data, function(key, value){
                        var url = '{{ route("training_center.detail", ":id") }}';
                        url = url.replace(':id', value.id);
                          $('div[name="center"]').append(`
                  <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                  <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0">
                    

                    
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col-7">
                          <h2 class="lead"><b>`+ value.name +`</b></h2>
                          <p class="text-muted text-sm"><i class="fas fa-pen-fancy"></i> <b>About: </b> 
                            @if ($item->comment == null)
                                <span class="text-danger">No Comment</span>
                            @endif
                              <span class="">`+value.comment.substring(0, 60)+`...</span>
                          </p>
                          <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> <b>Address</b> : `+ value.address +`</li>
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b>Phone</b> : `+ value.phone +`</li>
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-money-check-alt"></i></i></span> <b>Monthly Payment</b> : <br> `+value.monthly_payment_min+` / `+value.monthly_payment_max+`</li>
                            <li class="small"><span class="fa-li"><i class="fab fa-lg fa-buffer"></i></i></i></span> <b>Status</b> : <span class="badge badge-primary p-1">`+value.status.charAt(0).toUpperCase() + value.status.slice(1)+`</span></li>
                          </ul>
                        </div>
                        <div class="col-5 text-center">
                          <img src="../../public/`+value.main_image+`" style="width: 160px; height: 80px;" 
                          alt="image" class="profile-user img-fluid img-rectangle">
                        </div>
                      </div>
                    </div>

                    <div class="card-footer">
                      <div class="text-right">
                        <a href="`+url+`" class="btn btn-sm btn-primary">
                          <i class="fas fa-building"></i> View Center
                        </a>
                    </div>
                    </div>

                    </div>
                  </div>
                </div>
                          `);
                      });
                  },
              });
          } else {
              alert('danger');
          }
      });

      $('select[name="region_id"]').on('change', function(){
          var region_id = $(this).val();
          // alert(region_id)
          if(region_id) {
              $.ajax({
                  url: "{{  url('/filter/center/ajax') }}",
                  type:"GET",
                  dataType:"json",
                  data: {
                      status: $('select[name="status"]').val(),
                      region_id: region_id
                  },
                  success:function(data) {
                      var center = $('div[name="center"]').empty();
                      var i = 1;
                      // console.log(data);
                      $.each(data, function(key, value){
                        var url = '{{ route("training_center.detail", ":id") }}';
                        url = url.replace(':id', value.id);
                          $('div[name="center"]').append(`
                  <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                  <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0">
                    

                    
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col-7">
                          <h2 class="lead"><b>`+ value.name +`</b></h2>
                          <p class="text-muted text-sm"><i class="fas fa-pen-fancy"></i> <b>About: </b> 
                            @if ($item->comment == null)
                                <span class="text-danger">No Comment</span>
                            @endif
                              <span class="">`+value.comment.substring(0, 60)+`...</span>
                          </p>
                          <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> <b>Address</b> : `+ value.address +`</li>
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b>Phone</b> : `+ value.phone +`</li>
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-money-check-alt"></i></i></span> <b>Monthly Payment</b> : <br> `+value.monthly_payment_min+` / `+value.monthly_payment_max+`</li>
                            <li class="small"><span class="fa-li"><i class="fab fa-lg fa-buffer"></i></i></i></span> <b>Status</b> : <span class="badge badge-primary p-1">`+value.status.charAt(0).toUpperCase() + value.status.slice(1)+`</span></li>
                          </ul>
                        </div>
                        <div class="col-5 text-center">
                          <img src="../../public/`+value.main_image+`" style="width: 160px; height: 80px;" 
                          alt="image" class="profile-user img-fluid img-rectangle">
                        </div>
                      </div>
                    </div>

                    <div class="card-footer">
                      <div class="text-right">
                        <a href="`+url+`" class="btn btn-sm btn-primary">
                          <i class="fas fa-building"></i> View Center
                        </a>
                    </div>
                    </div>

                    </div>
                  </div>
                </div>
                          `);
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

