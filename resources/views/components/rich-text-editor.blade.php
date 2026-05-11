@props([
    /* Required: the form field name. Used as default for id + error key. */
    'name',
    /* Override the DOM id if it must differ from the field name (e.g. edit modal). */
    'id' => null,
    /* Value to pre-fill. Defaults to old() lookup so callers don't have to repeat it. */
    'value' => null,
    /* Field label. Pass `null` (or an empty string) to suppress the <label>. */
    'label' => null,
    /* Placeholder shown by Summernote when the editor is empty. */
    'placeholder' => null,
    /* Editor pixel height. */
    'height' => 200,
    /* Visible rows of the fallback <textarea> (before Summernote boots). */
    'rows' => 4,
    /* Validation error bag key. Defaults to $name. */
    'error' => null,
    /* Optional help text rendered under the editor. */
    'help' => null,
    /* When true, the page is responsible for calling
       window.initRichTextEditor() itself (e.g. modal `shown.bs.modal`). */
    'defer' => false,
    /* When true, mark the field required (red asterisk + aria/required attr). */
    'required' => false,
])

@php
    $resolvedId    = $id ?? $name;
    $errorKey      = $error ?? $name;
    $resolvedValue = $value ?? old($name);
@endphp

<div class="form-group">
    @if(!empty($label))
        <label for="{{ $resolvedId }}" class="font-weight-bold">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $resolvedId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        class="form-control rich-text-editor @error($errorKey) is-invalid @enderror"
        data-rich-text-editor
        data-height="{{ $height }}"
    >{{ $resolvedValue }}</textarea>

    @if($help)
        <small class="form-text text-muted">{!! $help !!}</small>
    @endif

    @error($errorKey)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

@pushOnce('styles')
    <link
        href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css"
        rel="stylesheet">
    <style>
        /* Make Summernote behave properly inside RTL Bootstrap modals. */
        .note-editor .note-editing-area .note-editable { text-align: right; direction: rtl; }
        .note-editor.note-frame { border-radius: 0.375rem; }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
    <script>
        (function () {
            if (window.initRichTextEditor) {
                return; // already wired up by a previous instance on the page
            }

            var DEFAULT_OPTIONS = {
                height: 200,
                direction: 'rtl',
                placeholder: '',
                toolbar: [
                    ['style',    ['style']],
                    ['font',     ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color',    ['color']],
                    ['para',     ['ul', 'ol', 'paragraph']],
                    ['table',    ['table']],
                    ['insert',   ['link', 'picture', 'video']],
                    ['view',     ['fullscreen', 'codeview', 'help']]
                ]
            };

            window.initRichTextEditor = function (selector, options) {
                var $el = $(selector);
                if (!$el.length) {
                    return $();
                }
                if ($el.next('.note-editor').length) {
                    return $el; // already initialized — keep it idempotent
                }
                var merged = $.extend({}, DEFAULT_OPTIONS, options || {});
                if (!('height' in (options || {}))) {
                    var dataHeight = parseInt($el.data('height'), 10);
                    if (!isNaN(dataHeight) && dataHeight > 0) {
                        merged.height = dataHeight;
                    }
                }
                if (!('placeholder' in (options || {}))) {
                    var ph = $el.attr('placeholder');
                    if (ph) {
                        merged.placeholder = ph;
                    }
                }
                $el.summernote(merged);
                return $el;
            };

            window.destroyRichTextEditor = function (selector) {
                var $el = $(selector);
                if ($el.next('.note-editor').length) {
                    $el.summernote('destroy');
                }
                return $el;
            };

            /**
             * Sync editor content back to the underlying textarea and clear
             * "visually empty" output before submit so the server stores NULL
             * instead of `<p><br></p>`.
             */
            window.syncRichTextEditor = function (selector) {
                var $el = $(selector);
                if (!$el.next('.note-editor').length) {
                    return $el;
                }
                if ($el.summernote('isEmpty')) {
                    $el.val('');
                } else {
                    $el.val($el.summernote('code'));
                }
                return $el;
            };

            $(function () {
                $('textarea[data-rich-text-editor]').each(function () {
                    if ($(this).closest('.modal').length) {
                        return; // modal-hosted editors init on `shown.bs.modal`
                    }
                    window.initRichTextEditor('#' + this.id);
                });

                // Always sync editors back to their textarea on form submit, so
                // empty editors post an empty string (the server-side sanitizer
                // then normalizes that to NULL).
                $(document).on('submit', 'form', function () {
                    $(this).find('textarea[data-rich-text-editor]').each(function () {
                        window.syncRichTextEditor('#' + this.id);
                    });
                });
            });
        })();
    </script>
@endPushOnce
