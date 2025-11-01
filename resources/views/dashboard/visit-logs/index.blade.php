@extends('layouts.app')

@section('content')
<div class="container-fluid" dir="rtl">

    <!-- عنوان الصفحة -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye mr-2"></i>سجل الزيارات
        </h1>
        <a href="{{ route('dashboard.visit-logs.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-redo mr-1"></i>إعادة الضبط
        </a>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="row mb-4">
        <x-stats-card
            icon="fas fa-mouse-pointer"
            title="إجمالي الزيارات"
            :value="number_format($stats['total_visits'])"
            color="primary"
        />

        <x-stats-card
            icon="fas fa-users"
            title="زوار فريدون"
            :value="number_format($stats['unique_visitors'])"
            color="success"
        />

        <x-stats-card
            icon="fas fa-user-check"
            title="مستخدمون مسجلون"
            :value="number_format($stats['unique_users'])"
            color="info"
        />

        <x-stats-card
            icon="fas fa-tachometer-alt"
            title="متوسط وقت الاستجابة"
            :value="number_format($stats['avg_response_time'], 2) . ' ملث'"
            color="warning"
        />
    </div>

    <!-- فلاتر البحث -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>فلاتر البحث
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-0">
                <div class="row">
                    <!-- فلترة الأسبوع -->
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-gray-700">فلترة الأسبوع</label>
                        <select name="week" class="form-control select2">
                            <option value="">— الكل —</option>
                            @foreach ($weekOptions as $key => $label)
                                <option value="{{ $key }}" @selected(request('week') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- من تاريخ -->
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-gray-700">من تاريخ</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                    </div>

                    <!-- إلى تاريخ -->
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-gray-700">إلى تاريخ</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>

                    <!-- المستخدم -->
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-gray-700">المستخدم</label>
                        <select name="user_id" class="form-control select2">
                            <option value="">— الكل —</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- IP Address -->
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-gray-700">عنوان IP</label>
                        <input type="text" name="ip_address" value="{{ request('ip_address') }}"
                               class="form-control" placeholder="مثال: 192.168.1.1">
                    </div>

                    <!-- Route Name -->
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-gray-700">اسم المسار</label>
                        <select name="route_name" class="form-control select2">
                            <option value="">— الكل —</option>
                            @foreach ($routeNames as $route)
                                <option value="{{ $route }}" @selected(request('route_name') === $route)>{{ $route }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Method -->
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-gray-700">الطريقة</label>
                        <select name="method" class="form-control">
                            <option value="">— الكل —</option>
                            @foreach ($methods as $method)
                                <option value="{{ $method }}" @selected(request('method') === $method)>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- البحث -->
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-gray-700">بحث</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="بحث في URL, IP, Route">
                    </div>

                    <!-- أزرار -->
                    <div class="col-md-12 mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>تطبيق الفلاتر
                        </button>
                        <a href="{{ route('dashboard.visit-logs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i>مسح الفلاتر
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الزيارات -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>قائمة الزيارات
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr class="text-right">
                            <th style="min-width: 160px;">التاريخ والوقت</th>
                            <th style="min-width: 120px;">المستخدم</th>
                            <th style="min-width: 100px;">IP</th>
                            <th style="min-width: 150px;">الموقع</th>
                            <th style="min-width: 100px;">المسار</th>
                            <th style="min-width: 80px;">الطريقة</th>
                            <th style="min-width: 200px;">الميتا داتا</th>
                            <th style="min-width: 100px;">وقت الاستجابة</th>
                            <th style="min-width: 80px;">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="text-right">
                        @forelse($visitLogs as $visit)
                            <tr>
                                <td class="align-middle">
                                    <div class="text-monospace small">
                                        {{ $visit->created_at->format('Y-m-d') }}
                                    </div>
                                    <div class="text-monospace small text-muted">
                                        {{ $visit->created_at->format('H:i:s') }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $visit->created_at->diffForHumans() }}
                                    </div>
                                </td>

                                <td class="align-middle">
                                    @if($visit->user)
                                        <div class="font-weight-bold">{{ $visit->user->name }}</div>
                                        <div class="small text-muted">{{ $visit->user->email }}</div>
                                    @else
                                        <span class="text-muted">زائر</span>
                                    @endif
                                </td>

                                <td class="align-middle">
                                    <span class="badge badge-info">{{ $visit->ip_address }}</span>
                                </td>

                                <td class="align-middle">
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $visit->url }}">
                                        <a href="{{ $visit->url }}" target="_blank" class="text-primary">
                                            {{ \Illuminate\Support\Str::limit($visit->url, 40) }}
                                        </a>
                                    </div>
                                    @if($visit->referer)
                                        <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $visit->referer }}">
                                            من: {{ \Illuminate\Support\Str::limit($visit->referer, 30) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @if($visit->route_name)
                                        <span class="badge badge-secondary">{{ $visit->route_name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @php
                                        $methodColors = [
                                            'GET' => 'success',
                                            'POST' => 'primary',
                                            'PUT' => 'warning',
                                            'PATCH' => 'info',
                                            'DELETE' => 'danger',
                                        ];
                                        $color = $methodColors[$visit->method] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $color }}">{{ $visit->method }}</span>
                                </td>

                                <td class="align-middle small">
                                    <div class="metadata-info">
                                        @if($visit->country)
                                            <div class="mb-1">
                                                <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                                <strong>{{ $visit->city }}</strong>, {{ $visit->country }}
                                                @if($visit->region)
                                                    <span class="text-muted">({{ $visit->region }})</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if($visit->browser)
                                            <div class="mb-1">
                                                <i class="fas fa-globe text-primary mr-1"></i>
                                                <span>{{ $visit->browser }}</span>
                                                @if($visit->platform)
                                                    <span class="text-muted">- {{ $visit->platform }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if($visit->device)
                                            <div class="mb-1">
                                                <i class="fas fa-mobile-alt text-info mr-1"></i>
                                                <span>{{ $visit->device }}</span>
                                            </div>
                                        @endif

                                        @if($visit->metadata && isset($visit->metadata['isp']))
                                            <div class="mb-1">
                                                <i class="fas fa-network-wired text-secondary mr-1"></i>
                                                <span class="text-muted">{{ \Illuminate\Support\Str::limit($visit->metadata['isp'], 30) }}</span>
                                            </div>
                                        @endif

                                        @if($visit->request_id)
                                            <div class="mb-0">
                                                <i class="fas fa-fingerprint text-dark mr-1"></i>
                                                <span class="text-monospace" style="font-size: 0.75rem;">{{ \Illuminate\Support\Str::limit($visit->request_id, 20) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="align-middle">
                                    @if($visit->response_time)
                                        <span class="badge badge-{{ $visit->response_time > 1000 ? 'danger' : ($visit->response_time > 500 ? 'warning' : 'success') }}">
                                            {{ number_format($visit->response_time, 0) }} ملث
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @if($visit->status_code)
                                        @php
                                            $statusColors = [
                                                200 => 'success',
                                                201 => 'success',
                                                301 => 'info',
                                                302 => 'info',
                                                400 => 'warning',
                                                401 => 'warning',
                                                403 => 'danger',
                                                404 => 'warning',
                                                500 => 'danger',
                                            ];
                                            $statusColor = $statusColors[$visit->status_code] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $statusColor }}">{{ $visit->status_code }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-5" colspan="9">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    لا توجد زيارات مسجلة.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ترقيم الصفحات -->
            <div class="px-3 py-2 border-top">
                {{ $visitLogs->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    <!-- إحصائيات إضافية -->
    @if($stats['top_routes']->isNotEmpty() || $stats['top_ips']->isNotEmpty())
        <div class="row">
            @if($stats['top_routes']->isNotEmpty())
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-route mr-2"></i>أكثر المسارات زيارة
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($stats['top_routes'] as $route => $count)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="text-truncate" style="max-width: 300px;">{{ $route }}</span>
                                        <span class="badge badge-primary badge-pill">{{ number_format($count) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if($stats['top_ips']->isNotEmpty())
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-server mr-2"></i>أكثر العناوين نشاطاً
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($stats['top_ips'] as $ip => $count)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="text-monospace">{{ $ip }}</span>
                                        <span class="badge badge-info badge-pill">{{ number_format($count) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

@push('styles')
<style>
    .metadata-info {
        font-size: 0.85rem;
    }
    .metadata-info i {
        width: 16px;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endpush
@endsection

