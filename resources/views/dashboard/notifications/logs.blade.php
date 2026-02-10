@extends('layouts.app')

@section('title', 'سجل الإرسال')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-history text-primary mr-2"></i>سجل الإرسال
            </h1>
            <p class="text-muted mt-1">عرض الإشعارات المرسلة عبر واتساب وحالة كل إرسال</p>
        </div>
        <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-right mr-1"></i>العودة
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-2">
            <form method="get" class="form-inline">
                <select name="status" class="form-control form-control-sm mr-2 no-search">
                    <option value="">كل الحالات</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="sending" {{ request('status') === 'sending' ? 'selected' : '' }}>جاري الإرسال</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>تم الإرسال</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>جزئي</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>فشل</option>
                </select>
                <input type="date" name="from" class="form-control form-control-sm mr-2" value="{{ request('from') }}">
                <input type="date" name="to" class="form-control form-control-sm mr-2" value="{{ request('to') }}">
                <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter mr-1"></i>تصفية</button>
                <a href="{{ route('dashboard.notifications.logs') }}" class="btn btn-light btn-sm">إعادة تعيين</a>
            </form>
        </div>
    </div>

    {{-- Notifications List --}}
    @forelse($notifications as $n)
    <div class="card shadow-sm mb-3">
        <div class="card-header py-2 d-flex justify-content-between align-items-center">
            <div>
                <strong>#{{ $n->id }}</strong>
                <span class="text-muted mx-1">|</span>
                {{ $n->created_at->format('Y-m-d H:i') }}
                <span class="text-muted mx-1">|</span>
                <i class="fas fa-user text-muted fa-xs"></i> {{ $n->user->name ?? '—' }}
                @if($n->media_type)
                    <span class="badge badge-light border ml-2">
                        @if($n->media_type === 'image')<i class="fas fa-image text-primary"></i> صورة
                        @elseif($n->media_type === 'video')<i class="fas fa-video text-danger"></i> فيديو
                        @elseif($n->media_type === 'voice')<i class="fas fa-microphone text-warning"></i> صوت
                        @endif
                    </span>
                @endif
            </div>
            <div>
                @if($n->status === 'sent')
                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>تم الإرسال</span>
                @elseif($n->status === 'sending')
                    <span class="badge badge-info"><i class="fas fa-spinner fa-spin mr-1"></i>جاري الإرسال</span>
                @elseif($n->status === 'partial')
                    <span class="badge badge-warning"><i class="fas fa-exclamation-triangle mr-1"></i>جزئي</span>
                @elseif($n->status === 'failed')
                    <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>فشل</span>
                @else
                    <span class="badge badge-secondary">{{ $n->status }}</span>
                @endif
            </div>
        </div>
        <div class="card-body py-2">
            @if($n->title)
                <p class="mb-1 font-weight-bold">{{ $n->title }}</p>
            @endif
            <p class="mb-2 text-muted small">{{ Str::limit($n->body ?? '(بدون نص)', 120) }}</p>

            {{-- Delivery details --}}
            @php
                $sendLogs = \App\Models\UltramsgSendLog::where('notification_id', $n->id)->with('person')->get();
            @endphp
            @if($sendLogs->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0" style="font-size: 0.82rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>الشخص</th>
                            <th>الرقم</th>
                            <th>الحالة</th>
                            <th>UltraMSG ID</th>
                            <th>الخطأ</th>
                            <th>وقت الإرسال</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sendLogs as $sl)
                        <tr class="{{ $sl->status === 'failed' ? 'table-danger' : ($sl->status === 'sent' ? 'table-success' : '') }}">
                            <td>{{ $sl->person->first_name ?? '#'.$sl->person_id }}</td>
                            <td dir="ltr" class="text-left">{{ $sl->to_number }}</td>
                            <td>
                                @if($sl->status === 'sent')
                                    <span class="badge badge-success">تم</span>
                                @elseif($sl->status === 'failed')
                                    <span class="badge badge-danger">فشل</span>
                                @else
                                    <span class="badge badge-secondary">{{ $sl->status }}</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $sl->ultramsg_id ?? '—' }}</td>
                            <td class="text-danger small">{{ $sl->error_message ? Str::limit($sl->error_message, 80) : '—' }}</td>
                            <td class="text-muted small">{{ $sl->sent_at ? $sl->sent_at->format('H:i:s') : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted small mb-0">لا توجد سجلات إرسال بعد.</p>
            @endif
        </div>
    </div>
    @empty
    <div class="card shadow-sm">
        <div class="card-body text-center text-muted py-5">
            <i class="fas fa-inbox fa-3x mb-3 text-gray-300"></i>
            <p>لا توجد إشعارات بعد.</p>
        </div>
    </div>
    @endforelse

    <div class="mt-3">{{ $notifications->links() }}</div>
</div>
@endsection
