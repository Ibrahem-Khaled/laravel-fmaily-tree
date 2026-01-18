@once
    <script>
        (function() {
            const wivesUrlTemplate = @json(route('people.getWives', ['father' => '__FATHER__']));

            function buildUrl(fatherId) {
                return wivesUrlTemplate.replace('__FATHER__', encodeURIComponent(String(fatherId)));
            }

            function findMotherSelect(fatherEl) {
                // Prefer closest modal content (most precise for modals)
                const modalContent = fatherEl.closest('.modal-content');
                if (modalContent) {
                    const inModal = modalContent.querySelector('.js-mother-select');
                    if (inModal) return inModal;
                }

                // Then same row (create/edit pages typically)
                const row = fatherEl.closest('.row');
                if (row) {
                    const inRow = row.querySelector('.js-mother-select');
                    if (inRow) return inRow;
                }

                // Fallback to same form
                const form = fatherEl.closest('form');
                if (form) {
                    const inForm = form.querySelector('.js-mother-select');
                    if (inForm) return inForm;
                }

                return null;
            }

            function setMotherOptions(motherEl, options, selectedValue) {
                const $mother = window.jQuery ? window.jQuery(motherEl) : null;
                const hasSelect2 = !!($mother && $mother.data('select2'));

                if (hasSelect2) {
                    $mother.empty();
                    options.forEach(function(opt) {
                        $mother.append(new Option(opt.text, opt.value, false, false));
                    });
                    if (selectedValue) {
                        $mother.val(String(selectedValue));
                    } else {
                        $mother.val(null);
                    }
                    $mother.trigger('change.select2');
                } else {
                    motherEl.innerHTML = '';
                    options.forEach(function(opt) {
                        const option = document.createElement('option');
                        option.value = opt.value;
                        option.textContent = opt.text;
                        motherEl.appendChild(option);
                    });
                    if (selectedValue) motherEl.value = String(selectedValue);
                }
            }

            async function loadWivesForFather(fatherEl) {
                const fatherId = fatherEl.value;
                const last = fatherEl.getAttribute('data-last-father-id');
                if (last === String(fatherId)) return;
                fatherEl.setAttribute('data-last-father-id', String(fatherId));

                const motherEl = findMotherSelect(fatherEl);
                if (!motherEl) return;

                // preserve current mother selection (if any)
                const currentMother = motherEl.value;

                if (!fatherId) {
                    setMotherOptions(motherEl, [{
                        value: '',
                        text: '-- اختر الأب أولاً --'
                    }], '');
                    return;
                }

                setMotherOptions(motherEl, [{
                    value: '',
                    text: '-- جار التحميل --'
                }], '');

                try {
                    const res = await fetch(buildUrl(fatherId));
                    if (!res.ok) throw new Error('Request failed: ' + res.status);
                    const wives = await res.json();

                    if (!Array.isArray(wives) || wives.length === 0) {
                        setMotherOptions(motherEl, [{
                            value: '',
                            text: '-- لا توجد زوجات مسجلة --'
                        }], '');
                        return;
                    }

                    const options = [{
                        value: '',
                        text: '-- اختر الأم --'
                    }].concat(wives.map(function(w) {
                        return {
                            value: String(w.id),
                            text: w.full_name ?? w.first_name ?? ''
                        };
                    }));

                    // restore mother selection if still exists
                    const wifeIds = new Set(wives.map(w => String(w.id)));
                    const restore = currentMother && wifeIds.has(String(currentMother)) ? String(currentMother) : '';
                    setMotherOptions(motherEl, options, restore);
                } catch (e) {
                    console.error('Error fetching wives:', e);
                    setMotherOptions(motherEl, [{
                        value: '',
                        text: '-- حدث خطأ --'
                    }], '');
                }
            }

            function attachVanilla() {
                document.querySelectorAll('.js-father-select').forEach(function(el) {
                    el.addEventListener('change', function() {
                        loadWivesForFather(this);
                    });

                    // if value already selected (old input), load immediately
                    if (el.value) {
                        loadWivesForFather(el);
                    }
                });
            }

            function attachJQueryDelegation() {
                if (!window.jQuery) return false;

                const $doc = window.jQuery(document);
                $doc.on('change', '.js-father-select', function() {
                    loadWivesForFather(this);
                });
                $doc.on('select2:select select2:clear', '.js-father-select', function() {
                    // some setups fire select2 events without change reliably
                    loadWivesForFather(this);
                });

                // load for preselected fathers
                window.jQuery('.js-father-select').each(function() {
                    if (this.value) loadWivesForFather(this);
                });

                return true;
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Prefer jQuery delegation (works best with Select2 + modals)
                if (!attachJQueryDelegation()) {
                    attachVanilla();
                }
            });
        })();
    </script>
@endonce

