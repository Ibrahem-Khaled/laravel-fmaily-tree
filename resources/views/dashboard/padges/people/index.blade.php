{{-- resources/views/dashboard/padges/people.blade.php --}}
@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* لمسات بسيطة فقط */
        .chip {
            display: inline-flex;
            align-items: center;
            padding: .35rem .6rem;
            border-radius: 50rem;
            background: #f8f9fa;
            margin: .25rem;
            font-weight: 500;
            border: 1px solid #e9ecef;
        }

        .chip .x {
            border: none;
            background: transparent;
            margin-inline-start: .5rem;
            font-size: 1rem;
            line-height: 1;
            cursor: pointer
        }

        .scroll-box {
            max-height: 460px;
            overflow: auto
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .text-muted small,
        small.text-muted {
            font-size: .85rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- العنوان والمسار --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 mb-1">إدارة الأشخاص لشارة: <span class="text-primary">{{ $padge->name }}</span></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('padges.index') }}">الشارات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إدارة الأشخاص</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('padges.index') }}" class="btn btn-outline-secondary">
                الرجوع للشارات
            </a>
        </div>

        @include('components.alerts')

        <div class="row">
            {{-- العمود الأيسر: البحث والإضافة --}}
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex align-items-center">
                        <span class="font-weight-bold text-primary">بحث وإضافة أشخاص</span>
                        <div class="ml-auto">
                            <small id="resultMeta" class="text-muted"></small>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- حقل البحث (Bootstrap input-group) --}}
                        <div class="form-group">
                            <label class="mb-2">فلترة بالاسم / الإيميل / الهاتف</label>
                            <div class="input-group">
                                <input id="q" type="text" class="form-control" placeholder="اكتب للبحث..."
                                    autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="state.page=1;fetchResults()">بحث</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">اتركه فارغًا لعرض أول صفحة من النتائج.</small>
                        </div>

                        {{-- النتائج --}}
                        <div class="scroll-box border rounded">
                            <ul id="results" class="list-group list-group-flush">
                                <li class="list-group-item text-muted">ابدأ بالكتابة أو اضغط "بحث" لعرض النتائج.</li>
                            </ul>
                        </div>

                        {{-- ترقيم AJAX --}}
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <button id="prevBtn" class="btn btn-outline-secondary btn-sm" disabled>السابق</button>
                            <small class="text-muted">صفحة <span id="pageNow">1</span> من <span
                                    id="pageTotal">1</span></small>
                            <button id="nextBtn" class="btn btn-outline-secondary btn-sm" disabled>التالي</button>
                        </div>

                        <hr>

                        {{-- المختارون + إرسال --}}
                        <div class="d-flex align-items-center mb-2">
                            <span class="font-weight-bold text-primary">الأشخاص المختارون</span>
                            <button id="clearAll" class="btn btn-link btn-sm ml-auto p-0">مسح الكل</button>
                        </div>

                        <div id="selectedWrap" class="mb-3" style="min-height:48px">
                            <div class="text-muted">لم تختر أحدًا بعد.</div>
                        </div>

                        <form id="attachForm" action="{{ route('padges.people.attach', $padge) }}" method="POST"
                            class="border-top pt-3">
                            @csrf
                            <div id="hiddenInputs"></div>
                            <button type="submit" class="btn btn-primary">
                                إضافة جميع المختارين
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- العمود الأيمن: المرتبطون حالياً --}}
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <span class="font-weight-bold text-primary">الأشخاص المرتبطون حالياً</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>الاسم</th>
                                        <th style="width:140px">الحالة</th>
                                        <th style="width:220px">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="linkedTableBody">
                                    @forelse($people as $i => $person)
                                        <tr data-person-id="{{ $person->id }}">
                                            <td>{{ $people->firstItem() + $i }}</td>
                                            <td class="font-weight-500">{{ $person->full_name }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $person->pivot->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $person->pivot->is_active ? 'مُفعّل' : 'مُعطّل' }}
                                                </span>
                                            </td>
                                            <td class="d-flex align-items-center">
                                                <button type="button" class="btn btn-warning btn-sm mr-2 js-toggle"
                                                    data-url="{{ route('padges.people.toggle', [$padge, $person]) }}">
                                                    تبديل الحالة
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm js-detach"
                                                    data-url="{{ route('padges.people.detach', [$padge, $person]) }}">
                                                    إزالة
                                                </button>

                                                {{-- Fallback لو JS مقفول --}}
                                                <noscript>
                                                    <form action="{{ route('padges.people.toggle', [$padge, $person]) }}"
                                                        method="POST" class="mr-2 d-inline-block">
                                                        @csrf @method('PATCH')
                                                        <button class="btn btn-warning btn-sm">تبديل الحالة</button>
                                                    </form>
                                                    <form action="{{ route('padges.people.detach', [$padge, $person]) }}"
                                                        method="POST" class="d-inline-block"
                                                        onsubmit="return confirm('متأكد من إزالة هذا الشخص من الشارة؟')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">إزالة</button>
                                                    </form>
                                                </noscript>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">لا يوجد أشخاص مرتبطون
                                                بعد.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ترقيم تقليدي للجزء الأيمن --}}
                        <div class="p-3 d-flex justify-content-center">
                            {{ $people->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- /row --}}
    </div> {{-- /container-fluid --}}
