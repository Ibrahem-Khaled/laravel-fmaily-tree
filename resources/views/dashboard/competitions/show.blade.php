@extends('layouts.app')

@section('title', 'تفاصيل المسابقة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-trophy text-primary mr-2"></i>{{ $competition->title }}
            </h1>
            <p class="text-muted mb-0">إدارة الفرق والأعضاء للمسابقة</p>
        </div>
        <div>
            <a href="{{ route('dashboard.competitions.edit', $competition) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-edit mr-2"></i>تعديل المسابقة
            </a>
            <a href="{{ route('dashboard.competitions.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الفرق</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_teams'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الفرق المكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['complete_teams'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">الفرق غير المكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['incomplete_teams'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي المسجلين</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_members'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competition Info -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>معلومات المسابقة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>نوع اللعبة:</strong> {{ $competition->game_type }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>حجم الفريق:</strong> {{ $competition->team_size }} عضو
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>الحالة:</strong>
                            @if($competition->is_active)
                                <span class="badge badge-success">نشط</span>
                            @else
                                <span class="badge badge-secondary">معطل</span>
                            @endif
                        </div>
                        @if($competition->start_date)
                            <div class="col-md-6 mb-3">
                                <strong>تاريخ بداية التسجيل:</strong> {{ $competition->start_date->format('Y-m-d') }}
                            </div>
                        @endif
                        @if($competition->end_date)
                            <div class="col-md-6 mb-3">
                                <strong>تاريخ نهاية التسجيل:</strong> {{ $competition->end_date->format('Y-m-d') }}
                            </div>
                        @endif
                        @if($competition->description)
                            <div class="col-12 mb-3">
                                <strong>الوصف:</strong>
                                <p class="mt-2">{{ $competition->description }}</p>
                            </div>
                        @endif
                        <div class="col-12 mb-3">
                            <strong>رابط التسجيل:</strong><br>
                            <code>{{ $competition->registration_url }}</code>
                            <button type="button" class="btn btn-sm btn-outline-primary ml-2" onclick="copyToClipboard('{{ $competition->registration_url }}')">
                                <i class="fas fa-copy"></i> نسخ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Users Section -->
    @if(isset($individualUsers) && $individualUsers->count() > 0)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold text-warning">
                    <i class="fas fa-user-clock mr-2"></i>الأفراد غير المجمعين ({{ $individualUsers->count() }})
                </h6>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createTeamModal">
                    <i class="fas fa-users-cog mr-2"></i>تجميع في فريق
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAllIndividuals" onchange="toggleAllIndividuals()">
                                </th>
                                <th>الاسم</th>
                                <th>رقم الهاتف</th>
                                <th>البريد الإلكتروني</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($individualUsers as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="individual-checkbox" value="{{ $user->id }}" 
                                            onchange="updateSelectedUsers()" 
                                            data-name="{{ $user->name }}" 
                                            data-phone="{{ $user->phone ?? '-' }}">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>{{ $user->email ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Teams Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-users mr-2"></i>الفرق ({{ $competition->teams->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if($competition->teams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>اسم الفريق</th>
                                <th>القائد</th>
                                <th>عدد الأعضاء</th>
                                <th>الحالة</th>
                                <th>رابط التسجيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competition->teams as $team)
                                <tr>
                                    <td>
                                        <strong>{{ $team->name }}</strong>
                                    </td>
                                    <td>
                                        @if($team->creator)
                                            {{ $team->creator->name }}
                                            @if($team->creator->phone)
                                                <br><small class="text-muted">{{ $team->creator->phone }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $team->members->count() }} / {{ $competition->team_size }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($team->is_complete)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>مكتمل
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock mr-1"></i>غير مكتمل
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('{{ $team->registration_url }}')" title="نسخ الرابط">
                                            <i class="fas fa-link"></i> نسخ
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="toggleTeamMembers({{ $team->id }})" title="عرض الأعضاء">
                                            <i class="fas fa-eye"></i> الأعضاء
                                        </button>
                                    </td>
                                </tr>
                                <tr id="team-members-{{ $team->id }}" style="display: none;">
                                    <td colspan="6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="font-weight-bold">أعضاء الفريق:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>الاسم</th>
                                                                <th>الهاتف</th>
                                                                <th>البريد</th>
                                                                <th>الدور</th>
                                                                <th>تاريخ الانضمام</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($team->members as $member)
                                                                <tr>
                                                                    <td>{{ $member->name }}</td>
                                                                    <td>{{ $member->phone ?? '-' }}</td>
                                                                    <td>{{ $member->email ?? '-' }}</td>
                                                                    <td>
                                                                        @if($member->pivot->role === 'captain')
                                                                            <span class="badge badge-primary">قائد</span>
                                                                        @else
                                                                            <span class="badge badge-secondary">عضو</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('Y-m-d') : '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد فرق مسجلة حالياً</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Creating Team from Individuals -->
<div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء فريق من الأفراد</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.competitions.create-team', $competition) }}" method="POST" id="createTeamForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>اسم الفريق <span class="text-danger">*</span></label>
                        <input type="text" name="team_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>الأعضاء المحددون:</label>
                        <div id="selectedUsersList" class="border p-2 rounded" style="min-height: 50px; max-height: 200px; overflow-y: auto;">
                            <p class="text-muted mb-0">لم يتم اختيار أي أعضاء</p>
                        </div>
                    </div>
                    <p class="text-muted small">
                        <strong>ملاحظة:</strong> يمكنك اختيار حتى {{ $competition->team_size }} عضو للفريق
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="submitTeamBtn" disabled>إنشاء الفريق</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('تم نسخ الرابط بنجاح!');
    }, function(err) {
        console.error('فشل نسخ الرابط:', err);
    });
}

function toggleTeamMembers(teamId) {
    const row = document.getElementById('team-members-' + teamId);
    if (row.style.display === 'none') {
        row.style.display = '';
    } else {
        row.style.display = 'none';
    }
}

function toggleAllIndividuals() {
    const selectAll = document.getElementById('selectAllIndividuals');
    const checkboxes = document.querySelectorAll('.individual-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateSelectedUsers();
}

function updateSelectedUsers() {
    const checkboxes = document.querySelectorAll('.individual-checkbox:checked');
    const selectedUsersList = document.getElementById('selectedUsersList');
    const form = document.getElementById('createTeamForm');
    const submitBtn = document.getElementById('submitTeamBtn');
    const maxTeamSize = {{ $competition->team_size }};
    
    // إزالة جميع hidden inputs السابقة
    const existingInputs = form.querySelectorAll('input[name="user_ids[]"]');
    existingInputs.forEach(input => input.remove());
    
    if (checkboxes.length === 0) {
        selectedUsersList.innerHTML = '<p class="text-muted mb-0">لم يتم اختيار أي أعضاء</p>';
        submitBtn.disabled = true;
    } else {
        if (checkboxes.length > maxTeamSize) {
            selectedUsersList.innerHTML = `<p class="text-danger mb-0">تم اختيار ${checkboxes.length} عضو. الحد الأقصى هو ${maxTeamSize} عضو</p>`;
            submitBtn.disabled = true;
            return;
        }
        
        let html = '<ul class="list-unstyled mb-0">';
        checkboxes.forEach(cb => {
            const name = cb.getAttribute('data-name');
            const phone = cb.getAttribute('data-phone');
            html += `<li><strong>${name}</strong> - ${phone}</li>`;
            
            // إضافة hidden input
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'user_ids[]';
            hiddenInput.value = cb.value;
            form.appendChild(hiddenInput);
        });
        html += '</ul>';
        selectedUsersList.innerHTML = html;
        submitBtn.disabled = false;
    }
}

// تحديث القائمة عند تغيير أي checkbox
document.addEventListener('DOMContentLoaded', function() {
    // تحديث عند فتح المودال
    $('#createTeamModal').on('show.bs.modal', function() {
        updateSelectedUsers();
    });
    
    // إعادة تعيين المودال عند إغلاقه
    $('#createTeamModal').on('hidden.bs.modal', function() {
        document.querySelectorAll('.individual-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAllIndividuals').checked = false;
        document.getElementById('createTeamForm').reset();
        updateSelectedUsers();
    });
});
</script>
@endsection
