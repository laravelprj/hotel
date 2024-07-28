@php
$id = Auth::user()->id;
$profileData = App\Models\User::find($id);
@endphp

<div class="service-side-bar"> 
    <div class="services-bar-widget">
        <h3 class="title">User Sidebar </h3>
        <div class="side-bar-categories">
{{-- <img src="{{ asset('frontend/assets/img/blog/blog-profile1.jpg') }}" class="rounded mx-auto d-block" alt="Image" style="width:100px; height:100px;"> <br><br> --}}

<img src="{{ asset((!empty($profileData->photo)) ? url('upload/user_images/'.$profileData->photo) : url('upload/no-image.jpg')) }}" class="rounded mx-auto d-block" alt="Image" style="width:100px; height:100px;">
<center>
<b> {{ $profileData->name }}</b><br> 
<b>{{ $profileData->email }}</b>
</center>
<br>
<ul> 

<li>
<a href="{{ route('user.dashboard') }}">User Dashboard</a>
</li>
<li>
<a href="{{ route('user.profile') }}">User Profile </a>
</li>
<li>
<a href="{{ route('user.changepassword') }}">Change Password</a>
</li>
<li>
    <a href="{{ route('user.booking') }}">Booking Details </a>
</li>
<li>
<a href="{{ route('user.logout') }}">Logout</a>
</li>
</ul>
        </div>
    </div>
   
</div>