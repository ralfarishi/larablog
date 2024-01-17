<div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">

  <div class="sidebar ps-lg-4">
    <div class="sidebar-item search-form">
      <h3 class="sidebar-title">Search</h3>
      <form action="{{ route('search') }}" class="mt-3" method="GET" id="search-form">
        <input type="text" name="q" placeholder="Find article ...">
        <button type="submit" onclick="submitSearchForm()">
          <i class="bi bi-search"></i>
        </button>
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
          <p class="fs-6">No tags found.</p>
        @endif
      </ul>
    </div>

  </div>
  <!-- End Blog Sidebar -->

</div>

@section('page_scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js" integrity="sha512-5CYOlHXGh6QpOFA/TeTylKLWfB3ftPsde7AnmhuitiTX4K5SqCLBeKro6sPS8ilsz1Q4NRx3v8Ko2IBiszzdww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
  <script>
    /**
     * Highlight searched text result
     */
    function highlightText() {
      const searchQuery = new URLSearchParams(window.location.search).get("q");

      if (searchQuery) {
        const postsList = new Mark(document.querySelector(".posts-list"));
        postsList.unmark();
        postsList.mark(searchQuery);
      }
    }

    document.addEventListener("DOMContentLoaded", highlightText);

    function submitSearchForm() {
      document.getElementById("search-form").submit();
    }
  </script>
@endsection