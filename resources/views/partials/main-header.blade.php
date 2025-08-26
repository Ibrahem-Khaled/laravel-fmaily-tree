{{-- ====================================================================== --}}
{{-- |     ملف الهيدر المنفصل (resources/views/partials/main-header.blade.php)      | --}}
{{-- ====================================================================== --}}

<style>
    /* --- START: CUSTOM HEADER STYLES --- */
    .main-header {
        background: var(--dark-green);
        border-bottom: 2px solid var(--primary-color);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        padding-top: 0;
        padding-bottom: 0;
        transition: all 0.3s ease;
    }

    .main-header .navbar-brand {
        font-size: 1.4rem;
        color: #fff;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
    }

    .main-header .nav-link {
        color: rgba(255, 255, 255, 0.75) !important;
        font-weight: 500;
        padding: 1.5rem 1rem;
        position: relative;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .main-header .nav-link:hover {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.05);
    }

    .main-header .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 50%;
        transform: translateX(50%);
        width: 0;
        height: 3px;
        background-color: var(--primary-color);
        transition: width 0.3s ease;
    }

    .main-header .nav-link:hover::after,
    .main-header .nav-link.active::after {
        width: 60%;
    }

    .main-header .nav-link.active {
        color: #fff !important;
        font-weight: 700;
    }

    .main-header .navbar-toggler {
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .main-header .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .dashboard-btn {
        border-color: rgba(255, 255, 255, 0.5);
    }

    .dashboard-btn:hover {
        background-color: #fff;
        color: var(--dark-green) !important;
    }

    /* --- END: CUSTOM HEADER STYLES --- */
</style>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top main-header">
        <div class="container-fluid">
            {{-- افترض أن راوت الصفحة الرئيسية هو 'home' --}}
            <a class="navbar-brand" href="{{ route('old.family-tree') }}">
                <i class="fas fa-tree me-2"></i>
                تواصل عائلة السريع
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('old.family-tree') ? 'active' : '' }}"
                            href="{{ route('old.family-tree') }}">الرئيسية</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('family-tree') ? 'active' : '' }}" aria-current="page"
                            href="{{ route('family-tree') }}">تواصل العائلة</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('family-tree') ? 'active' : '' }}"
                            href="{{ route('family-tree') }}">العرض الجديد</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}"
                            href="{{ route('gallery.index') }}">المعرض</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gallery.articles') ? 'active' : '' }}"
                            href="{{ route('gallery.articles') }}">شهادات و أبحاث</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                            href="{{ route('about') }}">عن العائلة</a>
                    </li> --}}
                </ul>

                <div class="d-flex">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm dashboard-btn">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        لوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
