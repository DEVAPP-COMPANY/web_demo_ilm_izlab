@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Training Center Images</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('all.training_center') }}">Training Centers</a></li>
          <li class="breadcrumb-item active">Training Center Images</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Training Center Images</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
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
      <!-- /.col -->
      <div class="col-md-4">
        <!-- general form elements -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Add Image</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('center_image.store', $center->id) }}" enctype="multipart/form-data">
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
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

