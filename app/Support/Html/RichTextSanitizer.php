<?php

namespace App\Support\Html;

use Mews\Purifier\Facades\Purifier;

/**
 * Single entry point for sanitizing rich-text HTML written through the
 * Summernote editor (events, councils, and future fields).
 *
 * Two responsibilities:
 *  1. Run the HTML through HTMLPurifier's `rich-content` profile so that
 *     anything outside the Summernote toolbar's vocabulary (scripts, event
 *     handlers, dangerous iframes, etc.) is stripped before persistence.
 *  2. Normalize "visually empty" editor output (`<p><br></p>`, `&nbsp;`,
 *     pure whitespace) to a real `null`, so the database doesn't end up
 *     with rows that look empty in the UI but are truthy in PHP.
 */
class RichTextSanitizer
{
    /**
     * The Purifier profile defined in `config/purifier.php`.
     */
    public const PROFILE = 'rich-content';

    /**
     * Sanitize untrusted HTML coming from the rich-text editor.
     *
     * Returns `null` for both real null input and content that is empty
     * once tags and non-breaking spaces are stripped (and that does not
     * carry any media — images/iframes are considered meaningful even if
     * the text content is empty).
     */
    public static function clean(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $trimmed = trim($html);
        if ($trimmed === '') {
            return null;
        }

        $clean = self::runPurifier($trimmed);

        if (self::isVisuallyEmpty($clean)) {
            return null;
        }

        return $clean;
    }

    /**
     * Run HTMLPurifier while neutralizing the harmless E_USER_WARNING noise
     * it emits when it encounters CSS properties outside its built-in
     * `CSSDefinition::$info` map (e.g. `direction`, `float`, `position`).
     *
     * Without this guard, Laravel's `HandleExceptions` bootstrapper promotes
     * those warnings to an `ErrorException` and the whole request 500s — even
     * though HTMLPurifier just wanted to tell us "I'm dropping this property
     * because I don't model it". Dropping the property is exactly the
     * behavior we want, so we silence those specific warnings only and let
     * every other PHP error bubble up untouched.
     */
    protected static function runPurifier(string $html): string
    {
        $previous = set_error_handler(
            static function (int $severity, string $message, string $file, int $line) use (&$previous): bool {
                if ($severity === E_USER_WARNING
                    && str_starts_with($message, 'Style attribute')
                    && str_contains($message, 'is not supported')
                ) {
                    return true; // swallow only the unsupported-CSS warning
                }

                return is_callable($previous)
                    ? (bool) call_user_func($previous, $severity, $message, $file, $line)
                    : false;
            }
        );

        try {
            return Purifier::clean($html, self::PROFILE);
        } finally {
            restore_error_handler();
        }
    }

    /**
     * True when the sanitized HTML carries no user-visible content
     * (no text, no media). Used to coerce Summernote's `<p><br></p>` and
     * friends to `null`.
     */
    protected static function isVisuallyEmpty(string $html): bool
    {
        if (stripos($html, '<img') !== false
            || stripos($html, '<iframe') !== false
            || stripos($html, '<video') !== false) {
            return false;
        }

        $textOnly = str_replace(['&nbsp;', "\xC2\xA0"], ' ', $html);
        $textOnly = trim(strip_tags($textOnly));

        return $textOnly === '';
    }
}
