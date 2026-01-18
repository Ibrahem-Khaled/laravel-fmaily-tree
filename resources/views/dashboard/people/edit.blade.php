@extends('layouts.app')

@section('title', 'تعديل شخص')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">تعديل بيانات: {{ $person->full_name }}</h1>
            <a href="{{ route('people.show', $person->id) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-right"></i> رجوع لملف الشخص
            </a>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('people.index') }}">الأشخاص</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('people.show', $person->id) }}">ملف الشخص</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">تعديل</li>
            </ol>
        </nav>

        @include('components.alerts')

        <form action="{{ route('people.update', $person->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="redirect_to" value="show">

            @include('dashboard.people.partials.form', ['person' => $person, 'males' => $males])

            <div class="d-flex justify-content-end">
                <a href="{{ route('people.show', $person->id) }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary ml-2">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('dashboard.people.partials.father-wives-script')

    <script>
        $(function() {
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationInput = document.getElementById('location');
            const locationIdInput = document.getElementById('location_id');
            const suggestionsDiv = document.getElementById('location_suggestions');
            let searchTimeout;

            if (!locationInput || !locationIdInput || !suggestionsDiv) return;

            locationInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    suggestionsDiv.style.display = 'none';
                    locationIdInput.value = '';
                    return;
                }

                searchTimeout = setTimeout(function() {
                    fetch('{{ route("locations.autocomplete") }}?q=' + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            suggestionsDiv.innerHTML = '';

                            if (!Array.isArray(data) || data.length === 0) {
                                suggestionsDiv.innerHTML =
                                    '<div class="list-group-item text-muted">لا توجد نتائج</div>';
                                suggestionsDiv.style.display = 'block';
                                return;
                            }

                            data.forEach(function(location) {
                                const item = document.createElement('div');
                                item.className = 'list-group-item list-group-item-action';
                                item.style.cursor = 'pointer';
                                item.innerHTML = `
                                    <strong>${location.name}</strong>
                                    ${location.persons_count > 0 ? `<small class="text-muted">(${location.persons_count} شخص)</small>` : ''}
                                `;

                                item.addEventListener('click', function() {
                                    locationInput.value = location.name;
                                    locationIdInput.value = location.id;
                                    suggestionsDiv.style.display = 'none';
                                });

                                item.addEventListener('mousedown', function(e) {
                                    // Prevent blur before click in some browsers
                                    e.preventDefault();
                                });

                                suggestionsDiv.appendChild(item);
                            });

                            suggestionsDiv.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!locationInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                    suggestionsDiv.style.display = 'none';
                }
            });
        });
    </script>
@endpush

