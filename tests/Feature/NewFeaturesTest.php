<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\ArticleForm;
use App\Livewire\Blog\ReaderDashboard;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test that guests cannot access the reader dashboard.
   */
  public function test_guest_cannot_access_reader_dashboard(): void
  {
    $this->get('/my/dashboard')->assertRedirect(route('login'));
  }

  /**
   * Test Reader Dashboard component: initial rendering, tabs, and functionality.
   */
  public function test_reader_dashboard_functionality(): void
  {
    $reader = User::factory()->create(['role' => 'reader']);
    $writer = User::factory()->create(['role' => 'writer']);
    $category = Category::create(['name' => 'General', 'slug' => 'general']);

    // Create a post
    $post = Post::factory()->create([
      'category_id' => $category->id,
      'user_id' => $writer->id,
      'status' => 'published',
    ]);

    // Bookmark the post
    $bookmark = $reader->bookmarks()->create([
      'post_id' => $post->id,
    ]);

    // Comment on the post
    $comment = Comment::create([
      'post_id' => $post->id,
      'user_id' => $reader->id,
      'content' => 'This is a test comment on the reader dashboard.',
      'active' => true,
    ]);

    // 1. Check initial rendering of the dashboard and default tab (bookmarks)
    Livewire::actingAs($reader)
      ->test(ReaderDashboard::class)
      ->assertStatus(200)
      ->assertViewHas('bookmarks')
      ->assertViewHas('comments')
      ->assertSet('activeTab', 'bookmarks');

    // 2. Test switching tabs
    Livewire::actingAs($reader)
      ->test(ReaderDashboard::class)
      ->call('setTab', 'comments')
      ->assertSet('activeTab', 'comments')
      ->call('setTab', 'profile')
      ->assertSet('activeTab', 'profile')
      ->call('setTab', 'invalid_tab') // Should ignore
      ->assertSet('activeTab', 'profile');

    // 3. Test removing a bookmark
    Livewire::actingAs($reader)
      ->test(ReaderDashboard::class)
      ->call('removeBookmark', $bookmark->id)
      ->assertDispatched('toast', message: 'Bookmark removed.');

    $this->assertDatabaseMissing('bookmarks', [
      'id' => $bookmark->id,
    ]);

    // 4. Test deleting a comment
    Livewire::actingAs($reader)
      ->test(ReaderDashboard::class)
      ->call('deleteComment', $comment->id)
      ->assertDispatched('toast', message: 'Comment deleted.');

    $this->assertDatabaseMissing('comments', [
      'id' => $comment->id,
    ]);

    // 5. Test profile settings update
    Livewire::actingAs($reader)
      ->test(ReaderDashboard::class)
      ->set('name', 'Updated Reader Name')
      ->set('email', 'updated_reader@example.com')
      ->call('updateProfile')
      ->assertHasNoErrors()
      ->assertDispatched('toast', message: 'Profile updated successfully.');

    $this->assertDatabaseHas('users', [
      'id' => $reader->id,
      'name' => 'Updated Reader Name',
      'email' => 'updated_reader@example.com',
    ]);
  }

  /**
   * Test TipTap Split Preview component states.
   */
  public function test_tiptap_split_preview_editor_modes(): void
  {
    $writer = User::factory()->create(['role' => 'writer']);
    $category = Category::create(['name' => 'General', 'slug' => 'general']);

    Livewire::actingAs($writer)
      ->test(ArticleForm::class)
      ->assertSet('editorMode', 'write')
      ->set('editorMode', 'split')
      ->assertSet('editorMode', 'split')
      ->set('editorMode', 'preview')
      ->assertSet('editorMode', 'preview');
  }

  /**
   * Test Advanced Editorial Analytics access control and calculations.
   */
  public function test_editorial_analytics_access_and_calculations(): void
  {
    $admin = User::factory()->create(['role' => 'admin']);
    $writer = User::factory()->create(['role' => 'writer']);
    $reader = User::factory()->create(['role' => 'reader']);

    // 1. Verify access control: readers and writers should be forbidden
    $this->actingAs($reader)->get(route('analytics.index'))->assertStatus(403);

    $this->actingAs($writer)->get(route('analytics.index'))->assertStatus(403);

    // 2. Admin should access successfully
    $category = Category::create(['name' => 'News', 'slug' => 'news']);

    // Create 2 published posts with different content lengths
    $post1 = Post::factory()->create([
      'category_id' => $category->id,
      'user_id' => $writer->id,
      'status' => 'published',
      'title' => 'Short Article',
      'content' => 'Short text content for testing.', // 5 words -> reading time 1 min
    ]);

    $post2 = Post::factory()->create([
      'category_id' => $category->id,
      'user_id' => $writer->id,
      'status' => 'published',
      'title' => 'Long Article',
      // Generate a string with about 300 words to test reading time -> 300 / 200 = 1.5 -> ceil = 2 mins
      'content' => str_repeat('word ', 300),
    ]);

    // Create a draft post - should be ignored by reading time and engagement score
    $draftPost = Post::factory()->create([
      'category_id' => $category->id,
      'user_id' => $writer->id,
      'status' => 'draft',
      'title' => 'Draft Article',
      'content' => str_repeat('word ', 1000),
    ]);

    // Add some bookmarks & comments to test engagement
    // Post 1: 1 bookmark, 2 comments -> (1 * 2) + 2 = 4 engagement
    $reader1 = User::factory()->create(['role' => 'reader']);
    $reader2 = User::factory()->create(['role' => 'reader']);

    $reader1->bookmarks()->create(['post_id' => $post1->id]);
    Comment::create([
      'post_id' => $post1->id,
      'user_id' => $reader1->id,
      'content' => 'First test comment text here.',
      'active' => true,
    ]);
    Comment::create([
      'post_id' => $post1->id,
      'user_id' => $reader2->id,
      'content' => 'Second test comment text here.',
      'active' => true,
    ]);

    // Post 2: 2 bookmarks, 0 comments -> (2 * 2) + 0 = 4 engagement
    $reader1->bookmarks()->create(['post_id' => $post2->id]);
    $reader2->bookmarks()->create(['post_id' => $post2->id]);

    // Request analytics page as admin
    $response = $this->actingAs($admin)->get(route('analytics.index'))->assertStatus(200);

    // Verify view has correct calculation data
    // Avg Reading time: post1 is 1 min, post2 is 2 mins. Average is (1 + 2) / 2 = 1.5 -> ceil to 2 mins.
    $response->assertViewHas('avgReadingTime', 2);

    // Avg Engagement: post1 engagement = 4, post2 engagement = 4. Average is (4 + 4) / 2 = 4.0
    $response->assertViewHas('avgEngagement', 4.0);

    // Verify activity feed has comments, users, and posts
    $response->assertViewHas('activityFeed');
    $activityFeed = $response->viewData('activityFeed');

    $this->assertNotEmpty($activityFeed);
  }

  /**
   * Test that admin can access the reader dashboard.
   */
  public function test_admin_can_access_reader_dashboard(): void
  {
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->get('/my/dashboard');

    $response->assertStatus(200);
  }
}
