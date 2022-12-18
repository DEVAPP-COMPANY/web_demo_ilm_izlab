@extends('admin.admin_master')

@section('admin')

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Offers</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Offers</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Offers</h3>
            <a href="{{ route('offer.add') }}" class="btn btn-primary float-right">Add Offer</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Content</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($offers as $item)
                  <tr>
                    <td width="1%">{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td width="10%">
                      <img
                      src="{{ asset($item->image) }}"
                      style="width: 90px; height: 60px;">
                    </td>
                    <td width=50%>{!! Str::limit(strip_tags($item->content), 100) !!}</td>
                    <td width="12%">
                      <a href="{{ route('offer.edit', $item->id) }}" class="btn btn-info" title="Edit Data"><i class="fas fa-edit"></i></a>
                      <a href="{{ route('offer.delete', $item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
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
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

