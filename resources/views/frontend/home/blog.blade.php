@php
    $blog = App\Models\BlogPost::Latest()->limit(3)->get();
@endphp

<div class="blog-area pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">BLOGS</span>
            <h2>Our Latest Blogs to the Intranational Journal at a Glance</h2>
        </div>
        <div class="row pt-45">
            @foreach ($blog as $item)
                
            <div class="col-lg-4 col-md-6">
                <div class="blog-item">
                    <a href="blog-details.html">
                        <img src="{{ asset($item->post_image) }}" style="height: 250px" alt="Images">
                    </a>
                    <div class="content">
                        <ul>
                            <li><li>{{ $item->created_at->format('M d Y')  }}</li></li>
                            <li><i class='bx bx-user'></i>29K</li>
                            <li><i class='bx bx-message-alt-dots'></i>15K</li>
                        </ul>
                        <h3>
                            <a href="blog-details.html">{{ $item->post_titile }}</a>
                        </h3>
                        <p> <p>{{ Str::limit($item->short_descp,100,'...') }}</p></p>
                        <a href="{{ route('blog.detail',$item->post_slug) }}" class="read-btn">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>
</div>