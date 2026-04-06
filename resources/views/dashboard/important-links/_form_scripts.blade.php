@push('scripts')
<script>
    function importantLinkAppendMediaRow(containerEl) {
        const tpl = document.getElementById('importantLinkMediaRowTpl');
        if (!tpl || !containerEl) return;
        const node = tpl.content.cloneNode(true);
        containerEl.appendChild(node);
        const row = containerEl.lastElementChild;
        row.querySelector('.remove-media-row').addEventListener('click', function() {
            row.remove();
        });
    }

    function syncImportantLinkFormType() {
        const type = document.getElementById('type').value;
        const appBox = document.getElementById('app_urls_box');
        const urlInput = document.getElementById('url');
        const mark = document.getElementById('url_required_mark');
        const hw = document.getElementById('url_hint_website');
        const ha = document.getElementById('url_hint_app');
        if (type === 'app') {
            appBox.classList.remove('d-none');
            urlInput.removeAttribute('required');
            if (mark) mark.classList.add('d-none');
            if (hw) hw.classList.add('d-none');
            if (ha) ha.classList.remove('d-none');
        } else {
            appBox.classList.add('d-none');
            urlInput.setAttribute('required', 'required');
            if (mark) mark.classList.remove('d-none');
            if (hw) hw.classList.remove('d-none');
            if (ha) ha.classList.add('d-none');
        }
    }

    document.getElementById('type')?.addEventListener('change', syncImportantLinkFormType);
    document.getElementById('addMediaRowBtn')?.addEventListener('click', function() {
        importantLinkAppendMediaRow(document.getElementById('media_new_container'));
    });

    document.addEventListener('DOMContentLoaded', function() {
        syncImportantLinkFormType();
        const d = document.getElementById('description');
        const cc = document.getElementById('charCount');
        if (d && cc) {
            cc.textContent = String(d.value || '').length;
            d.addEventListener('input', function() {
                cc.textContent = this.value.length;
            });
        }
    });
</script>
@endpush
