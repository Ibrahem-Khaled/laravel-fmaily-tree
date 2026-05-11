<?php

namespace Tests\Feature\Dashboard;

use App\Models\FamilyCouncil;
use App\Models\FamilyEvent;
use App\Support\Html\RichTextSanitizer;
use Tests\TestCase;

/**
 * Locks in the contract of RichTextSanitizer + HasRichTextDescription:
 *   - dangerous HTML stripped before persistence
 *   - Summernote's "visually empty" output normalized to NULL
 *   - allowed formatting tags survive intact
 *
 * These tests intentionally do not hit the database — they exercise the
 * sanitizer directly and the model mutator on an in-memory instance.
 */
class RichTextSanitizerTest extends TestCase
{
    public function test_clean_strips_script_tags(): void
    {
        $dirty = '<p>hello</p><script>alert(1)</script>';
        $clean = RichTextSanitizer::clean($dirty);

        $this->assertNotNull($clean);
        $this->assertStringNotContainsString('<script', (string) $clean);
        $this->assertStringNotContainsString('alert(1)', (string) $clean);
        $this->assertStringContainsString('hello', (string) $clean);
    }

    public function test_clean_strips_inline_event_handlers(): void
    {
        $dirty = '<p onclick="alert(1)">click me</p><a href="javascript:alert(1)">x</a>';
        $clean = RichTextSanitizer::clean($dirty);

        $this->assertNotNull($clean);
        $this->assertStringNotContainsString('onclick', (string) $clean);
        $this->assertStringNotContainsString('javascript:', (string) $clean);
    }

    public function test_clean_preserves_allowed_formatting(): void
    {
        $html = '<p><strong>bold</strong> and <em>italic</em> with <a href="https://example.com">link</a></p>';
        $clean = RichTextSanitizer::clean($html);

        $this->assertNotNull($clean);
        $this->assertStringContainsString('<strong>bold</strong>', (string) $clean);
        $this->assertStringContainsString('<em>italic</em>', (string) $clean);
        $this->assertStringContainsString('href="https://example.com"', (string) $clean);
    }

    public function test_clean_preserves_lists_and_tables(): void
    {
        $html = '<ul><li>one</li><li>two</li></ul><table><tr><td>a</td><td>b</td></tr></table>';
        $clean = RichTextSanitizer::clean($html);

        $this->assertNotNull($clean);
        $this->assertStringContainsString('<ul>', (string) $clean);
        $this->assertStringContainsString('<li>one</li>', (string) $clean);
        $this->assertStringContainsString('<table', (string) $clean);
        $this->assertStringContainsString('<td>a</td>', (string) $clean);
    }

    public function test_clean_normalizes_summernote_empty_to_null(): void
    {
        $this->assertNull(RichTextSanitizer::clean('<p><br></p>'));
        $this->assertNull(RichTextSanitizer::clean('<p>&nbsp;</p>'));
        $this->assertNull(RichTextSanitizer::clean('   '));
        $this->assertNull(RichTextSanitizer::clean(''));
        $this->assertNull(RichTextSanitizer::clean(null));
    }

    public function test_clean_keeps_image_only_content(): void
    {
        $html = '<p><img src="https://example.com/a.png" alt="x"></p>';
        $clean = RichTextSanitizer::clean($html);

        $this->assertNotNull($clean);
        $this->assertStringContainsString('<img', (string) $clean);
    }

    public function test_clean_keeps_base64_data_uri_images(): void
    {
        // Summernote embeds uploaded pictures as base64 data: URIs.
        $tinyPng = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
        $html = '<p><img src="data:image/png;base64,' . $tinyPng . '" alt="x"></p>';
        $clean = RichTextSanitizer::clean($html);

        $this->assertNotNull($clean);
        $this->assertStringContainsString('data:image/png;base64', (string) $clean);
    }

    public function test_clean_does_not_throw_on_unsupported_css_property(): void
    {
        // Summernote inlines `direction: ltr` (and other CSS that
        // HTMLPurifier does not natively model) on images and inline
        // elements when the editor sits in an RTL document. Without
        // RichTextSanitizer::runPurifier()'s warning guard, this would
        // throw an ErrorException via Laravel's error handler.
        $html = '<p>تجربة <img src="https://example.com/x.png" '
              . 'style="width: 128.903px; direction: ltr; float: left;" alt="x">.</p>';

        $clean = RichTextSanitizer::clean($html);

        $this->assertNotNull($clean);
        $this->assertStringContainsString('<img', (string) $clean);
        $this->assertStringContainsString('تجربة', (string) $clean);
        // Unsupported CSS gets silently dropped by HTMLPurifier — which is
        // exactly the behavior we want and the warning we suppressed.
        $this->assertStringNotContainsString('direction:', (string) $clean);
    }

    public function test_family_event_description_mutator_sanitizes_and_normalizes(): void
    {
        $event = new FamilyEvent();

        $event->description = '<p>hi</p><script>alert(1)</script>';
        $this->assertStringNotContainsString('<script', (string) $event->description);
        $this->assertStringContainsString('hi', (string) $event->description);

        $event->description = '<p><br></p>';
        $this->assertNull($event->description);
    }

    public function test_family_council_description_mutator_sanitizes_and_normalizes(): void
    {
        $council = new FamilyCouncil();

        $council->description = '<p>hello</p><iframe src="https://evil.example.com"></iframe>';
        $clean = (string) $council->description;
        $this->assertStringContainsString('hello', $clean);
        $this->assertStringNotContainsString('evil.example.com', $clean);

        $council->description = '   ';
        $this->assertNull($council->description);
    }
}
