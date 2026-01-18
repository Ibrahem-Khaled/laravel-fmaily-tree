@php
    $selectedId = $selectedId ?? null;
    $selectedText = $selectedText ?? null;
@endphp

<select
    name="{{ $name }}"
    id="{{ $id }}"
    class="form-control js-person-search no-search"
    data-url="{{ $url }}"
    data-placeholder="{{ $placeholder }}"
    data-min-input="{{ $minInput }}"
    data-gender="{{ $gender }}"
    data-include-outside="{{ $includeOutside ? '1' : '0' }}"
    data-allow-clear="{{ $allowClear ? '1' : '0' }}"
>
    @if ($selectedId && $selectedText)
        <option value="{{ $selectedId }}" selected>{{ $selectedText }}</option>
    @endif
</select>

@once
    @push('scripts')
        <script>
            (function() {
                function initPersonSearchSelect(el) {
                    const $el = window.jQuery(el);
                    if ($el.data('personSearchInit')) return;
                    $el.data('personSearchInit', true);

                    const url = $el.data('url');
                    const placeholder = $el.data('placeholder') || 'ابحث عن شخص...';
                    const minInput = parseInt($el.data('minInput') || 2, 10);
                    const gender = $el.data('gender') || null;
                    const includeOutside = String($el.data('includeOutside')) === '1';
                    const allowClear = String($el.data('allowClear')) === '1';

                    const opts = {
                        placeholder: placeholder,
                        allowClear: allowClear,
                        width: '100%',
                        minimumInputLength: minInput,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                const payload = {
                                    term: params.term || '',
                                    page: params.page || 1,
                                    include_outside: includeOutside ? 1 : 0,
                                };
                                if (gender) payload.gender = gender;
                                return payload;
                            },
                            processResults: function(data) {
                                return data;
                            },
                            cache: true
                        }
                    };

                    const $modal = $el.closest('.modal');
                    if ($modal.length) {
                        opts.dropdownParent = $modal;
                    }

                    $el.select2(opts);
                }

                document.addEventListener('DOMContentLoaded', function() {
                    if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) return;
                    document.querySelectorAll('select.js-person-search').forEach(initPersonSearchSelect);
                });
            })();
        </script>
    @endpush
@endonce

