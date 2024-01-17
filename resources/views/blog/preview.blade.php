@extends('layouts.templates')

@section('page_css')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.8.0/build/styles/atom-one-dark.min.css">
@endsection

@php
  $hideNavbar = true;
@endphp

@section('page-title')
  {{ $post->title }}
@endsection

@section('content-id')
<div class="breadcrumbs d-flex align-items-center" style="background-image: url({{ asset('images/blog-header.jpg') }});">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Preview Artikel</h2>
    <ol>
      <li><a href="{{ route('article.index') }}">List Artikel</a></li>
      <li>{{ $post->title }}</li>
    </ol>

  </div>
</div>

<section id="blog" class="blog">
  <div class="container" data-aos="fade-up">

    <div class="row g-5">

      <div class="col-lg-12" data-aos="fade-up" data-aos-delay="200">

        <article class="blog-details">

          <div class="ribbon"><span>PREVIEW</span></div>

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
                  <time datetime="2020-01-01">{{ $post->created_at->format('M d, Y') }}</time>
                </a>
              </li>
              <li class="d-flex align-items-center">
                <i class="bi bi-chat-dots"></i>
                <a href="javascript:void(0)">0 Comments</a>
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
              <li><a href="javascript:void(0)">{{ $post->category->name }}</a></li>
            </ul>

            <i class="bi bi-tags"></i>
            <ul class="tags">
              @foreach ($tags as $tag)
                <li><a href="{{ route('post-by-tag', $tag) }}">{{ Str::title($tag) }}</a></li>
              @endforeach
            </ul>
          </div>
          <!-- End meta bottom -->
        </article>
        <!-- End blog post -->

        <div class="comments">

          <h4 class="comments-count">0 Comments</h4>
            <div id="comment-1" class="comment">
              <div class="d-flex">
                <div class="comment-img">
                  <img src="https://ui-avatars.com/api/?name=John+Doe" alt="">
                </div>
                <div>
                  <h5><a href="javascript:void(0)">John Doe</a></h5>
                  <time datetime="2020-01-01">17 Sep, 2023</time>
                  <p>
                    Preview komentar
                  </p>
                </div>
              </div>
            </div>
          <!-- End comment -->

          @php
            $disabledForm = $post->allowed_comment ? '' : 'disabled';
          @endphp

          <div class="reply-form">

            <h4>Leave a Reply</h4>
            <p>Your email address will not be published. Required fields are marked * </p>
            <form action="javascript:void(0)" method="POST" id="comment-section">
              <div class="row">
                <div class="col-md-6 form-group">
                  <input name="user_name" type="text" class="form-control" placeholder="Your Name*" {{ $disabledForm }}>
                </div>
                <div class="col-md-6 form-group">
                  <input name="user_email" type="text" class="form-control" placeholder="Your Email*" {{ $disabledForm }}>
                </div>
              </div>
              <div class="row">
                <div class="col form-group">
                  <textarea name="content" class="form-control" placeholder="Your Comment*" rows="6" {{ $disabledForm }}></textarea>
                </div>
              </div>
              <button type="submit" class="btn btn-primary" {{ $disabledForm }}>Post Comment</button>
            </form>
          </div>
        </div>
        <!-- End blog comments -->
      </div>
    </div>
  </div>
</section>
@endsection

@section('page_scripts')
  <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.8.0/build/highlight.min.js"></script>
  <script>
    hljs.highlightAll();
  </script>
@endsection