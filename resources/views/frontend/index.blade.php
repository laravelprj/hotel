@extends('frontend.main_master')
@section('frontend')
<div class="banner-area" style="height: 480px;">
    <div class="container">
        <div class="banner-content">
            <h1>Discover a Hotel & Resort to Book a Suitable Room</h1>
            
             
        </div>
    </div>
</div>
<!-- Banner Area End -->

<!-- Banner Form Area -->
<div class="banner-form-area">
    <div class="container">
        <div class="banner-form">
            <form method="post" action="{{ route('booking.search') }}">
                @csrf
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label>CHECK IN TIME</label>
                            <div class="input-group">
                                <input autocomplete="off"  type="text" required name="check_in" class="form-control dt_picker" placeholder="yyy-mm-dd">
                                <span class="input-group-addon"></span>
                            </div>
                            <i class='bx bxs-chevron-down'></i>	
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label>CHECK OUT TIME</label>
                            <div class="input-group">
                                <input autocomplete="off"  type="text" required name="check_out" class="form-control dt_picker" placeholder="yyy-mm-dd">
                                <span class="input-group-addon"></span>
                            </div>
                            <i class='bx bxs-chevron-down'></i>	
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label>GUESTS</label>
                            <select name="persion" class="form-control">
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <button type="submit" class="default-btn btn-bg-one border-radius-5">
                            Check Availability 
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Banner Form Area End -->

<!-- Room Area -->
@include('frontend.home.room_area')
<!-- Room Area End -->

<!-- Book Area Two-->
@include('frontend.home.Book_area_two')

<!-- Book Area Two End -->

<!-- Services Area Three -->
@include('frontend.home.service_area_three')

<!-- Services Area Three End -->

<!-- Team Area Three -->
@include('frontend.home.team_area_three')

<!-- Team Area Three End -->

<!-- Testimonials Area Three -->
@include('frontend.home.testimonilas')

<!-- Testimonials Area Three End -->

<!-- FAQ Area -->
@include('frontend.home.faq')

<!-- FAQ Area End -->

<!-- Blog Area -->
@include('frontend.home.blog')

<!-- Blog Area End -->
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('message') == 'success'){
        alert('Đặt phòng thành công')
    }
</script>
@endsection