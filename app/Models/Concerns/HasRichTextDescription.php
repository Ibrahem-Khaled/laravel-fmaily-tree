<?php

namespace App\Models\Concerns;

use App\Support\Html\RichTextSanitizer;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Mark a model's `description` column as Summernote-edited rich-text.
 *
 * The setter funnels every write (mass-assign, update, fill, etc.) through
 * `RichTextSanitizer::clean()` so:
 *  - dangerous HTML is stripped before the value hits the database, and
 *  - "visually empty" editor output (`<p><br></p>`) is normalized to NULL.
 *
 * Apply this trait on any model with a rich-text `description` column —
 * controllers no longer need to remember to sanitize.
 */
trait HasRichTextDescription
{
    protected function description(): Attribute
    {
        return Attribute::set(
            fn (?string $value): ?string => RichTextSanitizer::clean($value)
        );
    }
}
