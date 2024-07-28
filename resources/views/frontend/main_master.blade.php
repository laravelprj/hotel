<!doctype html>
<html lang="zxx">
    <head>
        <!-- Required Meta Tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS --> 
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
        <!-- Animate Min CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/fonts/flaticon.css') }}">
        <!-- Boxicons CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/boxicons.min.css') }}">
        <!-- Magnific Popup CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
        <!-- Owl Carousel Min CSS --> 
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.theme.default.min.css') }}">
        <!-- Nice Select Min CSS --> 
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/nice-select.min.css') }}">
        <!-- Meanmenu CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/meanmenu.css') }}">
        <!-- Jquery Ui CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery-ui.css') }}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}">
        <!-- Theme Dark CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/theme-dark.css') }}">
        {{-- //toast --}}
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('frontend/assets/img/favicon.png') }}">

        <title>Vagabond Hotel</title>
    </head>
    <body>

        <!-- PreLoader Start -->
        <div class="preloader">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="sk-cube-area">
                        <div class="sk-cube1 sk-cube"></div>
                        <div class="sk-cube2 sk-cube"></div>
                        <div class="sk-cube4 sk-cube"></div>
                        <div class="sk-cube3 sk-cube"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PreLoader End -->

        <!-- Top Header Start -->
        @include('frontend.component.header')
        <!-- Top Header End -->

        <!-- Start Navbar Area -->
        @include('frontend.component.nav')
     
        <!-- End Navbar Area -->

        <!-- Banner Area -->
        
        @yield('frontend')

        <!-- Footer Area -->
        @php
    $setting = App\Models\SiteSetting::find(1);
@endphp


        <footer class="footer-area footer-bg">
            <div class="container">
                <div class="footer-top pt-100 pb-70">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget">
                                <div class="footer-logo">
                                    <a href="index.html">
                                        <img src="{{ asset($setting->logo) }}" style="width: 105px; height: 57px" alt="Images">
                                    </a>
                                </div>
                                <p>
                                    Aenean finibus convallis nisl sit amet hendrerit. Etiam blandit velit non lorem mattis, non ultrices eros bibendum .
                                </p>
                                <ul class="footer-list-contact">
                                    <li>
                                        <i class='bx bx-home-alt'></i>
                                        <a href="#">{{ $setting->address }}</a>
                                    </li>
                                    <li>
                                        <i class='bx bx-phone-call'></i>
                                        <a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a>
                                    </li>
                                    <li>
                                        <i class='bx bx-envelope'></i>
                                        <a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
    
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget pl-5">
                                <h3>Links</h3>
                                <ul class="footer-list">
                                    <li>
                                        <a href="about.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            About Us
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="services-1.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Services
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="team.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Team
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="gallery.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Gallery
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="terms-condition.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Terms 
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="privacy-policy.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Privacy Policy
                                        </a>
                                    </li> 
                                </ul>
                            </div>
                        </div>
    
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget">
                                <h3>Useful Links</h3>
                                <ul class="footer-list">
                                    <li>
                                        <a href="index.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Home
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="blog-1.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Blog
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="faq.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            FAQ
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="testimonials.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Testimonials
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="gallery.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Gallery
                                        </a>
                                    </li> 
                                    <li>
                                        <a href="contact.html" target="_blank">
                                            <i class='bx bx-caret-right'></i>
                                            Contact Us
                                        </a>
                                    </li> 
                                </ul>
                            </div>
                        </div>
    
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget">
                                <h3>Newsletter</h3>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                    Nullam tempor eget ante fringilla rutrum aenean sed venenatis .
                                </p>
                                <div class="footer-form">
                                    <form class="newsletter-form" data-toggle="validator" method="POST">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" placeholder="Your Email*" name="EMAIL" required autocomplete="off">
                                                </div>
                                            </div>
    
                                            <div class="col-lg-12 col-md-12">
                                                <button type="submit" class="default-btn btn-bg-one">
                                                    Subscribe Now
                                                </button>
                                                <div id="validator-newsletter" class="form-result"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="copy-right-area">
                    <div class="row">
                        <div class="col-lg-8 col-md-8">
                            <div class="copy-right-text text-align1">
                                <p>
                                    Copyright @<script>document.write(new Date().getFullYear())</script> Atoli. All Rights Reserved by 
                                    <a href="https://hibootstrap.com/" target="_blank">HiBootstrap</a> 
                                </p>
                            </div>
                        </div>
    
                        <div class="col-lg-4 col-md-4">
                            <div class="social-icon text-align2">
                                <ul class="social-link">
                                    <li>
                                        <a href="{{ $setting->facebook }}" target="_blank"><i class='bx bxl-facebook'></i></a>
                                    </li> 
                                    <li>
                                        <a href="{{ $setting->twitter }}" target="_blank"><i class='bx bxl-twitter'></i></a>
                                    </li> 
                                    <li>
                                        <a href="#" target="_blank"><i class='bx bxl-instagram'></i></a>
                                    </li> 
                                    <li>
                                        <a href="#" target="_blank"><i class='bx bxl-pinterest-alt'></i></a>
                                    </li> 
                                    <li>
                                        <a href="#" target="_blank"><i class='bx bxl-youtube'></i></a>
                                    </li> 
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Area End -->


        <!-- Jquery Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap Bundle Min JS -->
        <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Magnific Popup Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>
        <!-- Owl Carousel Min JS -->
        <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
        <!-- Nice Select Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.nice-select.min.js') }}"></script>
        <!-- Wow Min JS -->
        <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
        <!-- Jquery Ui JS -->
        <script src="{{ asset('frontend/assets/js/jquery-ui.js') }}"></script>
        <!-- Meanmenu JS -->
        <script src="{{ asset('frontend/assets/js/meanmenu.js') }}"></script>
        <!-- Ajaxchimp Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.ajaxchimp.min.js') }}"></script>
        <!-- Form Validator Min JS -->
        <script src="{{ asset('frontend/assets/js/form-validator.min.js') }}"></script>
        <!-- Contact Form JS -->
        <script src="{{ asset('frontend/assets/js/contact-form-script.js') }}"></script>
        <!-- Custom JS -->
        <script src="{{ asset('frontend/assets/js/custom.js') }}"></script>

        
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

	<script>
	 @if(Session::has('message'))
	 var type = "{{ Session::get('alert-type','info') }}"
	 switch(type){
		case 'info':
		toastr.info(" {{ Session::get('message') }} ");
		break;
		case 'success':
		toastr.success(" {{ Session::get('message') }} ");
		break;
		case 'warning':
		toastr.warning(" {{ Session::get('message') }} ");
		break;
		case 'error':
		toastr.error(" {{ Session::get('message') }} ");
		break; 
	 }
	 @endif 
	</script>
        
    </body>
</html>