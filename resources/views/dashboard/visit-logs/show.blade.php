@extends('layouts.app')

@section('content')
<div class="container-fluid" dir="rtl">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye mr-2"></i>تفاصيل الزيارة
        </h1>
        <a href="{{ route('dashboard.visit-logs.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-right mr-1"></i>العودة للقائمة
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>معلومات الزيارة
                    </h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">التاريخ والوقت:</dt>
                        <dd class="col-sm-8">
                            <div class="text-monospace">{{ $visitLog->created_at->format('Y-m-d H:i:s') }}</div>
                            <div class="small text-muted">{{ $visitLog->created_at->diffForHumans() }}</div>
                        </dd>

                        <dt class="col-sm-4">المستخدم:</dt>
                        <dd class="col-sm-8">
                            @if($visitLog->user)
                                <strong>{{ $visitLog->user->name }}</strong>
                                <div class="small text-muted">{{ $visitLog->user->email }}</div>
                            @else
                                <span class="text-muted">زائر</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">عنوان IP:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-info">{{ $visitLog->ip_address }}</span>
                        </dd>

                        <dt class="col-sm-4">الرابط (URL):</dt>
                        <dd class="col-sm-8">
                            <a href="{{ $visitLog->url }}" target="_blank" class="text-primary">
                                {{ $visitLog->url }}
                            </a>
                        </dd>

                        <dt class="col-sm-4">اسم المسار:</dt>
                        <dd class="col-sm-8">
                            @if($visitLog->route_name)
                                <span class="badge badge-secondary">{{ $visitLog->route_name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">الطريقة:</dt>
                        <dd class="col-sm-8">
                            @php
                                $methodColors = [
                                    'GET' => 'success',
                                    'POST' => 'primary',
                                    'PUT' => 'warning',
                                    'PATCH' => 'info',
                                    'DELETE' => 'danger',
                                ];
                                $color = $methodColors[$visitLog->method] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $color }}">{{ $visitLog->method }}</span>
                        </dd>

                        <dt class="col-sm-4">المرجع (Referer):</dt>
                        <dd class="col-sm-8">
                            @if($visitLog->referer)
                                <a href="{{ $visitLog->referer }}" target="_blank" class="text-primary">
                                    {{ $visitLog->referer }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">User Agent:</dt>
                        <dd class="col-sm-8">
                            <code class="small">{{ $visitLog->user_agent ?? '—' }}</code>
                        </dd>

                        <dt class="col-sm-4">Session ID:</dt>
                        <dd class="col-sm-8">
                            <code class="small">{{ $visitLog->session_id ?? '—' }}</code>
                        </dd>

                        <dt class="col-sm-4">Request ID:</dt>
                        <dd class="col-sm-8">
                            <code class="small">{{ $visitLog->request_id ?? '—' }}</code>
                        </dd>

                        <dt class="col-sm-4">وقت الاستجابة:</dt>
                        <dd class="col-sm-8">
                            @if($visitLog->response_time)
                                <span class="badge badge-{{ $visitLog->response_time > 1000 ? 'danger' : ($visitLog->response_time > 500 ? 'warning' : 'success') }}">
                                    {{ number_format($visitLog->response_time, 2) }} مللي ثانية
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">رمز الحالة:</dt>
                        <dd class="col-sm-8">
                            @if($visitLog->status_code)
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
                                    $statusColor = $statusColors[$visitLog->status_code] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $statusColor }}">{{ $visitLog->status_code }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marked-alt mr-2"></i>الميتا داتا
                    </h6>
                </div>
                <div class="card-body">
                    @if($visitLog->metadata)
                        @if($visitLog->country)
                            <div class="mb-3">
                                <strong>الموقع:</strong>
                                <div class="mt-1">
                                    <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                    <strong>{{ $visitLog->city }}</strong>, {{ $visitLog->country }}
                                    @if($visitLog->region)
                                        <div class="small text-muted mt-1">{{ $visitLog->region }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($visitLog->browser)
                            <div class="mb-3">
                                <strong>المتصفح:</strong>
                                <div class="mt-1">
                                    <i class="fas fa-globe text-primary mr-1"></i>
                                    {{ $visitLog->browser }}
                                    @if($visitLog->platform)
                                        <span class="text-muted">- {{ $visitLog->platform }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($visitLog->device)
                            <div class="mb-3">
                                <strong>الجهاز:</strong>
                                <div class="mt-1">
                                    <i class="fas fa-mobile-alt text-info mr-1"></i>
                                    {{ $visitLog->device }}
                                </div>
                            </div>
                        @endif

                        @if(isset($visitLog->metadata['isp']))
                            <div class="mb-3">
                                <strong>مزود الخدمة:</strong>
                                <div class="mt-1">
                                    <i class="fas fa-network-wired text-secondary mr-1"></i>
                                    {{ $visitLog->metadata['isp'] }}
                                </div>
                            </div>
                        @endif

                        @if(isset($visitLog->metadata['country_code']))
                            <div class="mb-3">
                                <strong>رمز البلد:</strong>
                                <div class="mt-1">
                                    <span class="badge badge-light">{{ $visitLog->metadata['country_code'] }}</span>
                                </div>
                            </div>
                        @endif

                        <hr>

                        <div class="mb-0">
                            <strong>جميع الميتا داتا:</strong>
                            <pre class="bg-light p-2 rounded mt-2" style="max-height: 300px; overflow-y: auto; font-size: 0.85rem; direction: ltr;"><code>{{ json_encode($visitLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    @else
                        <p class="text-muted text-center">لا توجد ميتا داتا متاحة</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

