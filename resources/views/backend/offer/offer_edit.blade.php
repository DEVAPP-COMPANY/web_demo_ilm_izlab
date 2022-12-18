@extends('admin.admin_master')

@section('admin')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Offer</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('all.offer') }}">Offer</a></li>
          <li class="breadcrumb-item active">Edit Offer</li>
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
            <h3 class="card-title">Edit Offer</h3>
            <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-lg">Get Images</a>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('offer.update', $offer->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="old_image" value="{{ $offer->image }}">
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" value="{{ $offer->title }}" class="form-control" placeholder="Title..">
                      @error('title')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Image</label>
                        <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1">
                      @error('image')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Content</label>
                      <textarea name="content" id="summernote">{{ $offer->content }}</textarea>
                      @error('content')
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

