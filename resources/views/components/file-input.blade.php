@props(['name', 'id' => Str::uuid(), 'multiple' => false, 'label' => 'اختر ملفات...'])
<div class="custom-file">
    <input type="file" name="{{ $name }}{{ $multiple ? '[]' : '' }}" class="custom-file-input"
        id="{{ $id }}" {{ $multiple ? 'multiple' : '' }}>
    <label class="custom-file-label" for="{{ $id }}">{{ $label }}</label>
</div>
@pushOnce('scripts')
    <script>
        document.addEventListener('change', function(e) {
            const input = e.target.closest('.custom-file-input');
            if (!input) return;
            const files = input.files;
            let txt = '{{ $label }}';
            if (files?.length > 1) txt = `${files.length} ملفات تم اختيارها`;
            else if (files?.length === 1) txt = files[0].name;
            input.nextElementSibling.innerHTML = txt;
        });
    </script>
@endPushOnce
