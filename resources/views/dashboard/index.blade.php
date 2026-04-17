@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="container-fluid">
        {{-- 1. Premium Hero Section --}}
        <x-dashboard.glass-hero title="مرحباً بك في لوحة تحكم عائلة السريع"
            subtitle="نظرة شاملة وذكية على إدارة شؤون العائلة والمحتوى والمناسبات.">
            <x-slot name="actions">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-pill bg-white bg-opacity-10 text-white px-3 py-2" style="backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.1);">
                        <i class="fe fe-calendar mr-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                    <a href="{{ safe_route('sila') }}" class="btn btn-primary shadow-sm px-4">
                        <i class="fe fe-share-2 mr-1"></i> شجرة العائلة
                    </a>
                </div>
            </x-slot>

            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="d-flex align-items-center text-white mb-2">
                        <div class="mr-3 p-2 bg-white bg-opacity-10 rounded-circle text-white shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fe fe-users"></i></div>
                        <div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ number_format($stats['totalPeople']) }}</div>
                            <div class="small opacity-75 text-white">إجمالي الأشخاص</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center text-white mb-2">
                        <div class="mr-3 p-2 bg-white bg-opacity-10 rounded-circle text-white shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fe fe-heart"></i></div>
                        <div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ number_format($stats['totalMarriages']) }}</div>
                            <div class="small opacity-75 text-white">إجمالي الزيجات</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center text-white mb-2">
                        <div class="mr-3 p-2 bg-white bg-opacity-10 rounded-circle text-white shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fe fe-image"></i></div>
                        <div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ number_format($stats['totalImages']) }}</div>
                            <div class="small opacity-75 text-white">الوسائط والصور</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center text-white mb-2">
                        <div class="mr-3 p-2 bg-white bg-opacity-10 rounded-circle text-white shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fe fe-award"></i></div>
                        <div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ number_format($stats['totalBadges']) }}</div>
                            <div class="small opacity-75 text-white">الأوسمة الممنوحة</div>
                        </div>
                    </div>
                </div>
            </div>
        </x-dashboard.glass-hero>

        {{-- 2. Primary Statistics Grid --}}
        <div class="row mt-n2">
            <x-dashboard.stat-card title="الأحياء" value="{{ number_format($stats['alivePeople']) }}" icon="fe-heartbeat"
                gradient="success" description="نفوس حية تنبض بالحب" badge="نشط" />

            <x-dashboard.stat-card title="ذكور وإناث" value="{{ $stats['maleCount'] . ' / ' . $stats['femaleCount'] }}"
                icon="fe-users" gradient="info" description="توازن جميل في العائلة" />

            <x-dashboard.stat-card title="الأجيال" value="{{ $stats['totalGenerations'] }}" icon="fe-layers"
                gradient="warning" description="تاريخ عريق يمتد" />

            <x-dashboard.stat-card title="أضيفوا مؤخراً" value="{{ $stats['peopleAddedThisMonth'] }}" icon="fe-user-plus"
                gradient="primary" description="أعضاء جدد هذا الشهر" badge="+{{ $stats['peopleAddedThisMonth'] }}" />
        </div>

        {{-- 3. Secondary Metrics Box --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
                    <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center">
                        <div class="avatar-sm mr-3" style="background: rgba(78, 115, 223, 0.12); border-radius: 8px;"><i class="fe fe-grid text-primary"></i></div>
                        <h6 class="mb-0 font-weight-bold">إحصائيات إضافية</h6>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            <x-dashboard.glance-card title="مقال" value="{{ $stats['totalArticles'] }}" icon="fe-file-text" color="info" />
                            <x-dashboard.glance-card title="برنامج نشط" value="{{ $stats['activePrograms'] }}" icon="fe-play-circle" color="success" />
                            <x-dashboard.glance-card title="مناسبة" value="{{ $stats['activeEvents'] }}" icon="fe-calendar" color="warning" />
                            <x-dashboard.glance-card title="مجلس" value="{{ $stats['totalCouncils'] }}" icon="fe-home" color="danger" />
                            <x-dashboard.glance-card title="متوفين" value="{{ $stats['deceasedPeople'] }}" icon="fe-cloud-off" color="secondary" />
                            <x-dashboard.glance-card title="زيارات" value="{{ number_format($stats['totalVisits'] ?? 0) }}" icon="fe-eye" color="primary" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- 4. Main Feed (Events & Birthdays) --}}
            <div class="col-lg-8">
                {{-- حدث اليوم --}}
                <div class="card shadow-sm border-0 rounded-xl mb-4 overflow-hidden">
                    <div class="card-header bg-primary text-white border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 font-weight-bold"><i class="fe fe-zap mr-2"></i>حدث في مثل هذا اليوم</h6>
                            <span class="badge badge-pill bg-white bg-opacity-20">{{ \Carbon\Carbon::now()->translatedFormat('d F') }}</span>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        @if ($events['birthsToday']->isEmpty() && $events['marriagesToday']->isEmpty())
                            <div class="text-center py-5">
                                <img src="{{ asset('admin-assets/assets/images/no-data.svg') }}" alt="No data" style="width: 120px; opacity: 0.5;">
                                <p class="text-muted mt-3">لا توجد أحداث مسجلة لهذا اليوم</p>
                            </div>
                        @else
                            @foreach ($events['birthsToday'] as $person)
                                <x-dashboard.activity-item title="ذكرى ميلاد: {{ $person->full_name }}" 
                                    subtitle="وُلد في عام {{ $person->birth_date->year }} (قبل {{ $person->birth_date->age }} سنة)"
                                    icon="fe-gift" iconBg="warning" 
                                    href="{{ route('people.show', $person) }}"
                                    badge="ميلاد" />
                            @endforeach

                            @foreach ($events['marriagesToday'] as $marriage)
                                <x-dashboard.activity-item title="ذكرى زواج: {{ $marriage->husband->full_name }} و {{ $marriage->wife->full_name }}" 
                                    subtitle="عُقد الزفاف في عام {{ $marriage->married_at->year }} (قبل {{ $marriage->married_at->diffInYears(\Carbon\Carbon::now()) }} سنة)"
                                    icon="fe-heart" iconBg="danger" 
                                    badge="زواج" />
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Launchpad (Quick Actions) --}}
                <div class="card shadow-sm border-0 rounded-xl mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 font-weight-bold"><i class="fe fe-cpu mr-2"></i>منصة الإطلاق (إجراءات سريعة)</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-4 col-6 mb-3">
                                <a href="{{ route('people.create') }}" class="launch-btn bg-soft-primary">
                                    <i class="fe fe-user-plus text-primary"></i>
                                    <span>إضافة شخص</span>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="{{ route('dashboard.family-news.create') }}" class="launch-btn bg-soft-success">
                                    <i class="fe fe-plus-circle text-success"></i>
                                    <span>إضافة خبر</span>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="{{ route('gallery.index') }}" class="launch-btn bg-soft-info">
                                    <i class="fe fe-camera text-info"></i>
                                    <span>المعرض</span>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="{{ route('dashboard.notifications.index') }}" class="launch-btn bg-soft-warning">
                                    <i class="fe fe-bell text-warning"></i>
                                    <span>الإشعارات</span>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="{{ route('dashboard.visit-logs.index') }}" class="launch-btn bg-soft-danger">
                                    <i class="fe fe-activity text-danger"></i>
                                    <span>سجل الزيارات</span>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="{{ route('dashboard.clear-cache') }}" onclick="event.preventDefault(); clearSystemCache('all', this);" class="launch-btn bg-soft-secondary">
                                    <i class="fe fe-refresh-cw text-secondary"></i>
                                    <span>تحديث الكاش</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Fun Facts --}}
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3 font-weight-bold ml-1">هل تعلم؟ (ارقام قياسية)</h6>
                    </div>
                    @if($funFact['oldestPerson'])
                    <div class="col-md-4 mb-4">
                        <div class="bento-card bg-primary text-white shadow">
                            <div class="d-flex flex-column h-100 justify-content-between">
                                <i class="fe fe-award h3 mb-4 opacity-50"></i>
                                <div>
                                    <div class="small opacity-80">أكبر شخص سناً</div>
                                    <div class="h5 font-weight-bold mb-0">{{ $funFact['oldestPerson']->full_name }}</div>
                                    <div class="badge badge-pill bg-white bg-opacity-20 mt-2">{{ $funFact['oldestPerson']->birth_date->age }} عاماً</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($funFact['personWithMostChildren'])
                    <div class="col-md-4 mb-4">
                        <div class="bento-card border shadow-sm">
                            <div class="d-flex flex-column h-100 justify-content-between">
                                <i class="fe fe-users h3 mb-4 text-info opacity-50"></i>
                                <div>
                                    <div class="small text-muted">الأكثر ذريّة</div>
                                    <div class="h5 font-weight-bold mb-0 dark-text-light">{{ $funFact['personWithMostChildren']->full_name }}</div>
                                    <div class="badge badge-pill bg-info text-white mt-2">{{ $funFact['personWithMostChildren']->children_count }} ابن وابنة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($funFact['latestMarriage'])
                    <div class="col-md-4 mb-4">
                        <div class="bento-card border shadow-sm">
                            <div class="d-flex flex-column h-100 justify-content-between">
                                <i class="fe fe-heart h3 mb-4 text-danger opacity-50"></i>
                                <div>
                                    <div class="small text-muted">أحدث زفاف</div>
                                    <div class="h6 font-weight-bold mb-0 dark-text-light">{{ $funFact['latestMarriage']->husband->full_name }}</div>
                                    <div class="badge badge-pill bg-danger text-white mt-1">{{ $funFact['latestMarriage']->married_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- 5. Right Column (Recent Activity & Birthdays) --}}
            <div class="col-lg-4">
                {{-- أضيفوا مؤخراً --}}
                <div class="card shadow-sm border-0 rounded-xl mb-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 font-weight-bold">أضيفوا مؤخراً</h6>
                        <a href="{{ route('people.index') }}" class="small text-primary">الكل</a>
                    </div>
                    <div class="card-body p-2 pt-0">
                        @forelse ($recentlyAdded as $person)
                            <x-dashboard.activity-item title="{{ $person->full_name }}" 
                                subtitle="أضيف بواسطة النظام"
                                time="{{ $person->created_at->diffForHumans() }}"
                                icon="fe-user" iconBg="info" 
                                href="{{ route('people.show', $person) }}" />
                        @empty
                            <div class="text-center py-4">لم تُضف بيانات مؤخراً</div>
                        @endforelse
                    </div>
                </div>

                {{-- أيام ميلاد قادمة --}}
                <div class="card shadow-sm border-0 rounded-xl mb-4 overflow-hidden border-top-warning">
                    <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 font-weight-bold text-warning">أيام ميلاد قادمة</h6>
                    </div>
                    <div class="card-body p-2 pt-0">
                        @forelse ($upcomingBirthdays as $person)
                            <x-dashboard.activity-item title="{{ $person->full_name }}" 
                                subtitle="{{ $person->birth_date->format('d M') }} (عمره الآن: {{ $person->birth_date->age }})"
                                icon="fe-gift" iconBg="warning" 
                                href="{{ route('people.show', $person) }}" 
                                badge="{{ $person->birth_date->isToday() ? 'اليوم' : ($person->birth_date->isTomorrow() ? 'غداً' : '') }}" />
                        @empty
                            <div class="text-center py-4">لا توجد أعياد ميلاد قادمة</div>
                        @endforelse
                    </div>
                </div>

                {{-- System Stats --}}
                <div class="card shadow-sm border-0 rounded-xl mb-4 p-4 status-card">
                        <h6 class="mb-3 font-weight-bold">حالة النظام</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>صحة البيانات</span>
                                <span>98%</span>
                            </div>
                            <div class="progress progress-sm" style="height: 5px; background-color: rgba(0,0,0,0.05);">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 98%" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>تكامل الأعضاء</span>
                                <span>85%</span>
                            </div>
                            <div class="progress progress-sm" style="height: 5px; background-color: rgba(0,0,0,0.05);">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rounded-xl { border-radius: 20px !important; }

        /* ===== Status Card (Theme-aware) ===== */
        .status-card {
            background: #ffffff;
            color: #1a1a2e;
        }
        .dark .status-card {
            background: #1a1a2e;
            color: #ffffff;
        }
        .dark .status-card .progress {
            background-color: rgba(255,255,255,0.1);
        }

        /* ===== Avatar Icon Shape ===== */
        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== Launchpad Buttons ===== */
        .launch-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            border-radius: 18px;
            text-decoration: none !important;
            transition: all 0.3s ease;
            text-align: center;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .launch-btn i { font-size: 1.5rem; margin-bottom: 0.75rem; }
        .launch-btn span { font-size: 0.85rem; font-weight: 600; color: #374151; }
        .launch-btn:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
        .dark .launch-btn span { color: #d1d5db; }
        .dark .launch-btn { border-color: rgba(255,255,255,0.06); }

        /* ===== Soft Background Colors (Used by launch-btn & activity icons) ===== */
        .bg-soft-primary   { background-color: rgba(78,  115, 223, 0.12) !important; }
        .bg-soft-success   { background-color: rgba(28,  200, 138, 0.12) !important; }
        .bg-soft-info      { background-color: rgba(54,  185, 204, 0.12) !important; }
        .bg-soft-warning   { background-color: rgba(246, 194, 62,  0.14) !important; }
        .bg-soft-danger    { background-color: rgba(231, 74,  59,  0.12) !important; }
        .bg-soft-secondary { background-color: rgba(108, 117, 125, 0.12) !important; }

        /* ===== Bento Cards ===== */
        .bento-card {
            padding: 1.5rem;
            border-radius: 24px;
            height: 100%;
            transition: all 0.3s ease;
        }
        .bento-card:hover { transform: scale(1.02); }

        /* ===== Misc ===== */
        .border-top-warning { border-top: 4px solid #f6c23e !important; }

        @media (max-width: 768px) {
            .mt-n2 { margin-top: 0 !important; }
        }
    </style>

    <script>
    function clearSystemCache(type, btn) {
        if (!confirm('هل أنت متأكد من مسح التخزين المؤقت؟')) return;

        const originalBtnContent = btn.innerHTML;
        btn.innerHTML = '<i class="fe fe-loader fe-spin"></i>';
        btn.style.pointerEvents = 'none';

        fetch('{{ route("dashboard.clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert(data.message || 'حدث خطأ ما');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال بالخادم');
        })
        .finally(() => {
            btn.innerHTML = originalBtnContent;
            btn.style.pointerEvents = 'auto';
        });
    }
    </script>
@endsection
