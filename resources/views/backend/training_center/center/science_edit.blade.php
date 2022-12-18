@extends('admin.admin_master')

@section('admin')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Science</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('all.category') }}">Categories</a></li>
          <li class="breadcrumb-item"><a href="{{ route('all.science', $science->category_id) }}">Science</a></li>
          <li class="breadcrumb-item active">Edit Science</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-8">
        <!-- general form elements -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Edit Science</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('science.update', $science->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="old_icon" value="{{ $science->icon }}">
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" value="{{ $science->title }}" class="form-control" placeholder="Title..">
                      @error('title')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Icon</label>
                        <input type="file" name="icon" class="form-control-file" id="exampleFormControlFile1">
                      @error('icon')
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

@endsection

