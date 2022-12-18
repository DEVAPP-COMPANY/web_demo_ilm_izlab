<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>
    @if ($center == null)
      Center Name
    @else
    {{ $center->name }}
    @endif

</title>

  <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
    integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
    crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
    integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
    crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
    <style>
      .box {
          position: relative;
          text-align: center;
          margin-bottom: 20px;
      }
      .box_img {
          border-radius: 20px;
          width: 100%;
          height: 300px;
      }
      .box_text-1 {
          position: absolute;
          bottom: 18px;
          left: 20px;
          color: white;
          font-size: 20px;
          font-weight: bold;
      }
      .box_text-2 {
          position: absolute;
          right: 20px;
          top: 18px;
          color: white;
          font-weight: bold;
      }

      .box_text-3 {
          position: absolute;
          bottom: -1px;
          left: 20px;
          color: white;
          font-size: 20px;
          font-weight: bold;
      }


      .reyting_star .fa-star {
          color: gold;
      }
      .head_img {
          padding: 10px 20px 0 420px;
      }
      .head_img img {
          border-radius: 50%;
          width: 80px;
      }
      .head_text {
          padding-top: 18px;
      }

      @media only screen and (max-width: 820px){
        
        .head_img {
              padding-left: 200px;
        }
      
      }

      
      @media only screen and (min-width: 769px){
        

        .box_img {
          width: 800px;
          height: 400px;
        }

        .box_text-1{
          left: 180px;
          padding-right: 120px;
        }

        .box_text-2{
          right: 180px;
        }

        .box_text-3{
          left: 180px;
        }
      }

      @media only screen and (max-width: 768px){
        
        
        .box_img {
          width: 600px;
        }

        .box_text-1{
          left: 70px;
          padding-right: 60px;
        }

        .box_text-2{
          right: 70px;
        }
        
      }

      @media only screen and (max-width: 461px) {
          .head_img {
              padding-left: 110px;
          }
          .head_text{
            margin-left: 90px;
          }
      }

      @media only screen and (max-width: 360px){
        .box_img {
          width: 330px;
          height: 200px;
        }

        .box_text-1{
          left: 25px;
          padding-right: 20px;
        }

        .box_text-2{
          right: 25px;
        }
      }
      
      
  </style>
  
  </head>
  
<body>
@php
use Carbon\Carbon;
@endphp
  @if ($center == null)
    <div class="box_one container">
      <h1 class="text-bold text-danger text-center pt-5">Bunday O'quv Markaz Topilmadi!</h1>
      
    </div>
  @elseif($center->status == "waiting")
    <div class="box_one container">
      <h1 class="text-bold text-danger text-center pt-5">Ushbu Markaz Aktiv Emas!</h1>
      
    </div>
  @else
  <div>
    <div class="m-4">
      <div class="owl-carousel owl-theme">
        @foreach ($center->images as $image)
        <div class="item">
          <img src="{{ asset($image->image) }}" style="height: 240px;" alt="Image">
        </div>
        @endforeach
      </div>
    </div>
    <div class="box_one container">
      <h4 class="text-bold text-center pb-4">{{ $center->name }}</h4>
      <div class="box-body mb-1">
        <div class="content">
          <div class="col-md-12">
            <div class="row justify-content-md-left">
              <div class="col-md-6">
                <p><i class="fas fa-university text-secondary"></i> Markaz nomi : <b>{{ $center->name }}</b></p>
                <p><i class="fas fa-star text-secondary"></i> Qo'yilgan baholar : <b>{{ $center->rating_count }} ta - {{ $center->rating }}/o'rtacha </b></p>
                <p><i class="fas fa-user-check text-secondary"></i> Obunachilar soni : <b>{{ count($subscribers) }}</b></p>
              </div>
              <div class="col-md-6">
                <p><i class="fas fa-money-check-alt text-secondary"></i> O'rtacha oylik to'lov : <b>{{ $center->monthly_payment_min }} dan {{ $center->monthly_payment_max }} gacha </b></p>
                <p><i class="fas fa-map-marker-alt text-secondary"></i> Manzil : <b>{{ $center->address }}</b></p>
                <p><i class="fas fa-mobile-alt text-secondary"></i> Telefon raqami : <b><a href="tel:{{ $center->phone }}">{{ $center->phone }}</a></b></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <nav class="card border-bottom-0">
        <div class="nav nav-tabs d-flex justify-content-around pt-2" id="nav-tab" role="tablist">
        <button class="nav-link active fw-bold" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Kurslar</button>
        <button class="nav-link fw-bold" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Yangiliklar</button>
        <button class="nav-link fw-bold" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Baholar</button>
        </div>
    </nav>
    </div>
    <br>
    <div class="tab-content container" id="nav-tabContent">
      <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        @foreach ($center->courses as $item)
          <div class="card" style="width: 100%; box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">
              <div class="card-body d-flex justify-content-between">
                <div class="card_left">
                  <h5 class="card-title">{{ $item->name }}</h5>
                  <p class="card-text">{{ $item->monthly_payment }}/oylik</p>
                  <p class="text-muted">{{ $item->science->title }}</p>
                </div>
                {{-- <div class="card_right align-self-center">
                  <a href="tel:{{ $center->phone }}" class="btn btn-primary">Bog'lanish</a>
                </div> --}}
              </div>
          </div>
          <br>
        @endforeach
      </div>
      {{-- <br> --}}
      <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        @foreach ($news as $item)

        <div class="box">
          <a href="{{ route('news_detail', $item->id) }}" target="_blank">
            <img class="box_img" src="{{ asset($item->image) }}" alt="image">
          </a>
          <div class="box_text-1">{{ $item->title }}</div>
          <div class="box_text-3 fs-6">{{ Carbon::parse($item->created_at)->format('H:m d/m/Y') }}</div>
          <div class="box_text-2">{{ $center->name }}</div>
      </div>
        @endforeach
          
      </div>
      <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
        @foreach ($ratings as $item)
          <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">
              <div class="card-header d-flex flex-wrap">
                  <div class="head_img">
                      <img src="{{ asset($item->user_avatar) }}" alt="Image"  style="width: 80px; height: 80px;">
                  </div>
                  <div class="head_text">
                      <h3>{{ $item->user_fullname }}</h3>
                      <p>{{ $item->date }}</p>
                  </div>
              </div>
              <div class="card-body text-center">
                  <div class="reyting">{{ $item->rating }}</div>
                  <div class="reyting_star">
                    @for ($i = 1; $i <= 5; $i++)
                      @if ($item->rating >= $i)
                        <i class="fas fa-star"></i>
                      @else
                        <i class="far fa-star"></i>
                      @endif
                    @endfor
                  </div>
                  <p>{{ $item->comment }}</p>
              </div>
          </div>
          <br>
        @endforeach
      </div>
  </div>
  </div>
  @endif
  <!--Jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<!-- Owl Carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- custom JS code after importing jquery and owl -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $(".owl-carousel").owlCarousel();
    });

    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 3
            }
        }
    })
</script>
</body>
</html>