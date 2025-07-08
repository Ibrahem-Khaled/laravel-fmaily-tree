{{-- تبويب الفلترة حسب الجنس --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('gender') && request()->routeIs('people.index') ? 'active' : '' }}"
            href="{{ route('people.index', ['search' => request('search')]) }}">الكل</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('gender') === 'male' ? 'active' : '' }}"
            href="{{ route('people.index', ['gender' => 'male', 'search' => request('search')]) }}">الذكور</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('gender') === 'female' ? 'active' : '' }}"
            href="{{ route('people.index', ['gender' => 'female', 'search' => request('search')]) }}">الإناث</a>
    </li>
</ul>

{{-- نموذج البحث --}}
<form action="{{ route('people.index') }}" method="GET" class="mb-4">
    {{-- إذا كان هناك فلتر للجنس، قم بتضمينه كحقل مخفي للحفاظ عليه عند البحث --}}
    @if (request('gender'))
        <input type="hidden" name="gender" value="{{ request('gender') }}">
    @endif

    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم الأول أو الأخير..."
            value="{{ request('search') }}">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> بحث
            </button>
        </div>
    </div>
</form>
