@props(['user'])

<tr>
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar avatar-sm mr-2">
                <img src="{{ $user->avatar_url }}" alt="" class="avatar-img rounded-circle">
            </div>
            <div>
                <p class="mb-0 text-dark font-weight-bold">{{ $user->name }}</p>
                <small class="text-muted">{{ $user->email }}</small>
            </div>
        </div>
    </td>
    <td>{{ $user->phone }}</td>
    <td>
        <x-dashboard.user-role-label :role="$user->role" />
    </td>
    <td>
        <x-dashboard.user-status :status="$user->status" />
    </td>
    <td>{{ $user->created_at->format('Y/m/d') }}</td>
    <td>
        <div class="dropdown">
            <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-muted sr-only">إجراءات</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('dashboard.users.edit', $user->id) }}">
                    <i class="fe fe-edit fe-12 mr-2"></i> تعديل
                </a>
                <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fe fe-trash fe-12 mr-2"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