@endsection

@push('scripts')
    <script>
        /* ================= إعداد عام ================= */
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const qInput = document.getElementById('q');
        const resultsEl = document.getElementById('results');
        const resultMetaEl = document.getElementById('resultMeta');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pageNowEl = document.getElementById('pageNow');
        const pageTotalEl = document.getElementById('pageTotal');

        const selectedWrap = document.getElementById('selectedWrap');
        const hiddenInputs = document.getElementById('hiddenInputs');
        const clearAllBtn = document.getElementById('clearAll');

        let state = {
            q: '',
            page: 1,
            lastPage: 1,
            perPage: 12,
            selected: new Map(), // id -> {id, full_name, email}
            loading: false,
        };

        let tId;
        const debounce = (fn, ms = 300) => (...args) => {
            clearTimeout(tId);
            tId = setTimeout(() => fn(...args), ms);
        };

        /* ================ البحث والنتائج (AJAX) ================ */
        function buildSearchUrl() {
            const params = new URLSearchParams();
            if (state.q) params.set('q', state.q);
            params.set('page', state.page);
            params.set('per_page', state.perPage);
            Array.from(state.selected.keys()).forEach(id => params.append('exclude[]', id));
            return `{{ route('padges.people.search', $padge) }}?${params.toString()}`;
        }

        async function fetchResults() {
            state.loading = true;
            resultsEl.innerHTML = '<li class="list-group-item text-muted">جاري التحميل...</li>';
            try {
                const res = await fetch(buildSearchUrl(), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();

                renderResults(json.data || []);
                const meta = json.meta || {
                    current_page: 1,
                    last_page: 1,
                    total: 0
                };
                state.page = meta.current_page;
                state.lastPage = meta.last_page;

                pageNowEl.textContent = meta.current_page;
                pageTotalEl.textContent = meta.last_page;
                resultMetaEl.textContent = `إجمالي النتائج: ${meta.total}`;

                prevBtn.disabled = (state.page <= 1);
                nextBtn.disabled = (state.page >= state.lastPage);
            } catch (e) {
                resultsEl.innerHTML = '<li class="list-group-item text-danger">تعذر الجلب، حاول مجددًا.</li>';
            } finally {
                state.loading = false;
            }
        }

        function renderResults(items) {
            if (!items.length) {
                resultsEl.innerHTML = '<li class="list-group-item text-muted">لا توجد نتائج.</li>';
                return;
            }
            resultsEl.innerHTML = '';
            items.forEach(p => {
                const exists = state.selected.has(p.id);
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center justify-content-between';
                li.innerHTML = `
            <div>
                <div class="font-weight-bold">${p.full_name}</div>
                <div class="small text-muted">${p.email ?? ''}</div>
            </div>
            <button class="btn btn-sm ${exists ? 'btn-secondary' : 'btn-outline-primary'}" ${exists ? 'disabled' : ''}>
                ${exists ? 'مضاف' : 'إضافة'}
            </button>
        `;
                li.querySelector('button').addEventListener('click', () => addSelected(p));
                resultsEl.appendChild(li);
            });
        }

        /* ================ إدارة المختارين محلياً ================ */
        function addSelected(p) {
            if (state.selected.has(p.id)) return;
            state.selected.set(p.id, p);
            renderSelected();
            fetchResults(); // استبعاد المضاف من القائمة
        }

        function removeSelected(id) {
            state.selected.delete(id);
            renderSelected();
            fetchResults();
        }

        function renderSelected() {
            selectedWrap.innerHTML = '';
            hiddenInputs.innerHTML = '';

            if (state.selected.size === 0) {
                selectedWrap.innerHTML = '<div class="text-muted">لم تختر أحدًا بعد.</div>';
                return;
            }

            state.selected.forEach((p, id) => {
                const chip = document.createElement('span');
                chip.className = 'chip';
                chip.innerHTML = `${p.full_name}<button type="button" class="x" aria-label="إزالة">×</button>`;
                chip.querySelector('.x').addEventListener('click', () => removeSelected(id));
                selectedWrap.appendChild(chip);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'people[]';
                input.value = id;
                hiddenInputs.appendChild(input);
            });
        }

        clearAllBtn.addEventListener('click', () => {
            state.selected.clear();
            renderSelected();
            fetchResults();
        });

        /* ================ إرسال الإضافة (AJAX) ================ */
        document.getElementById('attachForm').addEventListener('submit', async function(e) {
            if (state.selected.size === 0) return; // ممكن تسمح بإرسال فارغ حسب رغبتك
            e.preventDefault();

            const form = this;
            const payload = new FormData(form); // يحتوي people[]

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: payload
                });
                const json = await res.json();

                if (json.ok) {
                    state.selected.clear();
                    renderSelected();
                    fetchResults();
                    // توست بسيط
                    alert(json.message || 'تمت الإضافة.');
                    // بإمكانك هنا إعادة رسم جدول اليمين عبر Endpoint HTML إن رغبت
                } else {
                    alert(json.message || 'تعذر الإضافة.');
                }
            } catch (e) {
                alert('تعذر الإضافة.');
            }
        });

        /* ================ تبديل/إزالة (AJAX لجدول اليمين) ================ */
        document.addEventListener('click', async function(e) {
            const t = e.target.closest('.js-toggle, .js-detach');
            if (!t) return;

            const url = t.getAttribute('data-url');
            const isToggle = t.classList.contains('js-toggle');
            const method = isToggle ? 'PATCH' : 'DELETE';

            if (!isToggle && !confirm('متأكد من إزالة هذا الشخص من الشارة؟')) return;

            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();

                if (json.ok) {
                    const row = t.closest('tr');
                    if (isToggle) {
                        const badge = row.querySelector('.badge');
                        const active = json.is_active;
                        badge.className = 'badge ' + (active ? 'badge-success' : 'badge-secondary');
                        badge.textContent = active ? 'مُفعّل' : 'مُعطّل';
                    } else {
                        row.parentNode.removeChild(row);
                    }
                } else {
                    alert(json.message || 'تعذر التنفيذ.');
                }
            } catch (err) {
                alert('تعذر الاتصال.');
            }
        });

        /* ================ بدء التشغيل ================ */
        qInput.addEventListener('input', debounce(() => {
            state.q = qInput.value.trim();
            state.page = 1;
            fetchResults();
        }, 300));
        prevBtn.addEventListener('click', () => {
            if (state.page > 1) {
                state.page--;
                fetchResults();
            }
        });
        nextBtn.addEventListener('click', () => {
            if (state.page < state.lastPage) {
                state.page++;
                fetchResults();
            }
        });
        renderSelected();
        fetchResults();
    </script>
@endpush
