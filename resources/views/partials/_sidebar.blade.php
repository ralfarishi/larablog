<div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">

  <div class="sidebar ps-lg-4">
    <div class="sidebar-item search-form">
      <h3 class="sidebar-title">Search</h3>
      <form action="{{ route('search') }}" class="mt-3" method="GET">
        <input type="text" name="query" placeholder="Cari artikel ...">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>
    </div>
    <!-- End sidebar search formn-->

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
    </div>
    <!-- End sidebar categories-->

    <div class="sidebar-item tags">
      <h3 class="sidebar-title">Tags</h3>
      <ul class="mt-3">
        @if (!$tags->isEmpty())
          @foreach ($tags as $tag)
            @if (!empty($tag))
              <li>
                <a href="{{ route('post-by-tag', $tag) }}">
                  {{ Str::title($tag) }}
                </a>
              </li>
            @endif
          @endforeach
        @else
          <p class="fs-6">Belum ada tag.</p>
        @endif
      </ul>
    </div>

  </div>
  <!-- End Blog Sidebar -->

</div>