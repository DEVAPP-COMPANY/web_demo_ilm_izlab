@extends('admin.admin_master')

@section('admin')
<style>
  .checked {
    color: orange;
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Waiting Ratings</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Waiting Ratings</li>
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
            <h3 class="card-title">Waiting Ratings</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($ratings as $item)
                  <tr>
                    <td width="1%">{{ $item->id }}</td>
                    <td>{{ $item->user->fullname }}</td>
                    <td width="14%">
                      @php
                          for ($i = 1; $i <= 5; $i++) {
                              if ($item->rating < $i) {
                                echo "<span class='fa fa-star'></span>";
                              }else {
                                echo "<span class='fa fa-star checked'></span>";
                              }
                          }
                      @endphp
                    </td>
                    <td>{{ $item->comment }}</td>
                    <td><span class="badge badge-primary p-1">{{ ucfirst($item->status) }}</span></td>
                    <td width="12%">
                      <a href="{{ route('rating.accepted', $item->id) }}" class="btn btn-success" title="Accept Data"><i class="fas fa-check-circle"></i></a>
                      <a href="{{ route('rating.rejected', $item->id) }}" class="btn btn-secondary" title="Reject Data"><i class="fas fa-times-circle"></i></a>
                      {{-- <a href="{{ route('training_center.delete', $item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a> --}}
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

