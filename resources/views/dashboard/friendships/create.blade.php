@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إضافة صداقة جديدة</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('friendships.index') }}">الأصدقاء</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة جديدة</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">معلومات الصداقة</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('friendships.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="person_id">الشخص <span class="text-danger">*</span></label>
                                <select id="person_id" name="person_id" 
                                        class="form-control @error('person_id') is-invalid @enderror" required>
                                    <option value="">اختر الشخص</option>
                                    @foreach($persons as $person)
                                        <option value="{{ $person->id }}" {{ old('person_id') == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('person_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="friend_id">الصديق <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select id="friend_id" name="friend_id" 
                                            class="form-control @error('friend_id') is-invalid @enderror" required>
                                        <option value="">اختر الصديق</option>
                                        @foreach($persons as $person)
                                            <option value="{{ $person->id }}" {{ old('friend_id') == $person->id ? 'selected' : '' }}>
                                                {{ $person->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addFriendOutsideFamilyModal">
                                            <i class="fas fa-plus"></i> إضافة من خارج العائلة
                                        </button>
                                    </div>
                                </div>
                                @error('friend_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف/نبذة عن الصداقة</label>
                        <textarea id="description" name="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="وصف مختصر عن الصداقة...">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">حد أقصى 1000 حرف</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="friendship_story">قصة الصداقة</label>
                        <textarea id="friendship_story" name="friendship_story" 
                                  class="form-control @error('friendship_story') is-invalid @enderror" 
                                  rows="5" 
                                  placeholder="قصة أو تفاصيل عن الصداقة...">{{ old('friendship_story') }}</textarea>
                        <small class="form-text text-muted">حد أقصى 5000 حرف</small>
                        @error('friendship_story')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ
                        </button>
                        <a href="{{ route('friendships.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال إضافة شخص من خارج العائلة --}}
    @include('dashboard.friendships.modals.add-outside-family')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // منع اختيار نفس الشخص كصديق
            $('#person_id, #friend_id').on('change', function() {
                const personId = $('#person_id').val();
                const friendId = $('#friend_id').val();
                
                if (personId && friendId && personId === friendId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'لا يمكن اختيار نفس الشخص كصديق!',
                    });
                    $(this).val('');
                }
            });

            // التحقق من صحة النموذج قبل الإرسال
            $('form').on('submit', function(e) {
                const personId = $('#person_id').val();
                const friendId = $('#friend_id').val();

                if (personId === friendId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'لا يمكن أن يكون الشخص والصديق نفس الشخص!',
                    });
                }
            });
        });
    </script>
@endpush

