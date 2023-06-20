@extends('layouts.templates')
@section('content-id')
    <div class="breadcrumbs">
      <div class="page-header d-flex align-items-center" style="background-image: url('');">
        <div class="container position-relative">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-6 text-center">
              <h2>Mading Online JeWePe</h2>
              <p>Terhubung, Belajar, dan Berbagi di Mading Online JeWePe: Ruang Virtual yang Mempererat Komunitas Sekolah!</p>
            </div>
          </div>
        </div>
      </div>
      
      <nav>
        <div class="container">
          <ol>
            <li><a href="/">Home</a></li>
            <li>Blog</li>
          </ol>
        </div>
      </nav>
    </div>

		
    <section id="blog" class="blog">
			<div class="container">
				<h3>Search Results for "{{ $search_str }}"</h3>
        <div class="row gy-4 posts-list">
          @php
            $recent_posts_count = 0;
          @endphp
          
          @foreach ($search_results as $post)
            <div class="col-xl-4 col-md-6">
              <article>

                <div class="post-img">
                  <a href="{{ route('post', $post->slug) }}">
                    <img src="{{ asset($post->featured_image->thumb) }}" alt="" class="img-fluid">
                  </a>
                </div>

                <h2 class="title">
                  <a href="{{ route('post', $post->slug) }}">{{ $post->title }}</a>
                </h2>

                <div>
                  {!! str_limit($post->content,280) !!}
                </div>

                <div class="d-flex align-items-center">
                  <div class="post-meta">
                    <p class="post-date">
                      <time datetime="2022-01-01">{{ $post->created_at->diffForHumans() }}</time>
                    </p>
                  </div>
                </div>

              </article>
            </div>
          @endforeach

					<div class="text-center">
						{{ $search_results->appends(['search_str'=>Request::get('search_str')])->links()}}
					</div>
        </div>
      </div>
    </section>

@endsection