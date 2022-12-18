@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Developers</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Developers</li>
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
            <h3 class="card-title">Developers</h3>
          </div>
            <div class="card-body pb-0">
              <div name="center" class="row">
                @foreach ($developers as $item)

                <div class="col-12 col-sm-6 d-flex align-items-stretch flex-column">
                  <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0">
                    </div>
                    <div class="card-body pt-0">
                      <div class="">
                        <div class="text-center">
                          <h2 class="lead"><b>{{ $item->fullname }}</b></h2>
                          <ul class="ml-4 mb-0 fa-ul text-muted text-left">
                            <li class="small pb-1"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b>Phone</b> : {{ $item->phone }}</li>
                            <li class="small"><span class="fa-li"><i class="fab fa-lg fa-buffer"></i></span> <b>Status</b> : <span class="badge badge-primary p-1">{{ ucfirst($item->status) }}</span></li>
                            <li class="small"><span class="fa-li"><i class="fas fa-server"></i></span> <b>Request Count</b> : <span class="badge badge-primary p-1">{{ $item->req_count }}</span></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="text-right">
                        <a href="" id="{{ $item->id }}" onclick="viewDev(this.id)" class="btn btn-info" data-toggle="modal" data-target="#modal-lg-dev-view" title="View Data">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('developer.accept', $item->id) }}"  class="btn btn-success" title="Accept Data">
                          <i class="fas fa-check"></i>
                        </a>
                        <a href="{{ route('developer.block', $item->id) }}" class="btn btn-danger" title="Block Data">
                          <i class="fas fa-ban"></i>
                        </a>
                    </div>
                    </div>
                  </div>
                </div>
                @endforeach
                
              </div>
            </div>
        </div>
        <!-- /.card -->
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-body">
          <form method="POST" action="{{ route('developers.store') }}">
            @csrf
            <div class="form-group">
              <label>Fullname</label>
                <input type="text" name="fullname" class="form-control">
                @error('fullname')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
              <label>Phone</label>
                <input type="text" name="phone" class="form-control">
                @error('phone')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Add New">
            </div>
          </form>
        </div>
      </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->



<script type="text/javascript">
  function viewDev(id){
    // alert(id)
    $.ajax({
      type: 'GET',
      url: '/dev/view/'+id,
      dataType: 'json',
      success:function(data){
        // alert(data.developer.fullname)
        $('#did').val(data.developer.id);
        $('#dfullname').text(data.developer.fullname);
        $('#dphone').text(data.developer.phone);
        $('#dstatus').text(data.developer.status);
        $('#display').val(data.developer.api_key);
        $('#dreqcount').text(data.developer.req_count);
      }
    })
  }
</script>


<div class="modal fade" id="modal-lg-dev-view">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Developer</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <h5 class="text-bold">Fullname </h5>
                <p id="dfullname"></p>
              </div>
              <div class="col-md-6">
                <h5 class="text-bold">Phone </h5>
                <p id="dphone"></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h5 class="text-bold">Status </h5>
                <p style="text-transform:uppercase;" class="text-primary" id="dstatus"></p>
              </div>
              <div class="col-md-6">
                <h5 class="text-bold">Api Key </h5>
                <div class="controls">
                  <div class="input-group">
                      <input type="text" id="display" class="form-control"> 
                      <span class="input-group-append">
                        <a class="btn btn-info btn-sm" id="copy" onclick="copyPassword()"><i class="fas fa-clone pt-2"></i></a>
                      </span> 
                  </div>
                  <div class="help-block"></div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h5 class="text-bold">Request Count </h5>
                <p class="text-danger" id="dreqcount"></p>
              </div>
            </div>
          </div>
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

