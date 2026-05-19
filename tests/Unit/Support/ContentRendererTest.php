<?php

namespace Tests\Unit\Support;

use App\Support\ContentRenderer;
use PHPUnit\Framework\TestCase;

class ContentRendererTest extends TestCase
{
  public function test_it_returns_empty_string_for_empty_content(): void
  {
    $this->assertEquals('', ContentRenderer::render(''));
    $this->assertEquals('', ContentRenderer::render(null));
  }

  public function test_it_renders_legacy_html_safely(): void
  {
    $html = '<p>Hello <strong>World</strong> <script>alert("hack")</script></p>';
    $expected = '<p>Hello <strong>World</strong> alert("hack")</p>';
    $this->assertEquals($expected, ContentRenderer::render($html));
  }

  public function test_it_converts_markdown_inline_code_to_code_tags(): void
  {
    $html = 'This is `code` inline.';
    $expected = 'This is <code>code</code> inline.';
    $this->assertEquals($expected, ContentRenderer::render($html));
  }

  public function test_it_renders_tiptap_json_to_html(): void
  {
    $tiptapJson = json_encode([
      'type' => 'doc',
      'content' => [
        [
          'type' => 'heading',
          'attrs' => ['level' => 1],
          'content' => [['type' => 'text', 'text' => 'My Title']],
        ],
        [
          'type' => 'paragraph',
          'content' => [
            [
              'type' => 'text',
              'text' => 'This is bold and italic.',
              'marks' => [['type' => 'bold'], ['type' => 'italic']],
            ],
          ],
        ],
      ],
    ]);

    $html = ContentRenderer::render($tiptapJson);

    $this->assertStringContainsString(
      '<h1 class="text-3xl md:text-4xl font-black text-foreground mb-6 mt-10">My Title</h1>',
      $html,
    );
    $this->assertStringContainsString(
      '<p class="mb-4 text-muted-foreground leading-relaxed">',
      $html,
    );
    $this->assertStringContainsString(
      '<em class="italic text-foreground"><strong class="font-bold text-foreground">This is bold and italic.</strong></em>',
      $html,
    );
  }
}
