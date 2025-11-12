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
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم</span></a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        إدارة العائلة
    </div>

    <!-- Nav Item - Family Management Collapse -->
    <li class="nav-item {{ request()->routeIs(['people.*', 'marriages.*', 'breastfeeding.*', 'locations.*']) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFamily"
           aria-expanded="{{ request()->routeIs(['people.*', 'marriages.*', 'breastfeeding.*', 'locations.*']) ? 'true' : 'false' }}"
           aria-controls="collapseFamily">
            <i class="fas fa-fw fa-sitemap"></i>
            <span>إدارة العائلة</span>
        </a>
        <div id="collapseFamily" class="collapse {{ request()->routeIs(['people.*', 'marriages.*', 'breastfeeding.*', 'locations.*']) ? 'show' : '' }}"
             aria-labelledby="headingFamily" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة العائلة:</h6>
                <a class="collapse-item {{ request()->routeIs('people.*') ? 'active' : '' }}" href="{{ route('people.index') }}">
                    <i class="fas fa-fw fa-users"></i> الشخصيات
                </a>
                <a class="collapse-item {{ request()->routeIs('marriages.*') ? 'active' : '' }}" href="{{ route('marriages.index') }}">
                    <i class="fas fa-fw fa-heart"></i> الزواج
                </a>
                <a class="collapse-item {{ request()->routeIs('breastfeeding.*') ? 'active' : '' }}" href="{{ route('breastfeeding.index') }}">
                    <i class="fas fa-fw fa-baby"></i> الرضاعة
                </a>
                <a class="collapse-item {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">
                    <i class="fas fa-fw fa-map-marker-alt"></i> الأماكن
                </a>
            </div>
        </div>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        الصفحة الرئيسية
    </div>

    <!-- Nav Item - Home Page Management -->
    <li class="nav-item {{ request()->routeIs(['dashboard.site-content.*', 'dashboard.slideshow.*', 'dashboard.home-gallery.*', 'dashboard.courses.*', 'dashboard.programs.*', 'dashboard.councils.*']) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHome"
           aria-expanded="{{ request()->routeIs(['dashboard.site-content.*', 'dashboard.slideshow.*', 'dashboard.home-gallery.*', 'dashboard.courses.*', 'dashboard.programs.*', 'dashboard.councils.*']) ? 'true' : 'false' }}"
           aria-controls="collapseHome">
            <i class="fas fa-fw fa-home"></i>
            <span>الصفحة الرئيسية</span>
        </a>
        <div id="collapseHome" class="collapse {{ request()->routeIs(['dashboard.site-content.*', 'dashboard.slideshow.*', 'dashboard.home-gallery.*', 'dashboard.courses.*', 'dashboard.programs.*', 'dashboard.councils.*']) ? 'show' : '' }}"
             aria-labelledby="headingHome" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة الصفحة الرئيسية:</h6>
                <a class="collapse-item {{ request()->routeIs('dashboard.site-content.*') ? 'active' : '' }}" href="{{ route('dashboard.site-content.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i> محتوى الصفحة
                </a>
                <a class="collapse-item {{ request()->routeIs('dashboard.slideshow.*') ? 'active' : '' }}" href="{{ route('dashboard.slideshow.index') }}">
                    <i class="fas fa-fw fa-sliders-h"></i> السلايدشو
                </a>
                <a class="collapse-item {{ request()->routeIs('dashboard.home-gallery.*') ? 'active' : '' }}" href="{{ route('dashboard.home-gallery.index') }}">
                    <i class="fas fa-fw fa-images"></i> صور الصفحة الرئيسية
                </a>
                <a class="collapse-item {{ request()->routeIs('dashboard.courses.*') ? 'active' : '' }}" href="{{ route('dashboard.courses.index') }}">
                    <i class="fas fa-fw fa-graduation-cap"></i> الدورات
                </a>
                <a class="collapse-item {{ request()->routeIs('dashboard.programs.*') ? 'active' : '' }}" href="{{ route('dashboard.programs.index') }}">
                    <i class="fas fa-fw fa-tv"></i> البرامج
                </a>
                <a class="collapse-item {{ request()->routeIs('dashboard.councils.*') ? 'active' : '' }}" href="{{ route('dashboard.councils.index') }}">
                    <i class="fas fa-fw fa-building"></i> مجالس العائلة
                </a>
            </div>
        </div>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        المحتوى والوسائط
    </div>

    <!-- Nav Item - Content Management Collapse -->
    <li class="nav-item {{ request()->routeIs(['articles.*', 'categories.*', 'dashboard.images.*', 'stories.*']) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContent"
           aria-expanded="{{ request()->routeIs(['articles.*', 'categories.*', 'dashboard.images.*', 'stories.*']) ? 'true' : 'false' }}"
           aria-controls="collapseContent">
            <i class="fas fa-fw fa-folder"></i>
            <span>المحتوى والوسائط</span>
        </a>
        <div id="collapseContent" class="collapse {{ request()->routeIs(['articles.*', 'categories.*', 'dashboard.images.*', 'stories.*']) ? 'show' : '' }}"
             aria-labelledby="headingContent" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة المحتوى:</h6>
                <a class="collapse-item {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                    <i class="fas fa-fw fa-book"></i> المقالات
                </a>
                <a class="collapse-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fas fa-fw fa-tags"></i> الفئات
                </a>
                <a class="collapse-item {{ request()->routeIs('dashboard.images.*') ? 'active' : '' }}" href="{{ route('dashboard.images.index') }}">
                    <i class="fas fa-fw fa-images"></i> مكتبة الصور
                </a>
                <a class="collapse-item {{ request()->routeIs('stories.*') ? 'active' : '' }}" href="{{ route('stories.index') }}">
                    <i class="fas fa-fw fa-book-open"></i> القصص
                </a>
            </div>
        </div>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        النظام والإعدادات
    </div>

    <!-- Nav Item - System Management Collapse -->
    <li class="nav-item {{ request()->routeIs(['padges.*', 'roles.*', 'users.*']) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSystem"
           aria-expanded="{{ request()->routeIs(['padges.*', 'roles.*', 'users.*']) ? 'true' : 'false' }}"
           aria-controls="collapseSystem">
            <i class="fas fa-fw fa-cogs"></i>
            <span>النظام والإعدادات</span>
        </a>
        <div id="collapseSystem" class="collapse {{ request()->routeIs(['padges.*', 'roles.*', 'users.*']) ? 'show' : '' }}"
             aria-labelledby="headingSystem" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة النظام:</h6>
                <a class="collapse-item {{ request()->routeIs('padges.*') ? 'active' : '' }}" href="{{ route('padges.index') }}">
                    <i class="fas fa-fw fa-medal"></i> الشارات
                </a>
                @can('roles.manage')
                    <a class="collapse-item {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <i class="fas fa-fw fa-user-shield"></i> الأدوار
                    </a>
                @endcan
                @can('users.manage')
                    <a class="collapse-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-fw fa-user-cog"></i> المستخدمون
                    </a>
                @endcan
            </div>
        </div>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        التقارير والسجلات
    </div>

    <!-- Nav Item - Visit Logs -->
    <li class="nav-item {{ request()->routeIs('dashboard.visit-logs.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard.visit-logs.index') }}">
            <i class="fas fa-fw fa-eye"></i>
            <span>سجل الزيارات</span>
        </a>
    </li>

    <!-- Nav Item - Reports and Logs -->
    <li class="nav-item {{ request()->routeIs('logs.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLogs"
           aria-expanded="{{ request()->routeIs('logs.*') ? 'true' : 'false' }}"
           aria-controls="collapseLogs">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>سجلات النظام</span>
        </a>
        <div id="collapseLogs" class="collapse {{ request()->routeIs('logs.*') ? 'show' : '' }}"
             aria-labelledby="headingLogs" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">سجلات النظام:</h6>
                <a class="collapse-item {{ request()->routeIs('logs.activity') ? 'active' : '' }}" href="{{ route('logs.activity') }}">
                    <i class="fas fa-fw fa-history"></i> سجل النشاطات
                </a>
                <a class="collapse-item {{ request()->routeIs('logs.audits') ? 'active' : '' }}" href="{{ route('logs.audits') }}">
                    <i class="fas fa-fw fa-clipboard-check"></i> سجلات التدقيق
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
