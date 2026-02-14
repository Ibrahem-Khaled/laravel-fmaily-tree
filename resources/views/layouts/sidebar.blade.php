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
    @can('dashboard.view')
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>لوحة التحكم</span></a>
        </li>
    @endcan

    <!-- Heading -->
    @if (auth()->user()->can('people.view') ||
            auth()->user()->can('marriages.view') ||
            auth()->user()->can('breastfeeding.view') ||
            auth()->user()->can('locations.view') ||
            auth()->user()->can('friendships.view'))
        <div class="sidebar-heading">
            إدارة العائلة
        </div>
    @endif

    <!-- Nav Item - Family Management Collapse -->
    @if (auth()->user()->can('people.view') ||
            auth()->user()->can('marriages.view') ||
            auth()->user()->can('breastfeeding.view') ||
            auth()->user()->can('locations.view') ||
            auth()->user()->can('friendships.view'))
        <li
            class="nav-item {{ request()->routeIs(['people.*', 'marriages.*', 'breastfeeding.*', 'locations.*', 'friendships.*', 'dashboard.quran-competitions.*']) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFamily"
                aria-expanded="{{ request()->routeIs(['people.*', 'marriages.*', 'breastfeeding.*', 'locations.*', 'friendships.*', 'dashboard.quran-competitions.*']) ? 'true' : 'false' }}"
                aria-controls="collapseFamily">
                <i class="fas fa-fw fa-sitemap"></i>
                <span>إدارة العائلة</span>
            </a>
            <div id="collapseFamily"
                class="collapse {{ request()->routeIs(['people.*', 'marriages.*', 'breastfeeding.*', 'locations.*', 'friendships.*', 'dashboard.quran-competitions.*']) ? 'show' : '' }}"
                aria-labelledby="headingFamily" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">إدارة العائلة:</h6>
                    @can('people.view')
                        <a class="collapse-item {{ request()->routeIs('people.*') ? 'active' : '' }}"
                            href="{{ route('people.index') }}">
                            <i class="fas fa-fw fa-users"></i> الشخصيات
                        </a>
                    @endcan
                    @can('marriages.view')
                        <a class="collapse-item {{ request()->routeIs('marriages.*') ? 'active' : '' }}"
                            href="{{ route('marriages.index') }}">
                            <i class="fas fa-fw fa-heart"></i> الزواج
                        </a>
                    @endcan
                    @can('breastfeeding.view')
                        <a class="collapse-item {{ request()->routeIs('breastfeeding.*') ? 'active' : '' }}"
                            href="{{ route('breastfeeding.index') }}">
                            <i class="fas fa-fw fa-baby"></i> الرضاعة
                        </a>
                    @endcan

                    @can('friendships.view')
                        <a class="collapse-item {{ request()->routeIs('friendships.*') ? 'active' : '' }}"
                            href="{{ route('friendships.index') }}">
                            <i class="fas fa-fw fa-user-friends"></i> الأصدقاء
                        </a>
                    @endcan
                    @can('locations.view')
                        <a class="collapse-item {{ request()->routeIs('locations.*') ? 'active' : '' }}"
                            href="{{ route('locations.index') }}">
                            <i class="fas fa-fw fa-map-marker-alt"></i> الأماكن
                        </a>
                    @endcan
                </div>
            </div>
        </li>
    @endif

    <!-- Heading -->
    @if (auth()->user()->can('site-content.view') ||
            auth()->user()->can('slideshow.view') ||
            auth()->user()->can('home-gallery.view') ||
            auth()->user()->can('courses.view') ||
            auth()->user()->can('programs.view') ||
            auth()->user()->can('councils.view'))
        <div class="sidebar-heading">
            الصفحة الرئيسية
        </div>
    @endif

    <!-- Nav Item - Home Page Management -->
    @if (auth()->user()->can('site-content.view') ||
            auth()->user()->can('slideshow.view') ||
            auth()->user()->can('home-gallery.view') ||
            auth()->user()->can('courses.view') ||
            auth()->user()->can('programs.view') ||
            auth()->user()->can('councils.view'))
        <li
            class="nav-item {{ request()->routeIs(['dashboard.site-content.*', 'dashboard.slideshow.*', 'dashboard.home-gallery.*', 'dashboard.courses.*', 'dashboard.programs.*', 'dashboard.proud-of.*', 'dashboard.councils.*', 'dashboard.family-news.*']) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHome"
                aria-expanded="{{ request()->routeIs(['dashboard.site-content.*', 'dashboard.slideshow.*', 'dashboard.home-gallery.*', 'dashboard.courses.*', 'dashboard.programs.*', 'dashboard.proud-of.*', 'dashboard.councils.*', 'dashboard.family-news.*']) ? 'true' : 'false' }}"
                aria-controls="collapseHome">
                <i class="fas fa-fw fa-home"></i>
                <span>الصفحة الرئيسية</span>
            </a>
            <div id="collapseHome"
                class="collapse {{ request()->routeIs(['dashboard.site-content.*', 'dashboard.home-sections.*', 'dashboard.slideshow.*', 'dashboard.home-gallery.*', 'dashboard.courses.*', 'dashboard.programs.*', 'dashboard.proud-of.*', 'dashboard.councils.*', 'dashboard.events.*', 'dashboard.family-news.*']) ? 'show' : '' }}"
                aria-labelledby="headingHome" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">إدارة الصفحة الرئيسية:</h6>
                    @can('site-content.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.site-content.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.site-content.index') }}">
                            <i class="fas fa-fw fa-file-alt"></i> محتوى الصفحة
                        </a>
                    @endcan
                    @can('site-content.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.home-sections.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.home-sections.index') }}">
                            <i class="fas fa-fw fa-th-large"></i> الأقسام الديناميكية
                        </a>
                    @endcan
                    @can('slideshow.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.slideshow.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.slideshow.index') }}">
                            <i class="fas fa-fw fa-sliders-h"></i> السلايدشو
                        </a>
                    @endcan
                    @can('home-gallery.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.home-gallery.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.home-gallery.index') }}">
                            <i class="fas fa-fw fa-images"></i> صور الصفحة الرئيسية
                        </a>
                    @endcan
                    @can('courses.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.courses.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.courses.index') }}">
                            <i class="fas fa-fw fa-graduation-cap"></i> الدورات
                        </a>
                    @endcan
                    @can('programs.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.programs.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.programs.index') }}">
                            <i class="fas fa-fw fa-tv"></i> البرامج
                        </a>
                    @endcan
                    @can('programs.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.proud-of.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.proud-of.index') }}">
                            <i class="fas fa-fw fa-star"></i> نفتخر بهم
                        </a>
                    @endcan

                    @can('councils.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.councils.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.councils.index') }}">
                            <i class="fas fa-fw fa-building"></i> مجالس العائلة
                        </a>
                    @endcan
                    @can('councils.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.events.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.events.index') }}">
                            <i class="fas fa-fw fa-calendar-alt"></i> مناسبات العائلة
                        </a>
                    @endcan
                    @can('councils.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.family-news.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.family-news.index') }}">
                            <i class="fas fa-fw fa-newspaper"></i> أخبار العائلة
                        </a>
                    @endcan
                </div>
            </div>
        </li>
    @endif

    <!-- Heading -->
    @if (auth()->user()->can('articles.view') ||
            auth()->user()->can('categories.view') ||
            auth()->user()->can('images.view') ||
            auth()->user()->can('stories.view'))
        <div class="sidebar-heading">
            المحتوى والوسائط
        </div>
    @endif

    <!-- Nav Item - Content Management Collapse -->
    @if (auth()->user()->can('articles.view') ||
            auth()->user()->can('categories.view') ||
            auth()->user()->can('images.view') ||
            auth()->user()->can('stories.view'))
        <li
            class="nav-item {{ request()->routeIs(['articles.*', 'categories.*', 'dashboard.images.*', 'stories.*', 'dashboard.competitions.*', 'dashboard.quran-competitions.*', 'dashboard.quiz-competitions.*']) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContent"
                aria-expanded="{{ request()->routeIs(['articles.*', 'categories.*', 'dashboard.images.*', 'stories.*', 'dashboard.competitions.*', 'dashboard.quran-competitions.*', 'dashboard.quiz-competitions.*']) ? 'true' : 'false' }}"
                aria-controls="collapseContent">
                <i class="fas fa-fw fa-folder"></i>
                <span>المحتوى والوسائط</span>
                @if (isset($pendingImportantLinksCount) && $pendingImportantLinksCount > 0)
                    <span class="badge badge-danger badge-counter" title="روابط مهمة بانتظار الموافقة"
                        style="font-size: 0.7rem; padding: 0.2rem 0.45rem; border-radius: 0.35rem; margin-right: 0.5rem;">{{ $pendingImportantLinksCount }}</span>
                @endif
            </a>
            <div id="collapseContent"
                class="collapse {{ request()->routeIs(['articles.*', 'categories.*', 'dashboard.images.*', 'stories.*', 'dashboard.competitions.*', 'dashboard.quran-competitions.*', 'dashboard.quiz-competitions.*']) ? 'show' : '' }}"
                aria-labelledby="headingContent" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">إدارة المحتوى:</h6>
                    @can('articles.view')
                        <a class="collapse-item {{ request()->routeIs('articles.*') ? 'active' : '' }}"
                            href="{{ route('articles.index') }}">
                            <i class="fas fa-fw fa-book"></i> المقالات
                        </a>
                    @endcan
                    @can('categories.view')
                        <a class="collapse-item {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">
                            <i class="fas fa-fw fa-tags"></i> الفئات
                        </a>
                    @endcan
                    @can('images.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.images.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.images.index') }}">
                            <i class="fas fa-fw fa-images"></i> مكتبة الصور
                        </a>
                    @endcan
                    @can('stories.view')
                        <a class="collapse-item {{ request()->routeIs('stories.*') ? 'active' : '' }}"
                            href="{{ route('stories.index') }}">
                            <i class="fas fa-fw fa-book-open"></i> القصص
                        </a>
                    @endcan

                    @can('site-content.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.important-links.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.important-links.index') }}">
                            <i class="fas fa-fw fa-link"></i> الروابط المهمة
                            @if (isset($pendingImportantLinksCount) && $pendingImportantLinksCount > 0)
                                <span class="badge badge-danger badge-counter float-left" title="اقتراحات بانتظار الموافقة"
                                    style="font-size: 0.7rem; padding: 0.2rem 0.45rem; border-radius: 0.35rem;">{{ $pendingImportantLinksCount }}</span>
                            @endif
                        </a>
                    @endcan

                    <a class="collapse-item {{ request()->routeIs('dashboard.quran-competitions.*') ? 'active' : '' }}"
                        href="{{ route('dashboard.quran-competitions.index') }}">
                        <i class="fas fa-fw fa-quran"></i> مسابقة القرآن الكريم
                    </a>
                    <a class="collapse-item {{ request()->routeIs('dashboard.competitions.*') ? 'active' : '' }}"
                        href="{{ route('dashboard.competitions.index') }}">
                        <i class="fas fa-fw fa-trophy"></i> المسابقات
                    </a>
                    <a class="collapse-item {{ request()->routeIs('dashboard.quiz-competitions.*') ? 'active' : '' }}"
                        href="{{ route('dashboard.quiz-competitions.index') }}">
                        <i class="fas fa-fw fa-question-circle"></i> مسابقات الأسئلة
                    </a>
                </div>
            </div>
        </li>
    @endif

    <!-- Heading -->
    @can('products.view')
        <div class="sidebar-heading">
            المتجر
        </div>
    @endcan

    <!-- Nav Item - Products Store Collapse -->
    @can('products.view')
        <li class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts"
                aria-expanded="{{ request()->routeIs('products.*') ? 'true' : 'false' }}"
                aria-controls="collapseProducts">
                <i class="fas fa-fw fa-shopping-bag"></i>
                <span>الاسر منتجة والدوات الرياضية المؤجرة</span>
            </a>
            <div id="collapseProducts" class="collapse {{ request()->routeIs('products.*') ? 'show' : '' }}"
                aria-labelledby="headingProducts" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">إدارة المتجر:</h6>
                    <a class="collapse-item {{ request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">
                        <i class="fas fa-fw fa-box"></i> المنتجات
                    </a>
                    <a class="collapse-item {{ request()->routeIs('products.categories.*') ? 'active' : '' }}"
                        href="{{ route('products.categories.index') }}">
                        <i class="fas fa-fw fa-tags"></i> الفئات الرئيسية
                    </a>
                    <a class="collapse-item {{ request()->routeIs('products.subcategories.*') ? 'active' : '' }}"
                        href="{{ route('products.subcategories.index') }}">
                        <i class="fas fa-fw fa-layer-group"></i> الفئات الفرعية
                    </a>
                </div>
            </div>
        </li>
    @endcan

    <!-- Heading -->
    <div class="sidebar-heading">
        الصحة واللياقة
    </div>

    <!-- Nav Item - Health & Fitness Collapse -->
    <li
        class="nav-item {{ request()->routeIs(['dashboard.rental-requests.*', 'dashboard.health-websites.*', 'dashboard.walking.*']) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHealthFitness"
            aria-expanded="{{ request()->routeIs(['dashboard.rental-requests.*', 'dashboard.health-websites.*', 'dashboard.walking.*']) ? 'true' : 'false' }}"
            aria-controls="collapseHealthFitness">
            <i class="fas fa-fw fa-heartbeat"></i>
            <span>الصحة واللياقة</span>
            @if (isset($pendingRentalRequestsCount) && $pendingRentalRequestsCount > 0)
                <span class="badge badge-danger badge-counter"
                    style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 0.35rem; margin-right: 0.5rem;">{{ $pendingRentalRequestsCount }}</span>
            @endif
        </a>
        <div id="collapseHealthFitness"
            class="collapse {{ request()->routeIs(['dashboard.rental-requests.*', 'dashboard.health-websites.*', 'dashboard.walking.*']) ? 'show' : '' }}"
            aria-labelledby="headingHealthFitness" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة الصحة واللياقة:</h6>
                @can('walking-program.view')
                    <a class="collapse-item {{ request()->routeIs('dashboard.walking.*') ? 'active' : '' }}"
                        href="{{ route('dashboard.walking.index') }}">
                        <i class="fas fa-fw fa-walking"></i> برنامج المشي
                    </a>
                @endcan
                <a class="collapse-item {{ request()->routeIs('dashboard.rental-requests.*') ? 'active' : '' }}"
                    href="{{ route('dashboard.rental-requests.index') }}">
                    <i class="fas fa-fw fa-hand-holding"></i> طلبات الاستعارة
                </a>
                <a class="collapse-item {{ request()->routeIs('dashboard.health-websites.*') ? 'active' : '' }}"
                    href="{{ route('dashboard.health-websites.index') }}">
                    <i class="fas fa-fw fa-globe"></i> المواقع الصحية
                </a>
            </div>
        </div>
    </li>

    <!-- Heading - الاشعارات -->
    @can('notifications.view')
        <div class="sidebar-heading">
            الاشعارات
        </div>
    @endcan

    <!-- Nav Item - Notifications Collapse -->
    @can('notifications.view')
        <li
            class="nav-item {{ request()->routeIs(['dashboard.notifications.*', 'dashboard.notification-groups.*']) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNotifications"
                aria-expanded="{{ request()->routeIs(['dashboard.notifications.*', 'dashboard.notification-groups.*']) ? 'true' : 'false' }}"
                aria-controls="collapseNotifications">
                <i class="fas fa-fw fa-bell"></i>
                <span>الاشعارات</span>
            </a>
            <div id="collapseNotifications"
                class="collapse {{ request()->routeIs(['dashboard.notifications.*', 'dashboard.notification-groups.*']) ? 'show' : '' }}"
                aria-labelledby="headingNotifications" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">الاشعارات:</h6>
                    <a class="collapse-item {{ request()->routeIs('dashboard.notifications.index') ? 'active' : '' }}"
                        href="{{ route('dashboard.notifications.index') }}">
                        <i class="fas fa-fw fa-home"></i> الرئيسية
                    </a>
                    @can('notifications.send')
                        <a class="collapse-item {{ request()->routeIs('dashboard.notifications.send') ? 'active' : '' }}"
                            href="{{ route('dashboard.notifications.send') }}">
                            <i class="fas fa-fw fa-paper-plane"></i> إرسال دعوة واتساب
                        </a>
                    @endcan
                    @can('notifications.manage-groups')
                        <a class="collapse-item {{ request()->routeIs('dashboard.notification-groups.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.notification-groups.index') }}">
                            <i class="fas fa-fw fa-users"></i> المجموعات
                        </a>
                    @endcan
                    <a class="collapse-item {{ request()->routeIs('dashboard.notifications.logs') ? 'active' : '' }}"
                        href="{{ route('dashboard.notifications.logs') }}">
                        <i class="fas fa-fw fa-history"></i> سجل الإرسال
                    </a>
                </div>
            </div>
        </li>
    @endcan

    <!-- Heading -->
    @if (auth()->user()->can('padges.view') ||
            auth()->user()->can('roles.manage') ||
            auth()->user()->can('users.manage') ||
            auth()->user()->can('site-content.view'))
        <div class="sidebar-heading">
            النظام والإعدادات
        </div>
    @endif

    <!-- Nav Item - System Management Collapse -->
    @if (auth()->user()->can('padges.view') ||
            auth()->user()->can('roles.manage') ||
            auth()->user()->can('users.manage') ||
            auth()->user()->can('site-content.view'))
        <li
            class="nav-item {{ request()->routeIs(['padges.*', 'roles.*', 'users.*', 'dashboard.site-password-settings.*']) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSystem"
                aria-expanded="{{ request()->routeIs(['padges.*', 'roles.*', 'users.*', 'dashboard.site-password-settings.*']) ? 'true' : 'false' }}"
                aria-controls="collapseSystem">
                <i class="fas fa-fw fa-cogs"></i>
                <span>النظام والإعدادات</span>
            </a>
            <div id="collapseSystem"
                class="collapse {{ request()->routeIs(['padges.*', 'roles.*', 'users.*', 'dashboard.site-password-settings.*']) ? 'show' : '' }}"
                aria-labelledby="headingSystem" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">إدارة النظام:</h6>
                    @can('padges.view')
                        <a class="collapse-item {{ request()->routeIs('padges.*') ? 'active' : '' }}"
                            href="{{ route('padges.index') }}">
                            <i class="fas fa-fw fa-medal"></i> الشارات
                        </a>
                    @endcan
                    @can('roles.manage')
                        <a class="collapse-item {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                            href="{{ route('roles.index') }}">
                            <i class="fas fa-fw fa-user-shield"></i> الأدوار
                        </a>
                    @endcan
                    @can('users.manage')
                        <a class="collapse-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="fas fa-fw fa-user-cog"></i> المستخدمون
                        </a>
                    @endcan
                    @can('site-content.view')
                        <a class="collapse-item {{ request()->routeIs('dashboard.site-password-settings.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.site-password-settings.index') }}">
                            <i class="fas fa-fw fa-shield-alt"></i> إعدادات حماية الموقع
                        </a>
                    @endcan
                </div>
            </div>
        </li>
    @endif

    <!-- Heading -->
    @if (auth()->user()->can('visit-logs.view') || auth()->user()->can('logs.view') || auth()->user()->can('audit.view'))
        <div class="sidebar-heading">
            التقارير والسجلات
        </div>
    @endif

    <!-- Nav Item - Visit Logs -->
    @can('visit-logs.view')
        <li class="nav-item {{ request()->routeIs('dashboard.visit-logs.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.visit-logs.index') }}">
                <i class="fas fa-fw fa-eye"></i>
                <span>سجل الزيارات</span>
            </a>
        </li>
    @endcan

    <!-- Nav Item - Reports and Logs -->
    @if (auth()->user()->can('logs.view') || auth()->user()->can('audit.view'))
        <li class="nav-item {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLogs"
                aria-expanded="{{ request()->routeIs('logs.*') ? 'true' : 'false' }}" aria-controls="collapseLogs">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>سجلات النظام</span>
            </a>
            <div id="collapseLogs" class="collapse {{ request()->routeIs('logs.*') ? 'show' : '' }}"
                aria-labelledby="headingLogs" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">سجلات النظام:</h6>
                    @can('logs.view')
                        <a class="collapse-item {{ request()->routeIs('logs.activity') ? 'active' : '' }}"
                            href="{{ route('logs.activity') }}">
                            <i class="fas fa-fw fa-history"></i> سجل النشاطات
                        </a>
                    @endcan
                    @can('audit.view')
                        <a class="collapse-item {{ request()->routeIs('logs.audits') ? 'active' : '' }}"
                            href="{{ route('logs.audits') }}">
                            <i class="fas fa-fw fa-clipboard-check"></i> سجلات التدقيق
                        </a>
                    @endcan
                </div>
            </div>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
