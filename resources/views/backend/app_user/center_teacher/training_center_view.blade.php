@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Training Centers</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Training Centers</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
@php
    
use App\Models\Backend\Course;
use App\Models\Backend\Science;
@endphp
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Training Centers</h3>
            <a href="{{ route('training_center.add') }}" class="btn btn-primary float-right">Add Training Center</a>
          </div>
          <!-- /.card-header -->
          {{-- <div class="card card-solid"> --}}
            <div class="card-body pb-0">
              <div class="row">
                @foreach ($centers as $item)
<?php

    $courses = Course::where('center_id', $item->id)->get();

?>
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                  <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0">
                      @foreach ($courses as $course)
                          @php
                              $sciences = Science::where('id', $course->id)->get();
                              // dd($sciences);
                          @endphp
                          @foreach ($sciences as $science)
                              {{ $science->title}},
                          @endforeach
                      @endforeach
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col-7">
                          <h2 class="lead"><b>{{ $item->name }}</b></h2>
                          <p class="text-muted text-sm"><i class="fas fa-pen-fancy"></i> <b>About: </b> 
                            @if ($item->comment == null)
                                <span class="text-danger">No Comment</span>
                            @endif
                              <span class="">{{ $item->comment }}</span>
                          </p>
                          <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> <b>Address</b> : {{ $item->address }}</li>
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b>Phone</b> : {{ $item->phone }}</li>
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-money-check-alt"></i></i></span> <b>Monthly Payment</b> : <br> {{ $item->monthly_payment_min }} / {{ $item->monthly_payment_max }}</li>
                          </ul>
                        </div>
                        <div class="col-5 text-center">
                          <img src="{{ asset($item->main_image) }}" style="width: 150px; height: 100px;" alt="image" class="profile-user-img img-fluid img-circle">
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="text-right">
                        {{-- <a href="#" class="btn btn-sm bg-teal">
                          <i class="fas fa-comments"></i>
                        </a> --}}
                        <a href="{{ route('training_center.detail', $item->id) }}" class="btn btn-sm btn-primary">
                          <i class="fas fa-building"></i> View Center
                        </a>
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
                  {{ $centers->links()}}
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

@endsection

