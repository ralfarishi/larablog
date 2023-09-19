@extends('layouts.templates')

@section('page-title', 'Home')

@section('content-id')
<!-- ======= Breadcrumbs ======= -->
<div class="breadcrumbs d-flex align-items-center" style="background-image: url({{ asset('images/blog-header.jpg') }});">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Cari Blog</h2>
    <ol>
      <li><a href="/">Home</a></li>
      <li>Pencarian</li>
    </ol>

  </div>
</div>

<section id="blog" class="blog">
  <div class="container" data-aos="fade-up">

    <div class="row g-5">

      <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">

        <div class="row gy-5 posts-list">
          <h1>Hasil Pencarian untuk "{{ $query }}"</h1>

          @if ($results->isEmpty())
            <p>Tidak ada hasil yang ditemukan</p>
          @else
            @foreach ($results as $result)
              <div class="col-lg-6">
                <article class="d-flex flex-column">

                  <div class="post-img">
                    <img src="{{ asset('uploads/' . $result->featured_image) }}" alt="" class="img-fluid">
                  </div>

                  <h2 class="title">
                    <a href="{{ route('post', $result->slug) }}">{{ $result->title }}</a>
                  </h2>

                  <div class="meta-top">
                    <ul>
                      <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="{{ route('post', $result->slug) }}">{{ $result->user->name }}</a></li>
                      <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="{{ route('post', $result->slug) }}"><time datetime="2022-01-01">{{ $result->created_at->format('M d, Y') }}</time></a></li>
                      <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a href="{{ route('post', $result->slug) }}">{{ $result->comments->count() > 0 ? $result->comments->count() : 0}} Comments</a></li>
                    </ul>
                  </div>

                  <div class="content">
                    <p>
                      {!! Str::limit(strip_tags($result->content), 280) !!}
                    </p>
                  </div>

                  <div class="read-more mt-auto align-self-end">
                    <a href="{{ route('post', $result->slug) }}">Read More <i class="bi bi-arrow-right"></i></a>
                  </div>

                </article>
              </div>
            @endforeach
          @endif

        </div><!-- End blog posts list -->

        <div class="blog-pagination">
          <ul class="justify-content-center">
            <li><a href="#">1</a></li>
            <li class="active"><a href="#">2</a></li>
            <li><a href="#">3</a></li>
          </ul>
        </div><!-- End blog pagination -->

      </div>

      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">

        <div class="sidebar ps-lg-4">

          <div class="sidebar-item search-form">
            <h3 class="sidebar-title">Search</h3>
            <form action="" class="mt-3">
              <input type="text">
              <button type="submit"><i class="bi bi-search"></i></button>
            </form>
          </div><!-- End sidebar search formn-->

          <div class="sidebar-item categories">
            <h3 class="sidebar-title">Categories</h3>
            <ul class="mt-3">
              @foreach ($categories as $category)
                <li>
                  <a href="{{ route('categories', Str::lower($category->name)) }}">{{ $category->name }}
                    <span>
                      ({{ $category->posts_count }})
                    </span>
                  </a>
                </li>
              @endforeach
            </ul>
          </div><!-- End sidebar categories-->

        </div><!-- End Blog Sidebar -->

      </div>

    </div>

  </div>
</section>
@endsection
