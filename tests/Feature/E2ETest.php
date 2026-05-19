<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Livewire\Blog\BookmarkToggle;
use App\Livewire\Blog\PostComments;
use App\Livewire\Admin\ArticleForm;
use App\Livewire\Admin\CategoryTable;
use App\Livewire\Admin\UserTable;
use App\Livewire\Admin\CommentTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class E2ETest extends TestCase
{
  use RefreshDatabase;

  /**
   * CUJ 1: Reader Journey - Homepage navigation & interactive elements
   */
  public function test_reader_journey_homepage_and_interactive_comments(): void
  {
    $reader = User::factory()->create(['role' => 'reader']);
    $category = Category::create(['name' => 'Tech', 'slug' => 'tech']);
    $post = Post::factory()->create([
      'category_id' => $category->id,
      'user_id' => $reader->id,
      'status' => 'published',
      'allowed_comment' => true,
    ]);

    // 1. Visit homepage
    $response = $this->get('/');
    $response->assertStatus(200);

    // 2. Bookmark toggle as guest (redirects to login)
    Livewire::test(BookmarkToggle::class, ['post' => $post])
      ->call('toggle')
      ->assertRedirect(route('login'));

    // 3. Bookmark toggle as authenticated reader
    Livewire::actingAs($reader)
      ->test(BookmarkToggle::class, ['post' => $post])
      ->assertSet('isBookmarked', false)
      ->call('toggle')
      ->assertSet('isBookmarked', true)
      ->call('toggle')
      ->assertSet('isBookmarked', false);

    // 4. Livewire Comments system interaction
    // Test validation constraints (minimum 10 characters)
    Livewire::actingAs($reader)
      ->test(PostComments::class, ['post' => $post])
      ->set('content', 'Short')
      ->call('postComment')
      ->assertHasErrors(['content' => 'min'])
      ->assertSessionMissing('message');

    // Test successful comment posting
    Livewire::actingAs($reader)
      ->test(PostComments::class, ['post' => $post])
      ->set('content', 'This is a high quality test comment for E2E testing.')
      ->call('postComment')
      ->assertHasNoErrors()
      ->assertSet('content', '');

    $this->assertDatabaseHas('comments', [
      'post_id' => $post->id,
      'user_id' => $reader->id,
      'content' => 'This is a high quality test comment for E2E testing.',
    ]);
  }

  /**
   * CUJ 2: Writer Journey - Article creation, validation, and publication
   */
  public function test_writer_journey_article_creation_and_validation(): void
  {
    $writer = User::factory()->create(['role' => 'writer']);
    $category = Category::create(['name' => 'General', 'slug' => 'general']);

    // Boot session
    $this->actingAs($writer)->get('/');

    // Validate fields and test saving post via ArticleForm Livewire component
    Livewire::actingAs($writer)
      ->test(ArticleForm::class)
      // Test missing title & content validation
      ->call('save')
      ->assertHasErrors(['title' => 'required', 'content' => 'required'])
      // Provide valid inputs
      ->set('title', 'Stunning New Laravel 13 Architecture')
      ->set('content', 'Laravel 13 is out and introduces highly scalable built-in features.')
      ->set('tags', 'laravel, php, frameworks')
      ->set('category_id', $category->id)
      ->set('status', 'published')
      ->call('save')
      ->assertHasNoErrors()
      ->assertRedirect(route('article.index'));

    $this->assertEquals('Article has been published.', session('success'));

    $this->assertDatabaseHas('posts', [
      'title' => 'Stunning New Laravel 13 Architecture',
      'slug' => 'stunning-new-laravel-13-architecture',
      'status' => 'published',
      'category_id' => $category->id,
      'user_id' => $writer->id,
    ]);
  }

  /**
   * CUJ 3: Admin Journey - Category administration CRUD flow
   */
  public function test_admin_journey_category_crud_operations(): void
  {
    $admin = User::factory()->create(['role' => 'admin']);

    // Boot session
    $this->actingAs($admin)->get('/');

    // Test creating a new category with CategoryTable Livewire component
    Livewire::actingAs($admin)
      ->test(CategoryTable::class)
      ->set('new_category_name', 'Scalable Microservices')
      ->call('createCategory')
      ->assertHasNoErrors()
      ->assertSet('new_category_name', '');

    $this->assertDatabaseHas('categories', [
      'name' => 'Scalable Microservices',
      'slug' => 'scalable-microservices',
    ]);

    $category = Category::where('slug', 'scalable-microservices')->first();

    // Test editing category
    Livewire::actingAs($admin)
      ->test(CategoryTable::class)
      ->call('startEditing', $category->id, 'Scalable Microservices')
      ->set('editingCategoryName', 'Distributed Microservices')
      ->call('updateCategory')
      ->assertHasNoErrors();

    $this->assertDatabaseHas('categories', [
      'id' => $category->id,
      'name' => 'Distributed Microservices',
      'slug' => 'distributed-microservices',
    ]);

    // Test deleting category
    Livewire::actingAs($admin)->test(CategoryTable::class)->call('delete', $category->id);

    $this->assertDatabaseMissing('categories', [
      'id' => $category->id,
    ]);
  }

  /**
   * Admin Journey - Cannot delete category with active posts
   */
  public function test_admin_cannot_delete_category_with_active_posts(): void
  {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create(['name' => 'Cloud', 'slug' => 'cloud']);

    // Create active post inside this category
    Post::factory()->create([
      'category_id' => $category->id,
      'user_id' => $admin->id,
      'status' => 'published',
    ]);

    // Boot session
    $this->actingAs($admin)->get('/');

    Livewire::actingAs($admin)->test(CategoryTable::class)->call('delete', $category->id);

    // Assert category is preserved
    $this->assertDatabaseHas('categories', [
      'id' => $category->id,
    ]);
  }

  /**
   * CUJ 3: Admin Journey - User administration and safety controls
   */
  public function test_admin_user_moderation_operations(): void
  {
    $admin = User::factory()->create(['role' => 'admin', 'slug' => 'main-admin']);
    $otherUser = User::factory()->create(['role' => 'writer', 'slug' => 'spammer-writer']);

    // Boot session
    $this->actingAs($admin)->get('/');

    // 1. Admin cannot delete their own account
    Livewire::actingAs($admin)->test(UserTable::class)->call('delete', $admin->slug);

    $this->assertDatabaseHas('users', ['id' => $admin->id]);

    // 2. Admin can successfully delete other accounts
    Livewire::actingAs($admin)->test(UserTable::class)->call('delete', $otherUser->slug);

    $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);
  }

  /**
   * CUJ 3: Admin Journey - Comment moderation & interactive status toggles
   */
  public function test_admin_comment_moderation_operations(): void
  {
    $admin = User::factory()->create(['role' => 'admin']);
    $writer = User::factory()->create(['role' => 'writer']);
    $category = Category::create(['name' => 'News', 'slug' => 'news']);
    $post = Post::factory()->create([
      'category_id' => $category->id,
      'user_id' => $writer->id,
      'status' => 'published',
    ]);

    $comment = \App\Models\Comment::create([
      'post_id' => $post->id,
      'user_id' => $writer->id,
      'content' => 'This is a comment waiting for admin moderation.',
      'active' => true,
    ]);

    // Boot session
    $this->actingAs($admin)->get('/');

    // 1. Admin can toggle comment status (active status)
    Livewire::actingAs($admin)
      ->test(CommentTable::class)
      ->call('toggleStatus', $comment->id)
      ->assertDispatched('toast');

    $comment->refresh();
    $this->assertFalse((bool) $comment->active);

    // 2. Admin can delete comments
    Livewire::actingAs($admin)->test(CommentTable::class)->call('delete', $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  }
}
