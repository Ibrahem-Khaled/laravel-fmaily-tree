@extends('layouts.app')

@section('title', 'إدارة الرعاة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-handshake text-primary mr-2"></i>إدارة الرعاة
            </h1>
            <p class="text-muted mb-0">إدارة رعاة المسابقات في النظام</p>
        </div>
        <a href="{{ route('dashboard.sponsors.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة راعي جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th width="80">#</th>
                            <th>الشعار</th>
                            <th>الاسم</th>
                            <th>عدد المسابقات</th>
                            <th width="150" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsors as $sponsor)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($sponsor->image)
                                    <img src="{{ asset('storage/' . $sponsor->image) }}" alt="{{ $sponsor->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px; border-radius: 8px; border: 1px solid #dee2e6;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $sponsor->name }}</div>
                            </td>
                            <td>
                                <span class="badge badge-info px-2 py-1">{{ $sponsor->competitions_count ?? 0 }} مسابقات</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('dashboard.sponsors.edit', $sponsor) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dashboard.sponsors.destroy', $sponsor) }}" method="POST" class="d-inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" title="حذف">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle mb-3 text-secondary" style="font-size: 2rem;"></i>
                                <p class="mb-0">لا يوجد رعاة مضافين حالياً</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($sponsors, 'links') && $sponsors->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $sponsors->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حذف هذا الراعي؟ لا يمكن التراجع عن هذا الإجراء.')) {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endpush
