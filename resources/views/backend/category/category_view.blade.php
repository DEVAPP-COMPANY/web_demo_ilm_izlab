@extends('admin.admin_master')

@section('admin')

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Categories</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Categories</li>
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
            <h3 class="card-title">Categories</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Icon</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($categories as $item)
                  <tr>
                    <td width="1%">{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td width="20%">
                      {{-- <a href="{{ route('category.edit', $item->id) }}"> --}}
                        <img
                        src="{{ asset($item->icon) }}"
                        style="width: 40px; height: 40px;">
                    {{-- </a> --}}
                  </td>
                    <td width="25%">
                      <a href="{{ route('all.science', $item->id) }}" class="btn btn-primary" title="Plus Data"><i class="fas fa-eye"></i></a>
                      <a href="{{ route('category.edit', $item->id) }}" class="btn btn-info" title="Edit Data"><i class="fas fa-edit"></i></a>
                      <a href="{{ route('category.delete', $item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
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
            <h3 class="card-title">Add Category</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" class="form-control" placeholder="Title..">
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

