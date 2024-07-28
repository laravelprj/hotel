@php
    $book= App\Models\BookArea::find(1);
@endphp
<div class="book-area-two pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">BOOKING NOW </span>
            <h2>Book Quickly Deep Discount</h2>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="book-content-two">
                    <div class="section-title">
                        
                        <span class="sp-color">{{ $book->short_title }}</span>
                        <h2>{{ $book->main_title }}</h2>
                        <p>
                        {{ $book->short_desc }}
                        </p>
                    </div>
                    <a href="#" class="default-btn btn-bg-three">Quick Booking</a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="book-img-2">
                    <img src="{{ asset($book->image) }}" alt="Images" height="600px" width="600px">
                </div>
            </div>
        </div>
    </div>
</div>