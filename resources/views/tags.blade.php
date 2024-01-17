@extends('layouts.templates')

@section('page-title')
  Tags
@endsection

@section('content-id')
<!-- ======= Breadcrumbs ======= -->
<div class="breadcrumbs d-flex align-items-center" style="background-image: url({{ asset('images/blog-header.jpg') }});">
  <div class="container position-relative d-flex flex-column align-items-center">
    <i class="bi bi-tags fs-1 text-light"></i>

    <h2>Tag</h2>
    <ol>
      <li><a href="/">Home</a></li>
      <li>{{ Str::title($selectedTag) }}</li>
    </ol>

  </div>
</div>

<section id="blog" class="blog">
  <div class="container" data-aos="fade-up">

    <div class="row g-5">

      <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">

        <div class="row gy-5 posts-list">

          @if (count($posts) > 0)
            @foreach ($posts as $post)
            <div class="col-lg-6">
              <article class="d-flex flex-column">

                <div class="post-img">
                  <img src="{{ asset('uploads/' . $post->image) }}" alt="" class="img-fluid">
                </div>

                <h2 class="title">
                  <a href="{{ route('post', $post->slug) }}">{{ $post->title }}</a>
                </h2>

                <div class="meta-top">
                  <ul>
                    <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="{{ route('post', $post->slug) }}">{{ $post->user->name }}</a></li>
                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="{{ route('post', $post->slug) }}"><time datetime="2022-01-01">{{ $post->created_at->format('M d, Y') }}</time></a></li>
                    <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a href="{{ route('post', $post->slug) }}">{{ $post->comments->count() > 0 ? $post->comments->count() : 0}}
                        Comments</a></li>
                  </ul>
                </div>

                <div class="content">
                  <p>
                    {!! Str::limit(strip_tags($post->content), 280) !!}
                  </p>
                </div>

                <div class="read-more mt-auto align-self-end">
                  <a href="{{ route('post', $post->slug) }}">Read More <i class="fa-solid fa-arrow-right"></i></a>
                </div>

              </article>
            </div>
            @endforeach
          @else
            <p class="text-center">No artikel found.</p>
          @endif

        </div><!-- End blog posts list -->

        <div class="blog-pagination">
          <ul class="justify-content-center">
            @for ($i = 1; $i <= $posts->lastPage(); $i++)
              <li class="{{ $i === $posts->currentPage() ? 'active' : '' }}">
                <a href="{{ $posts->url($i) }}">{{ $i }}</a>
              </li>
            @endfor
          </ul>
        </div>
        <!-- End blog pagination -->

      </div>

      @include('partials._sidebar', [
        'categories' => $categories,
        'tags' => $tags
      ])

    </div>

  </div>
</section>
@endsection
