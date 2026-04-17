<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>

    <nav class="vertnav navbar navbar-light">

        {{-- ===== Brand ===== --}}
        <div class="w-100 mb-3 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ safe_route('dashboard') }}">
                <h2 class="h5 mb-0">
                    <i class="fe fe-home fe-16 mr-1"></i>
                    لوحة التحكم
                </h2>
            </a>
        </div>


        <ul class="navbar-nav flex-fill w-100 mb-2">
            @can('dashboard.view')
            <li class="nav-item w-100 {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ safe_route('dashboard') }}">
                    <i class="fe fe-pie-chart fe-16"></i>
                    <span class="ml-3 item-text">الإحصائيات</span>
                </a>
            </li>
            @endcan
        </ul>

        {{-- ==========================================
             إدارة العائلة (dropdown)
        =========================================== --}}
        @if (auth()->user()->can('people.view') ||
                auth()->user()->can('marriages.view') ||
                auth()->user()->can('breastfeeding.view') ||
                auth()->user()->can('locations.view') ||
                auth()->user()->can('friendships.view'))

            @php
                $familyActive = request()->routeIs(['people.*', 'marriages.*', 'breastfeeding.*', 'locations.*', 'friendships.*']);
            @endphp


            <ul class="navbar-nav flex-fill w-100 mb-2">
                <li class="nav-item w-100 {{ $familyActive ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                       href="#collapseFamily"
                       data-toggle="collapse"
                       aria-expanded="{{ $familyActive ? 'true' : 'false' }}"
                       aria-controls="collapseFamily">
                        <span class="d-flex align-items-center">
                            <i class="fe fe-git-merge fe-16"></i>
                            <span class="ml-3 item-text">إدارة العائلة</span>
                        </span>
                        <i class="fe fe-chevron-down fe-12 text-muted"></i>
                    </a>
                    <div id="collapseFamily" class="collapse {{ $familyActive ? 'show' : '' }}">
                        <ul class="navbar-nav flex-fill w-100">
                            @can('people.view')
                            <li class="nav-item w-100 {{ request()->routeIs('people.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('people.index') }}">
                                    <i class="fe fe-users fe-14"></i>
                                    <span class="ml-2 item-text">الشخصيات</span>
                                </a>
                            </li>
                            @endcan
                            @can('marriages.view')
                            <li class="nav-item w-100 {{ request()->routeIs('marriages.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('marriages.index') }}">
                                    <i class="fe fe-heart fe-14"></i>
                                    <span class="ml-2 item-text">الزواج</span>
                                </a>
                            </li>
                            @endcan
                            @can('breastfeeding.view')
                            @if(Route::has('breastfeeding.index'))
                            <li class="nav-item w-100 {{ request()->routeIs('breastfeeding.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('breastfeeding.index') }}">
                                    <i class="fe fe-shield fe-14"></i>
                                    <span class="ml-2 item-text">الرضاعة</span>
                                </a>
                            </li>
                            @endif
                            @endcan
                            @can('friendships.view')
                            <li class="nav-item w-100 {{ request()->routeIs('friendships.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('friendships.index') }}">
                                    <i class="fe fe-user-check fe-14"></i>
                                    <span class="ml-2 item-text">الأصدقاء</span>
                                </a>
                            </li>
                            @endcan
                            @can('locations.view')
                            <li class="nav-item w-100 {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('locations.index') }}">
                                    <i class="fe fe-map-pin fe-14"></i>
                                    <span class="ml-2 item-text">الأماكن</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            </ul>
        @endif

        {{-- ==========================================
             الصفحة الرئيسية (dropdown)
        =========================================== --}}
        @if (auth()->user()->can('site-content.view') ||
                auth()->user()->can('slideshow.view') ||
                auth()->user()->can('home-gallery.view') ||
                auth()->user()->can('courses.view') ||
                auth()->user()->can('programs.view') ||
                auth()->user()->can('councils.view'))

            @php
                $homeActive = request()->routeIs([
                    'dashboard.site-content.*', 'dashboard.slideshow.*', 'dashboard.home-gallery.*',
                    'dashboard.home-sections.*', 'dashboard.courses.*', 'dashboard.program-categories.*',
                    'dashboard.programs.*', 'dashboard.proud-of.*', 'dashboard.councils.*',
                    'dashboard.events.*', 'dashboard.family-news.*'
                ]);
            @endphp


            <ul class="navbar-nav flex-fill w-100 mb-2">
                <li class="nav-item w-100 {{ $homeActive ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                       href="#collapseHome"
                       data-toggle="collapse"
                       aria-expanded="{{ $homeActive ? 'true' : 'false' }}"
                       aria-controls="collapseHome">
                        <span class="d-flex align-items-center">
                            <i class="fe fe-layout fe-16"></i>
                            <span class="ml-3 item-text">الصفحة الرئيسية</span>
                        </span>
                        <i class="fe fe-chevron-down fe-12 text-muted"></i>
                    </a>
                    <div id="collapseHome" class="collapse {{ $homeActive ? 'show' : '' }}">
                        <ul class="navbar-nav flex-fill w-100">
                            @can('site-content.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.site-content.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.site-content.index') }}">
                                    <i class="fe fe-file-text fe-14"></i>
                                    <span class="ml-2 item-text">محتوى الصفحة</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.home-sections.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.home-sections.index') }}">
                                    <i class="fe fe-grid fe-14"></i>
                                    <span class="ml-2 item-text">الأقسام الديناميكية</span>
                                </a>
                            </li>
                            @endcan
                            @can('slideshow.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.slideshow.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.slideshow.index') }}">
                                    <i class="fe fe-monitor fe-14"></i>
                                    <span class="ml-2 item-text">السلايدشو</span>
                                </a>
                            </li>
                            @endcan
                            @can('home-gallery.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.home-gallery.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.home-gallery.index') }}">
                                    <i class="fe fe-image fe-14"></i>
                                    <span class="ml-2 item-text">صور الصفحة الرئيسية</span>
                                </a>
                            </li>
                            @endcan
                            @can('courses.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.courses.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.courses.index') }}">
                                    <i class="fe fe-book fe-14"></i>
                                    <span class="ml-2 item-text">الدورات</span>
                                </a>
                            </li>
                            @endcan
                            @can('programs.view')
                            <li class="nav-item w-100 {{ request()->routeIs(['dashboard.programs.*', 'dashboard.program-categories.*']) ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.programs.index') }}">
                                    <i class="fe fe-layers fe-14"></i>
                                    <span class="ml-2 item-text">البرامج</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.proud-of.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.proud-of.index') }}">
                                    <i class="fe fe-award fe-14"></i>
                                    <span class="ml-2 item-text">نفتخر بهم</span>
                                </a>
                            </li>
                            @endcan
                            @can('councils.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.councils.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.councils.index') }}">
                                    <i class="fe fe-users fe-14"></i>
                                    <span class="ml-2 item-text">مجالس العائلة</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.events.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.events.index') }}">
                                    <i class="fe fe-calendar fe-14"></i>
                                    <span class="ml-2 item-text">مناسبات العائلة</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.family-news.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.family-news.index') }}">
                                    <i class="fe fe-rss fe-14"></i>
                                    <span class="ml-2 item-text">أخبار العائلة</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            </ul>
        @endif

        {{-- ==========================================
             المحتوى والوسائط (dropdown)
        =========================================== --}}
        @if (auth()->user()->can('articles.view') ||
                auth()->user()->can('categories.view') ||
                auth()->user()->can('images.view') ||
                auth()->user()->can('stories.view'))

            @php
                $contentActive = request()->routeIs([
                    'articles.*', 'categories.*', 'dashboard.images.*', 'stories.*',
                    'dashboard.competitions.*', 'dashboard.quran-competitions.*',
                    'dashboard.quiz-competitions.*', 'dashboard.important-links.*',
                    'dashboard.sponsors.*'
                ]);
                $pendingImportantLinksCount = $pendingImportantLinksCount ?? 0;
            @endphp


            <ul class="navbar-nav flex-fill w-100 mb-2">
                <li class="nav-item w-100 {{ $contentActive ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                       href="#collapseContent"
                       data-toggle="collapse"
                       aria-expanded="{{ $contentActive ? 'true' : 'false' }}"
                       aria-controls="collapseContent">
                        <span class="d-flex align-items-center">
                            <i class="fe fe-folder fe-16"></i>
                            <span class="ml-3 item-text">المحتوى والوسائط</span>
                        </span>
                        @if ($pendingImportantLinksCount > 0)
                            <span class="badge badge-danger rounded-pill">{{ $pendingImportantLinksCount > 99 ? '99+' : $pendingImportantLinksCount }}</span>
                        @else
                            <i class="fe fe-chevron-down fe-12 text-muted"></i>
                        @endif
                    </a>
                    <div id="collapseContent" class="collapse {{ $contentActive ? 'show' : '' }}">
                        <ul class="navbar-nav flex-fill w-100">
                            @can('articles.view')
                            <li class="nav-item w-100 {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('articles.index') }}">
                                    <i class="fe fe-file-text fe-14"></i>
                                    <span class="ml-2 item-text">المقالات</span>
                                </a>
                            </li>
                            @endcan
                            @can('categories.view')
                            <li class="nav-item w-100 {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('categories.index') }}">
                                    <i class="fe fe-tag fe-14"></i>
                                    <span class="ml-2 item-text">الفئات</span>
                                </a>
                            </li>
                            @endcan
                            @can('images.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.images.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.images.index') }}">
                                    <i class="fe fe-image fe-14"></i>
                                    <span class="ml-2 item-text">مكتبة الصور</span>
                                </a>
                            </li>
                            @endcan
                            @can('stories.view')
                            @if(Route::has('stories.index'))
                            <li class="nav-item w-100 {{ request()->routeIs('stories.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('stories.index') }}">
                                    <i class="fe fe-book-open fe-14"></i>
                                    <span class="ml-2 item-text">القصص</span>
                                </a>
                            </li>
                            @endif
                            @endcan
                            @can('site-content.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.important-links.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5 d-flex align-items-center justify-content-between" href="{{ route('dashboard.important-links.index') }}">
                                    <span class="d-flex align-items-center">
                                        <i class="fe fe-link fe-14"></i>
                                        <span class="ml-2 item-text">الروابط المهمة</span>
                                    </span>
                                    @if ($pendingImportantLinksCount > 0)
                                        <span class="badge badge-danger rounded-pill">{{ $pendingImportantLinksCount }}</span>
                                    @endif
                                </a>
                            </li>
                            @endcan
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.quran-competitions.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.quran-competitions.index') }}">
                                    <i class="fe fe-star fe-14"></i>
                                    <span class="ml-2 item-text">مسابقة القرآن الكريم</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.competitions.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.competitions.index') }}">
                                    <i class="fe fe-zap fe-14"></i>
                                    <span class="ml-2 item-text">المسابقات</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.quiz-competitions.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.quiz-competitions.index') }}">
                                    <i class="fe fe-help-circle fe-14"></i>
                                    <span class="ml-2 item-text">مسابقات الأسئلة</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.sponsors.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.sponsors.index') }}">
                                    <i class="fe fe-briefcase fe-14"></i>
                                    <span class="ml-2 item-text">رعاة المسابقات</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        @endif

        {{-- ==========================================
             المتجر (dropdown)
        =========================================== --}}
        @can('products.view')
            @php $productsActive = request()->routeIs(['products.*', 'dashboard.products.*']); @endphp

            <ul class="navbar-nav flex-fill w-100 mb-2">
                <li class="nav-item w-100 {{ $productsActive ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                       href="#collapseProducts"
                       data-toggle="collapse"
                       aria-expanded="{{ $productsActive ? 'true' : 'false' }}"
                       aria-controls="collapseProducts">
                        <span class="d-flex align-items-center">
                            <i class="fe fe-shopping-bag fe-16"></i>
                            <span class="ml-3 item-text">الأسر المنتجة والأدوات المؤجرة</span>
                        </span>
                        <i class="fe fe-chevron-down fe-12 text-muted"></i>
                    </a>
                    <div id="collapseProducts" class="collapse {{ $productsActive ? 'show' : '' }}">
                        <ul class="navbar-nav flex-fill w-100">
                            @if(Route::has('products.index'))
                            <li class="nav-item w-100 {{ request()->routeIs('products.index') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('products.index') }}">
                                    <i class="fe fe-box fe-14"></i>
                                    <span class="ml-2 item-text">المنتجات</span>
                                </a>
                            </li>
                            @endif
                            @if(Route::has('products.categories.index'))
                            <li class="nav-item w-100 {{ request()->routeIs('products.categories.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('products.categories.index') }}">
                                    <i class="fe fe-tag fe-14"></i>
                                    <span class="ml-2 item-text">الفئات الرئيسية</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
            </ul>
        @endcan

        {{-- ==========================================
             الصحة واللياقة (dropdown)
        =========================================== --}}
        @php
            $healthActive = request()->routeIs(['dashboard.rental-requests.*', 'dashboard.health-websites.*', 'dashboard.walking.*']);
            $pendingRentalRequestsCount = $pendingRentalRequestsCount ?? 0;
        @endphp

        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 {{ $healthActive ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                   href="#collapseHealth"
                   data-toggle="collapse"
                   aria-expanded="{{ $healthActive ? 'true' : 'false' }}"
                   aria-controls="collapseHealth">
                    <span class="d-flex align-items-center">
                        <i class="fe fe-activity fe-16"></i>
                        <span class="ml-3 item-text">الصحة واللياقة</span>
                    </span>
                    @if ($pendingRentalRequestsCount > 0)
                        <span class="badge badge-danger rounded-pill">{{ $pendingRentalRequestsCount > 99 ? '99+' : $pendingRentalRequestsCount }}</span>
                    @else
                        <i class="fe fe-chevron-down fe-12 text-muted"></i>
                    @endif
                </a>
                <div id="collapseHealth" class="collapse {{ $healthActive ? 'show' : '' }}">
                    <ul class="navbar-nav flex-fill w-100">
                        @can('walking-program.view')
                        <li class="nav-item w-100 {{ request()->routeIs('dashboard.walking.*') ? 'active' : '' }}">
                            <a class="nav-link pl-5" href="{{ route('dashboard.walking.index') }}">
                                <i class="fe fe-trending-up fe-14"></i>
                                <span class="ml-2 item-text">برنامج المشي</span>
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item w-100 {{ request()->routeIs('dashboard.rental-requests.*') ? 'active' : '' }}">
                            <a class="nav-link pl-5 d-flex align-items-center justify-content-between" href="{{ route('dashboard.rental-requests.index') }}">
                                <span class="d-flex align-items-center">
                                    <i class="fe fe-home fe-14"></i>
                                    <span class="ml-2 item-text">طلبات الاستعارة</span>
                                </span>
                                @if ($pendingRentalRequestsCount > 0)
                                    <span class="badge badge-danger rounded-pill">{{ $pendingRentalRequestsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item w-100 {{ request()->routeIs('dashboard.health-websites.*') ? 'active' : '' }}">
                            <a class="nav-link pl-5" href="{{ route('dashboard.health-websites.index') }}">
                                <i class="fe fe-heart fe-14"></i>
                                <span class="ml-2 item-text">المواقع الصحية</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        {{-- ==========================================
             الإشعارات (dropdown)
        =========================================== --}}
        @can('notifications.view')
            @php
                $notifActive = request()->routeIs(['dashboard.notifications.*', 'dashboard.notification-groups.*']);
            @endphp

            <ul class="navbar-nav flex-fill w-100 mb-2">
                <li class="nav-item w-100 {{ $notifActive ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                       href="#collapseNotifications"
                       data-toggle="collapse"
                       aria-expanded="{{ $notifActive ? 'true' : 'false' }}"
                       aria-controls="collapseNotifications">
                        <span class="d-flex align-items-center">
                            <i class="fe fe-bell fe-16"></i>
                            <span class="ml-3 item-text">الإشعارات</span>
                        </span>
                        <i class="fe fe-chevron-down fe-12 text-muted"></i>
                    </a>
                    <div id="collapseNotifications" class="collapse {{ $notifActive ? 'show' : '' }}">
                        <ul class="navbar-nav flex-fill w-100">
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.notifications.index') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.notifications.index') }}">
                                    <i class="fe fe-home fe-14"></i>
                                    <span class="ml-2 item-text">الرئيسية</span>
                                </a>
                            </li>
                            @can('notifications.send')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.notifications.send') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.notifications.send') }}">
                                    <i class="fe fe-send fe-14"></i>
                                    <span class="ml-2 item-text">إرسال دعوة واتساب</span>
                                </a>
                            </li>
                            @endcan
                            @can('notifications.manage-groups')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.notification-groups.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.notification-groups.index') }}">
                                    <i class="fe fe-users fe-14"></i>
                                    <span class="ml-2 item-text">المجموعات</span>
                                </a>
                            </li>
                            @endcan
                            @if(Route::has('dashboard.notifications.logs'))
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.notifications.logs') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.notifications.logs') }}">
                                    <i class="fe fe-clock fe-14"></i>
                                    <span class="ml-2 item-text">سجل الإرسال</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
            </ul>
        @endcan

        {{-- ==========================================
             النظام والإعدادات (dropdown)
        =========================================== --}}
        @if (auth()->user()->can('padges.view') ||
                auth()->user()->can('roles.manage') ||
                auth()->user()->can('users.manage') ||
                auth()->user()->can('site-content.view'))

            @php
                $systemActive = request()->routeIs(['padges.*', 'roles.*', 'users.*', 'dashboard.site-password-settings.*']);
            @endphp


            <ul class="navbar-nav flex-fill w-100 mb-2">
                <li class="nav-item w-100 {{ $systemActive ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                       href="#collapseSystem"
                       data-toggle="collapse"
                       aria-expanded="{{ $systemActive ? 'true' : 'false' }}"
                       aria-controls="collapseSystem">
                        <span class="d-flex align-items-center">
                            <i class="fe fe-settings fe-16"></i>
                            <span class="ml-3 item-text">النظام والإعدادات</span>
                        </span>
                        <i class="fe fe-chevron-down fe-12 text-muted"></i>
                    </a>
                    <div id="collapseSystem" class="collapse {{ $systemActive ? 'show' : '' }}">
                        <ul class="navbar-nav flex-fill w-100">
                            @can('padges.view')
                            @if(Route::has('padges.index'))
                            <li class="nav-item w-100 {{ request()->routeIs('padges.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('padges.index') }}">
                                    <i class="fe fe-award fe-14"></i>
                                    <span class="ml-2 item-text">الشارات</span>
                                </a>
                            </li>
                            @endif
                            @endcan
                            @can('roles.manage')
                            <li class="nav-item w-100 {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('roles.index') }}">
                                    <i class="fe fe-shield fe-14"></i>
                                    <span class="ml-2 item-text">الأدوار</span>
                                </a>
                            </li>
                            @endcan
                            @can('users.manage')
                            <li class="nav-item w-100 {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('users.index') }}">
                                    <i class="fe fe-user-check fe-14"></i>
                                    <span class="ml-2 item-text">المستخدمون</span>
                                </a>
                            </li>
                            @endcan
                            @can('site-content.view')
                            <li class="nav-item w-100 {{ request()->routeIs('dashboard.site-password-settings.*') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('dashboard.site-password-settings.index') }}">
                                    <i class="fe fe-lock fe-14"></i>
                                    <span class="ml-2 item-text">إعدادات حماية الموقع</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            </ul>
        @endif



        @can('visit-logs.view')
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.visit-logs.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.visit-logs.index') }}">
                    <i class="fe fe-eye fe-16"></i>
                    <span class="ml-3 item-text">سجل الزيارات</span>
                </a>
            </li>
        </ul>
        @endcan

        @if (auth()->user()->can('logs.view') || auth()->user()->can('audit.view'))
            @php $logsActive = request()->routeIs('logs.*'); @endphp
            <ul class="navbar-nav flex-fill w-100 mb-2">
                <li class="nav-item w-100 {{ $logsActive ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center justify-content-between collapsed"
                       href="#collapseLogs"
                       data-toggle="collapse"
                       aria-expanded="{{ $logsActive ? 'true' : 'false' }}"
                       aria-controls="collapseLogs">
                        <span class="d-flex align-items-center">
                            <i class="fe fe-terminal fe-16"></i>
                            <span class="ml-3 item-text">سجلات النظام</span>
                        </span>
                        <i class="fe fe-chevron-down fe-12 text-muted"></i>
                    </a>
                    <div id="collapseLogs" class="collapse {{ $logsActive ? 'show' : '' }}">
                        <ul class="navbar-nav flex-fill w-100">
                            @can('logs.view')
                            @if(Route::has('logs.activity'))
                            <li class="nav-item w-100 {{ request()->routeIs('logs.activity') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('logs.activity') }}">
                                    <i class="fe fe-clock fe-14"></i>
                                    <span class="ml-2 item-text">سجل النشاطات</span>
                                </a>
                            </li>
                            @endif
                            @endcan
                            @can('audit.view')
                            @if(Route::has('logs.audits'))
                            <li class="nav-item w-100 {{ request()->routeIs('logs.audits') ? 'active' : '' }}">
                                <a class="nav-link pl-5" href="{{ route('logs.audits') }}">
                                    <i class="fe fe-check-square fe-14"></i>
                                    <span class="ml-2 item-text">سجلات التدقيق</span>
                                </a>
                            </li>
                            @endif
                            @endcan
                        </ul>
                    </div>
                </li>
            </ul>
        @endif

        {{-- ==========================================
             رابط عرض الموقع
        =========================================== --}}
        <ul class="navbar-nav flex-fill w-100 mt-2 mb-4">
            <li class="nav-item w-100">
                <a class="nav-link" href="{{ Route::has('home') ? route('home') : url('/') }}" target="_blank" rel="noopener noreferrer">
                    <i class="fe fe-external-link fe-16"></i>
                    <span class="ml-3 item-text">عرض الموقع</span>
                </a>
            </li>
        </ul>

    </nav>
</aside>