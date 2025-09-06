<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">لوحة التحكم</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم</span></a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        الادارات
    </div>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('people.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>الشخصيات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('marriages.index') }}">
            <i class="fas fa-fw fa-heart"></i>
            <span>الزواج</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('articles.index') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>
                المقالات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.images.index') }}">
            <i class="fas fa-fw fa-images"></i>
            <span>
                مكتبة الصور</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>
                الفئات للكل </span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('padges.index') }}">
            <i class="fas fa-fw fa-medal"></i>
            <span>
                الشارات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('roles.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>الادوار</span></a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
