<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Page Accessibility Smoke Tests
 *
 * This suite performs an HTTP GET against every publicly-accessible and
 * role-gated page and asserts a 200 response. It catches runtime errors
 * (500s) that unit tests miss because they are integration-level: the full
 * request pipeline (middleware → controller → view) executes.
 *
 * Roles tested:
 *   - guest (unauthenticated)
 *   - reader
 *   - writer
 *   - admin
 */
class PageAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $writer;
    private User $reader;
    private Category $category;
    private Tag $tag;
    private Post $publishedPost;
    private Post $draftPost;

    /**
     * Seed shared fixtures once for the whole test class.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin  = User::factory()->create(['role' => 'admin',  'slug' => 'admin-user']);
        $this->writer = User::factory()->create(['role' => 'writer', 'slug' => 'writer-user']);
        $this->reader = User::factory()->create(['role' => 'reader', 'slug' => 'reader-user']);

        $this->category = Category::create(['name' => 'Technology', 'slug' => 'technology']);

        $this->tag = Tag::factory()->create(['name' => 'laravel', 'slug' => 'laravel']);

        // Published post — used for public article page, tag page, category page
        $this->publishedPost = Post::factory()->create([
            'title'           => 'Published Test Article',
            'slug'            => 'published-test-article',
            'status'          => 'published',
            'user_id'         => $this->writer->id,
            'category_id'     => $this->category->id,
            'allowed_comment' => true,
            'content'         => json_encode([
                'type'    => 'doc',
                'content' => [
                    ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello world paragraph content.']]],
                ],
            ]),
        ]);

        // Attach a tag via pivot to exercise the BelongsToMany relation path
        $this->publishedPost->tags()->attach($this->tag->id);

        // Draft post — available in admin panel
        $this->draftPost = Post::factory()->create([
            'title'       => 'Draft Test Article',
            'slug'        => 'draft-test-article',
            'status'      => 'draft',
            'user_id'     => $this->writer->id,
            'category_id' => $this->category->id,
        ]);

        // Comment for moderation pages
        Comment::create([
            'post_id' => $this->publishedPost->id,
            'user_id' => $this->reader->id,
            'content' => 'This is a test comment for accessibility tests.',
            'active'  => true,
        ]);
    }

    // ────────────────────────────────────────────────────────────────────────
    // GROUP 1: Public Pages (Guest access)
    // ────────────────────────────────────────────────────────────────────────

    #[Test]
    public function test_homepage_is_accessible_to_guests(): void
    {
        $this->get('/')->assertStatus(200);
    }

    #[Test]
    public function test_article_show_page_is_accessible_to_guests(): void
    {
        // This is the page that triggers "pluck() on string" when tags are not
        // retrieved via the pivot relation correctly.
        $this->get("/article/{$this->publishedPost->slug}")->assertStatus(200);
    }

    #[Test]
    public function test_category_page_is_accessible_to_guests(): void
    {
        $this->get("/category/{$this->category->name}")->assertStatus(200);
    }

    #[Test]
    public function test_tag_page_is_accessible_to_guests(): void
    {
        $this->get("/tag/{$this->tag->slug}")->assertStatus(200);
    }

    #[Test]
    public function test_post_by_user_page_is_accessible_to_guests(): void
    {
        $this->get("/article/user/{$this->writer->slug}")->assertStatus(200);
    }

    #[Test]
    public function test_search_page_is_accessible_to_guests(): void
    {
        $this->get('/search')->assertStatus(200);
    }

    #[Test]
    public function test_search_page_with_query_is_accessible_to_guests(): void
    {
        $this->get('/search?q=laravel')->assertStatus(200);
    }

    #[Test]
    public function test_login_page_is_accessible_to_guests(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    #[Test]
    public function test_register_page_is_accessible_to_guests(): void
    {
        $this->get('/register')->assertStatus(200);
    }

    // ────────────────────────────────────────────────────────────────────────
    // GROUP 2: Auth-Protected Pages (Any authenticated user)
    // ────────────────────────────────────────────────────────────────────────

    #[Test]
    public function test_reader_dashboard_is_accessible_to_reader(): void
    {
        $this->actingAs($this->reader)
            ->get('/my/dashboard')
            ->assertStatus(200);
    }

    #[Test]
    public function test_reader_dashboard_is_accessible_to_admin(): void
    {
        $this->actingAs($this->admin)
            ->get('/my/dashboard')
            ->assertStatus(200);
    }

    #[Test]
    public function test_bookmark_redirect_works_for_auth_user(): void
    {
        $this->actingAs($this->reader)
            ->get('/my/bookmarks')
            ->assertRedirect('/my/dashboard');
    }

    // ────────────────────────────────────────────────────────────────────────
    // GROUP 3: Admin + Writer Dashboard Pages
    // ────────────────────────────────────────────────────────────────────────

    #[Test]
    public function test_dashboard_is_accessible_to_writer(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard')
            ->assertStatus(200);
    }

    #[Test]
    public function test_dashboard_is_accessible_to_admin(): void
    {
        $this->actingAs($this->admin)
            ->get('/dashboard')
            ->assertStatus(200);
    }

    #[Test]
    public function test_article_list_is_accessible_to_writer(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard/article')
            ->assertStatus(200);
    }

    #[Test]
    public function test_article_create_page_is_accessible_to_writer(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard/article/create')
            ->assertStatus(200);
    }

    #[Test]
    public function test_article_edit_page_is_accessible_to_writer(): void
    {
        $this->actingAs($this->writer)
            ->get("/dashboard/article/{$this->draftPost->slug}/edit")
            ->assertStatus(200);
    }

    #[Test]
    public function test_article_preview_is_accessible_to_writer(): void
    {
        // PreviewController redirects published posts back to the article list
        // ("Can't preview a published article."). Must use a draft to hit the 200 path.
        $this->actingAs($this->writer)
            ->get("/dashboard/article/p/{$this->draftPost->slug}")
            ->assertStatus(200);
    }

    #[Test]
    public function test_comment_list_is_accessible_to_writer(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard/comment')
            ->assertStatus(200);
    }

    #[Test]
    public function test_user_list_is_accessible_to_writer(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard/user')
            ->assertStatus(200);
    }

    // ────────────────────────────────────────────────────────────────────────
    // GROUP 4: Admin-Only Pages
    // ────────────────────────────────────────────────────────────────────────

    #[Test]
    public function test_category_list_is_accessible_to_admin(): void
    {
        $this->actingAs($this->admin)
            ->get('/dashboard/category')
            ->assertStatus(200);
    }

    #[Test]
    public function test_analytics_page_is_accessible_to_admin(): void
    {
        // This also exercises the ceil() fix for avg('reading_time')
        $this->actingAs($this->admin)
            ->get('/dashboard/analytics')
            ->assertStatus(200);
    }

    #[Test]
    public function test_login_history_is_accessible_to_admin(): void
    {
        $this->actingAs($this->admin)
            ->get('/dashboard/login-history')
            ->assertStatus(200);
    }

    // ────────────────────────────────────────────────────────────────────────
    // GROUP 5: Access-Control Assertions (Non-admin routes blocked)
    // ────────────────────────────────────────────────────────────────────────

    #[Test]
    public function test_reader_is_forbidden_from_dashboard(): void
    {
        $this->actingAs($this->reader)
            ->get('/dashboard')
            ->assertStatus(403);
    }

    #[Test]
    public function test_reader_is_forbidden_from_analytics(): void
    {
        $this->actingAs($this->reader)
            ->get('/dashboard/analytics')
            ->assertStatus(403);
    }

    #[Test]
    public function test_writer_is_forbidden_from_analytics(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard/analytics')
            ->assertStatus(403);
    }

    #[Test]
    public function test_writer_is_forbidden_from_category_management(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard/category')
            ->assertStatus(403);
    }

    #[Test]
    public function test_writer_is_forbidden_from_login_history(): void
    {
        $this->actingAs($this->writer)
            ->get('/dashboard/login-history')
            ->assertStatus(403);
    }

    #[Test]
    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    #[Test]
    public function test_guest_is_redirected_from_reader_dashboard(): void
    {
        $this->get('/my/dashboard')->assertRedirect('/login');
    }
}
