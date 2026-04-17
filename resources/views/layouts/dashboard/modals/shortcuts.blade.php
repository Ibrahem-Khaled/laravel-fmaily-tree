@php
    use Illuminate\Support\Facades\Route;

    // Helper to safely get route URL
    $r = fn(string $name, array $params = []) => Route::has($name) ? route($name, $params) : '#';

    $shortcuts = [
        // الأساسية
        ['href' => $r('dashboard'),              'icon' => 'fe-pie-chart',     'color' => 'bg-success',   'label' => 'الإحصائيات'],
        ['href' => Route::has('home') ? route('home') : url('/'), 'icon' => 'fe-external-link', 'color' => 'bg-info', 'label' => 'عرض الموقع', 'blank' => true],

        // إدارة الأشخاص
        ['href' => Route::has('people.index') ? route('people.index') : '#', 'icon' => 'fe-git-merge', 'color' => 'bg-warning', 'label' => 'شجرة العائلة'],
        ['href' => Route::has('users.index') ? route('users.index') : '#', 'icon' => 'fe-user-check', 'color' => 'bg-primary', 'label' => 'المستخدمون'],
        ['href' => Route::has('marriages.index') ? route('marriages.index') : '#', 'icon' => 'fe-heart', 'color' => 'bg-danger', 'label' => 'الزيجات'],

        // المحتوى والفعاليات
        ['href' => $r('dashboard.family-news.index'),    'icon' => 'fe-rss',          'color' => 'bg-primary',   'label' => 'أخبار العائلة'],
        ['href' => $r('dashboard.events.index'),         'icon' => 'fe-calendar',     'color' => 'bg-info',      'label' => 'الفعاليات'],
        ['href' => $r('dashboard.images.index'),         'icon' => 'fe-image',        'color' => 'bg-secondary', 'label' => 'معرض الصور'],
        ['href' => $r('dashboard.competitions.index'),       'icon' => 'fe-zap',          'color' => 'bg-warning',   'label' => 'المسابقات'],

        // الإعدادات والنظام
        ['href' => $r('dashboard.site-content.index'),   'icon' => 'fe-edit-3',       'color' => 'bg-secondary', 'label' => 'محتوى الموقع'],
        ['href' => $r('dashboard.notifications.index'),  'icon' => 'fe-bell',         'color' => 'bg-danger',    'label' => 'الإشعارات'],
        ['href' => $r('dashboard.visit-logs.index'),     'icon' => 'fe-eye',          'color' => 'bg-dark',      'label' => 'سجل الزيارات'],
    ];

    // Filter out shortcuts that have no route (#)
    $validShortcuts = array_values(array_filter($shortcuts, fn($s) => $s['href'] !== '#'));
@endphp

<div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="shortcutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shortcutModalLabel">
                    <i class="fe fe-grid fe-16 mr-2"></i>
                    اختصارات لوحة التحكم
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4 pb-4">
                {{-- Search inside shortcuts --}}
                <div class="mb-3">
                    <input
                        type="text"
                        id="shortcutSearch"
                        class="form-control form-control-sm"
                        placeholder="ابحث عن قسم..."
                        oninput="filterShortcuts(this.value)"
                    >
                </div>

                <div class="row align-items-start" id="shortcutsGrid">
                    @foreach ($validShortcuts as $sc)
                    <div class="col-4 text-center mb-3 shortcut-item" data-label="{{ $sc['label'] }}">
                        <a href="{{ $sc['href'] }}"
                           class="text-decoration-none text-body d-block shortcut-link"
                           @if(!empty($sc['blank'])) target="_blank" rel="noopener noreferrer" @endif>
                            <div class="squircle {{ $sc['color'] }} justify-content-center mx-auto">
                                <i class="fe {{ $sc['icon'] }} fe-24 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1 small font-weight-medium shortcut-label">{{ $sc['label'] }}</p>
                        </a>
                    </div>
                    @endforeach

                    {{-- Empty state when search returns nothing --}}
                    <div class="col-12 text-center py-4 d-none" id="shortcutEmpty">
                        <i class="fe fe-search fe-32 text-muted mb-2 d-block"></i>
                        <p class="text-muted mb-0">لا توجد نتائج</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterShortcuts(query) {
        var items = document.querySelectorAll('#shortcutsGrid .shortcut-item');
        var q = query.trim().toLowerCase();
        var visibleCount = 0;

        items.forEach(function(item) {
            var label = item.getAttribute('data-label').toLowerCase();
            if (!q || label.includes(q)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        var emptyEl = document.getElementById('shortcutEmpty');
        if (emptyEl) {
            emptyEl.classList.toggle('d-none', visibleCount > 0);
        }
    }

    // Clear search when modal closes
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.querySelector('.modal-shortcut');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                var searchInput = document.getElementById('shortcutSearch');
                if (searchInput) {
                    searchInput.value = '';
                    filterShortcuts('');
                }
            });

            // Also handle Bootstrap 4 event
            $(modal).on('hidden.bs.modal', function () {
                var searchInput = document.getElementById('shortcutSearch');
                if (searchInput) {
                    searchInput.value = '';
                    filterShortcuts('');
                }
            });
        }
    });
</script>
