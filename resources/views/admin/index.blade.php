@extends('admin.admin_master')

@section('admin')

@php
    use App\Models\Backend\AppUser;
    use App\Models\Backend\TrainingCenter;
    use App\Models\Backend\Review;
    use App\Models\Backend\News;

    $users = AppUser::get();
    $user_count = $users->count();

    $centers = TrainingCenter::where('status', 'waiting')->get();
    $center_count = $centers->count();

    $ratings = Review::where('status', 'waiting')->get();
    $rating_count = $ratings->count();

    $news = News::where('status', 'waiting')->get();
    $news_count = $news->count();
    

@endphp

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $center_count }}</h3>

            <p>Waiting Centers</p>
          </div>
          <div class="icon">
            <i class="fa fa-building"></i>
          </div>
          <a href="{{ route('all.training_center_waiting') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $rating_count }}</h3>

            <p>Waiting Ratings</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="{{ route('all.rating_waiting') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $user_count }}</h3>

            <p>App Users</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="{{ route('app_user.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-primary">
          <div class="inner">
            <h3>{{ $news_count }}</h3>

            <p>Waiting News</p>
          </div>
          <div class="icon">
            <i class="ion ion-image"></i>
          </div>
          <a href="{{ route('all.news_waiting') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

  @endsection

