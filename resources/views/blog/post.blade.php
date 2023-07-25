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
    </div>

<section id="blog" class="blog">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg">
        <article class="blog-details">
          <div class="post-img">
              <img src="{{ asset($post->featured_image->original) }}" alt="" class="img-fluid d-flex w-75 mx-auto">
          </div>

          <h2 class="title">{{ $post->title }}</h2>

          <div class="meta-top">
            <ul>
              <li class="d-flex align-items-center">
                <i class="bi bi-person"></i>
                <a href="blog-details.html">
                  {{ $post->user->name }}
                </a>
              </li>
              <li class="d-flex align-items-center">
                <i class="bi bi-clock"></i>
                <a href="blog-details.html">
                  <time datetime="2020-01-01">
                    {{ $post->created_at->diffForHumans() }}
                  </time>
                </a>
              </li>
            </ul>
          </div>

          <div class="content">
            <p>
              {!! $post->content !!}
            </p>
          </div>
          <div class="comments">
              <h4 class="comments-count">Comments</h4>
              @foreach ($active_comments as $comment)
                <div id="comment-1" class="comment">
                  <div class="d-flex">
                    <div class="comment-img"><img src="assets/img/blog/comments-1.jpg" alt=""></div>
                    <div>
                      <h5><a href="">{{ $comment->user_name }}</a></h5>
                      <p>
                        {{ $comment->content }}
                      </p>
                    </div>
                  </div>
                </div>
              @endforeach

              @php
                $disabledForm = $post->allowed_comment ? '' : 'disabled';
              @endphp

              <div class="reply-form">
                <h4>Leave a Comment</h4>
                <p>Your email address will not be published. Required fields are marked * </p>
                  {{ Form::open(['url'=>'artikel/'.$post->slug.'/save-comment','method'=>'POST']) }}
                    @csrf
                    <div class="row">
                      <div class="col-md-6 form-group {{ $errors->has('user_name') ? ' has-error' : '' }}">
                        {{-- <input name="name" type="text" class="form-control" placeholder="Your Name*"> --}}
                        {{ Form::text('user_name',null,['placeholder' => 'Name' , 'class' => 'form-control', $disabledForm]) }}
                        @if ($errors->has('user_name'))
                            <span class="help-block text-danger">
                              <strong>{{ $errors->first('user_name') }}</strong>
                            </span>
                        @endif
                      </div>
                      <div class="col-md-6 form-group {{ $errors->has('user_email') ? ' has-error' : '' }}">
                        {{ Form::text('user_email',null,["placeholder"=> "Email" , 'class' => 'form-control', $disabledForm]) }}
                        @if ($errors->has('user_email'))
                            <span class="help-block text-danger">
                              <strong>{{ $errors->first('user_email') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="row">
                      <div class="col form-group {{ $errors->has('content') ? ' has-error' : '' }}">
                        {{ Form::textarea('content',null,["placeholder"=>"Comment", 'class' => 'form-control', $disabledForm])}}
                        @if ($errors->has('content'))
                            <span class="help-block text-danger">
                              <strong>{{ $errors->first('content') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary" {{ $disabledForm }}>Post Comment</button>
                  {{ Form::close() }}
              </div>
            </div>
        </article>
      </div>
    </div>
  </div>
</section>
@endsection
