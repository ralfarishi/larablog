@extends('layouts.templates')

@section('page_css')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@endsection

@section('content-id')
<div class="breadcrumbs d-flex align-items-center" style="background-image: url({{ asset('images/blog-header.jpg') }});">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Article Detail</h2>
    <ol>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li>{{ $post->title }}</li>
    </ol>

  </div>
</div>

<section id="blog" class="blog">
  <div class="container" data-aos="fade-up">

    <div class="row g-5">

      <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">

        <article class="blog-details">

          <div class="post-img">
            <img src="{{ asset('uploads/' . $post->image) }}" alt="" class="img-fluid">
          </div>

          <h2 class="title">{{ $post->title }}</h2>

          <div class="meta-top">
            <ul>
              <li class="d-flex align-items-center">
                <i class="bi bi-person"></i>
                <a href="javascript:void(0)">{{ $post->user->name }}</a>
              </li>
              <li class="d-flex align-items-center">
                <i class="bi bi-clock"></i>
                <a href="javascript:void(0)">
                  <time datetime="{{ $post->created_at }}">{{ formatDate($post->created_at) }}</time>
                </a>
              </li>
              <li class="d-flex align-items-center">
                <i class="bi bi-chat-dots"></i>
                <a href="javascript:void(0)">{{ $totalComments }} Comments</a>
              </li>
            </ul>
          </div>
          <!-- End meta top -->

          <div class="content">
            {!! $post->content !!}
          </div>
          <!-- End post content -->

          <div class="meta-bottom">
            <i class="{{ $post->category->icon }}"></i>
            <ul class="cats">
              <li><a href="{{ route('categories', Str::lower($post->category->name)) }}">{{ $post->category->name }}</a></li>
            </ul>

            <i class="bi bi-tags"></i>
            <ul class="tags">
              @if (!empty(array_filter($postTags)))
                @foreach ($postTags as $postTag)
                  @if (!empty($postTag))
                    <li>
                      <a href="{{ route('post-by-tag', $postTag) }}">
                        {{ Str::title($postTag) }}
                      </a>
                    </li>
                  @endif
                @endforeach
              @else
                <li>
                  <a href="javascript:void(0)">Belum ada tag.</a>
                </li>
              @endif
            </ul>
          </div>
          <!-- End meta bottom -->
        </article>
        <!-- End blog post -->

        <div class="comments">

          <h4 class="comments-count">{{ $totalComments > 0 ? $totalComments : 0}} Comments</h4>


          @foreach ($activeComments as $comment)
          
            @php
              if ($comment->user->role === 'user') {
                $badgeColor = "primary";
              } else {
                $badgeColor = "success";
              }
            @endphp

            <div id="comment-1" class="comment">
              <div class="d-flex">
                <div class="comment-img">
                  <img src="https://ui-avatars.com/api/?name={{ Str::slug($comment->user->name, '+') }}" alt="">
                </div>
                <div>
                  <h5>
                    <a href="">
                      {{ $comment->user->name }}
                    </a>
                    @if ($comment->user->role !== 'admin')
                      <span class="badge rounded-pill text-bg-{{ $badgeColor }}">{{ Str::lower($comment->user->role) }}</span>
                    @endif
                  </h5>
                  <time datetime="2020-01-01">{{ $comment->created_at->format('M d, Y') }}</time>
                  <p>
                    {{ $comment->content }}
                  </p>
                </div>
              </div>
            </div>
          @endforeach
          <!-- End comment -->

          @php
            $disabledForm = $post->allowed_comment ? '' : 'disabled';
          @endphp

          <div class="reply-form">

            <h4>Leave a Reply</h4>
            <p>Your email address will not be published. Required fields are marked * </p>
            @guest
             <a href="{{ route('login') }}" class="btn btn-primary">Login to comment</a>   
            @endguest

            @auth
              <form action="{{ route('store-comment', $post->slug) }}#comment-section" method="POST" id="comment-section">
                @method('POST')
                @csrf
                {{-- <div class="row">
                  <div class="col-md-6 form-group">
                    <input name="user_name" type="text" class="form-control" placeholder="Your Name*" {{ $disabledForm }}>
                    @if ($errors->has('user_name'))
                      <span class="help-block text-danger">
                        <p>{{ $errors->first('user_name') }}</p>
                      </span>
                    @endif
                  </div>
                  <div class="col-md-6 form-group">
                    <input name="user_email" type="text" class="form-control" placeholder="Your Email*" {{ $disabledForm }}>
                    @if ($errors->has('user_email'))
                      <span class="help-block text-danger">
                        <p>{{ $errors->first('user_email') }}</p>
                      </span>
                    @endif
                  </div>
                </div> --}}
                {{-- <input type="hidden" name="user_name" class="form-control" value="{{ Auth::user()->name }}">
                <input type="hidden" name="user_email" class="form-control" value="{{ Auth::user()->email }}"> --}}
                <div class="row">
                  <div class="col form-group">
                    <textarea name="content" class="form-control" placeholder="Your Comment*" rows="6" {{ $disabledForm }}></textarea>
                    @if ($errors->has('content'))
                      <span class="help-block text-danger">
                        <p>{{ $errors->first('content') }}</p>
                      </span>
                    @endif
                  </div>
                </div>
                <button type="submit" class="btn btn-primary" {{ $disabledForm }}>Post Comment</button>
              </form>
            @endauth
          </div>
        </div>
        <!-- End blog comments -->
      </div>

      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">

        <div class="sidebar ps-lg-4">

          <div class="sidebar-item categories">
            <h3 class="sidebar-title">Categories</h3>
            <ul class="mt-3">
              @foreach ($categories as $category)
                <li>
                  <a href="{{ route('categories', Str::lower($category->name)) }}">
                    {{ $category->name }}
                    <span>
                      ({{ $category->posts_count }})
                    </span>
                  </a>
                </li>
              @endforeach
            </ul>
          </div><!-- End sidebar categories-->

          <div class="sidebar-item recent-posts">
            <h3 class="sidebar-title">Related Posts</h3>

            <div class="mt-3">

              @foreach ($relatedPosts as $relatedPost)
                @if ($relatedPost->active == 1)
                  <div class="post-item">
                    <img src="{{ asset('uploads/' . $relatedPost->image) }}" alt="" class="flex-shrink-0">
                    <div>
                      <h4><a href="{{ route('post', $relatedPost->slug) }}">{{ $relatedPost->title }}</a></h4>
                      <time datetime="2020-01-01">{{ $relatedPost->created_at->format('M d, Y') }}</time>
                    </div>
                  </div>
                @endif
              @endforeach
              <!-- End recent post item-->
            </div>
          </div>
          <!-- End sidebar recent posts-->

          <div class="sidebar-item tags">
            <h3 class="sidebar-title">Tags</h3>
            <ul class="mt-3">
              @foreach ($tags as $tag)
                <li>
                  <a href="{{ route('post-by-tag', $tag) }}">
                    {{ Str::title($tag) }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
        <!-- End Blog Sidebar -->
      </div>
    </div>
  </div>
</section>
@endsection

@section('page_scripts')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <script>
    $(document).ready(function() {
      $("table").addClass("table table-striped table-bordered");
    });
  </script>
@endsection

@include('includes.toast')