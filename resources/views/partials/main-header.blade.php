{{-- ====================================================================== --}}
{{-- | Header Component - Clean & Modern Design | --}}
{{-- ====================================================================== --}}

<style>
    /* ===== CSS Variables ===== */
    :root {
        --header-bg: #145147;
        --header-accent: #37a05c;
        --header-text: rgba(255, 255, 255, 0.85);
        --header-text-hover: #ffffff;
        --header-height: 70px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ===== Header Container ===== */
    .custom-header {
        background: linear-gradient(135deg, var(--header-bg) 0%, #0f3d35 100%);
        height: var(--header-height);
        width: 100%;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        border-bottom: 3px solid var(--header-accent);
    }

    .header-container {
        max-width: 1400px;
        margin: 0 auto;
        height: 100%;
        padding: 0 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        position: relative;
    }

    /* Separator between brand and navigation */
    .header-separator {
        width: 2px;
        height: 50%;
        background: linear-gradient(180deg,
                transparent 0%,
                rgba(255, 255, 255, 0.15) 20%,
                rgba(255, 255, 255, 0.25) 50%,
                rgba(255, 255, 255, 0.15) 80%,
                transparent 100%);
        border-radius: 2px;
        flex-shrink: 0;
    }

    /* ===== Brand Logo - Enhanced Design ===== */
    .header-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--header-text-hover);
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        transition: var(--transition);
        white-space: nowrap;
        position: relative;
        background: linear-gradient(135deg, rgba(55, 160, 92, 0.25) 0%, rgba(55, 160, 92, 0.15) 100%);
        border: 2px solid rgba(55, 160, 92, 0.4);
        box-shadow:
            0 4px 12px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.15),
            0 0 20px rgba(55, 160, 92, 0.1);
        overflow: hidden;
        flex-shrink: 0;
        margin-right: auto;
        min-width: 0;
    }

    .header-brand::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .header-brand:hover::before {
        left: 100%;
    }

    .header-brand:hover {
        background: linear-gradient(135deg, rgba(55, 160, 92, 0.35) 0%, rgba(55, 160, 92, 0.25) 100%);
        border-color: rgba(55, 160, 92, 0.6);
        transform: translateY(-2px) scale(1.02);
        box-shadow:
            0 6px 16px rgba(0, 0, 0, 0.25),
            inset 0 1px 0 rgba(255, 255, 255, 0.25),
            0 0 30px rgba(55, 160, 92, 0.2);
        color: var(--header-text-hover);
    }

    .header-brand:active {
        transform: translateY(0);
    }

    .header-brand i {
        font-size: 1.5rem;
        color: var(--header-accent);
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        transition: var(--transition);
        position: relative;
        z-index: 1;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.8;
            transform: scale(1.05);
        }
    }

    .header-brand:hover i {
        animation: none;
        transform: rotate(15deg) scale(1.15);
        color: #4fd675;
        filter: drop-shadow(0 3px 6px rgba(79, 214, 117, 0.4));
    }

    .header-brand span {
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        letter-spacing: 0.5px;
    }

    /* ===== Navigation ===== */
    .header-nav {
        display: flex;
        align-items: center;
        flex: 1;
        justify-content: flex-end;
        gap: 0.5rem;
        min-width: 0;
    }

    .header-nav-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0.5rem;
        align-items: center;
        flex: 1;
        justify-content: flex-end;
        min-width: 0;
    }

    .header-nav-list>li {
        display: flex;
        flex: 0 1 auto;
        min-width: 0;
    }

    .header-nav .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1rem;
        color: var(--header-text);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        position: relative;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid transparent;
        width: 100%;
        text-align: center;
    }

    .header-nav .nav-link i {
        font-size: 1rem;
        transition: transform 0.3s ease;
    }

    .header-nav .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: var(--header-text-hover);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .header-nav .nav-link:hover i {
        transform: scale(1.1);
    }

    .header-nav .nav-link.active {
        background: linear-gradient(135deg, rgba(55, 160, 92, 0.25) 0%, rgba(55, 160, 92, 0.15) 100%);
        color: var(--header-text-hover);
        font-weight: 600;
        border-color: rgba(55, 160, 92, 0.4);
        box-shadow: 0 4px 12px rgba(55, 160, 92, 0.2);
    }

    .header-nav .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 70%;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--header-accent), transparent);
        border-radius: 3px;
        box-shadow: 0 0 8px var(--header-accent);
    }

    /* ===== Dropdown ===== */
    .header-nav .dropdown {
        position: relative;
    }

    .header-nav .dropdown-toggle {
        cursor: pointer;
    }

    .header-nav .dropdown-toggle::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.4rem;
        font-size: 0.75rem;
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .header-nav .dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }

    .header-nav .dropdown-menu {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        min-width: 280px;
        background: linear-gradient(135deg, var(--header-bg) 0%, #0f3d35 100%);
        border-radius: 12px;
        padding: 0.75rem 0;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-12px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1001;
        display: none;
        backdrop-filter: blur(10px);
    }

    .header-nav .dropdown-menu.show {
        display: block;
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .header-nav .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.5rem;
        color: var(--header-text);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        background: none;
        width: 100%;
        text-align: right;
        position: relative;
        margin: 0.15rem 0.5rem;
        border-radius: 8px;
    }

    .header-nav .dropdown-item::before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: var(--header-accent);
        border-radius: 0 4px 4px 0;
        transition: height 0.3s ease;
    }

    .header-nav .dropdown-item i {
        font-size: 1.1rem;
        width: 22px;
        text-align: center;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .header-nav .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.12);
        color: var(--header-text-hover);
        transform: translateX(-5px);
        padding-right: 1.75rem;
    }

    .header-nav .dropdown-item:hover::before {
        height: 60%;
    }

    .header-nav .dropdown-item:hover i {
        transform: scale(1.15);
        color: var(--header-accent);
    }

    .header-nav .dropdown-item.active {
        background: linear-gradient(90deg, rgba(55, 160, 92, 0.2) 0%, rgba(55, 160, 92, 0.1) 100%);
        color: var(--header-text-hover);
        font-weight: 600;
    }

    .header-nav .dropdown-item.active::before {
        height: 70%;
        background: var(--header-accent);
        box-shadow: 0 0 8px var(--header-accent);
    }

    .header-nav .dropdown-divider {
        height: 1px;
        margin: 0.5rem 0;
        background: rgba(255, 255, 255, 0.15);
        border: none;
    }

    /* ===== Mobile Menu Toggle ===== */
    .mobile-menu-toggle {
        display: none;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 0.5rem;
        cursor: pointer;
        flex-direction: column;
        gap: 5px;
        width: 44px;
        height: 44px;
        justify-content: center;
        align-items: center;
        transition: var(--transition);
    }

    .mobile-menu-toggle:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .mobile-menu-toggle .icon-bar {
        width: 24px;
        height: 3px;
        background: var(--header-text-hover);
        border-radius: 3px;
        transition: var(--transition);
    }

    .mobile-menu-toggle.active {
        background: rgba(55, 160, 92, 0.2);
        border-color: var(--header-accent);
    }

    .mobile-menu-toggle.active .icon-bar:nth-child(1) {
        transform: rotate(45deg) translate(6px, 6px);
    }

    .mobile-menu-toggle.active .icon-bar:nth-child(2) {
        opacity: 0;
    }

    .mobile-menu-toggle.active .icon-bar:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -7px);
    }

    /* ===== Responsive Design - Mobile First ===== */
    @media (max-width: 992px) {
        .header-container {
            padding: 0 1rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .mobile-menu-toggle {
            order: -1;
            margin-left: auto;
            z-index: 1001;
        }

        .header-nav {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-separator {
            display: none;
        }

        .header-brand {
            font-size: 1rem;
            padding: 0.55rem 1rem;
            gap: 0.6rem;
        }

        .header-brand i {
            font-size: 1.2rem;
            animation: none;
        }

        .header-nav {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 1rem;
            right: 1rem;
            background: var(--header-bg);
            flex-direction: column;
            padding: 0.75rem 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 2px solid var(--header-accent);
            border-radius: 12px;
            max-height: 70vh;
            overflow-y: auto;
            z-index: 999;
        }

        .header-nav.is-open {
            display: flex;
            animation: slideDown 0.3s ease-out;
        }

        .header-nav-list {
            flex-direction: column;
            width: 100%;
            gap: 0.3rem;
            padding: 0.5rem;
            flex-wrap: nowrap;
        }

        .header-nav-list>li {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .header-nav .nav-link {
            width: 100%;
            margin: 0;
            padding: 0.85rem 1.5rem;
            justify-content: flex-start;
            border-radius: 8px;
            border-bottom: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .header-nav .dropdown {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .header-nav .nav-link i {
            font-size: 1rem;
            width: 24px;
            text-align: center;
            margin-left: 0.5rem;
        }

        .header-nav .nav-link:last-child {
            border-bottom: none;
        }

        .header-nav .nav-link.active {
            background: rgba(55, 160, 92, 0.25);
            border-right: 3px solid var(--header-accent);
        }

        .header-nav .nav-link.active::after {
            display: none;
        }

        /* Dropdown on Mobile - Always Hidden by Default */
        .header-nav .dropdown {
            width: 100%;
        }

        .header-nav .dropdown-menu {
            position: static;
            display: none !important;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-5px);
            box-shadow: none;
            border: none;
            border-radius: 8px;
            margin: 0.5rem 0 0 0;
            padding: 0;
            background: rgba(0, 0, 0, 0.25);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                padding 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                margin 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            border-radius: 0 0 8px 8px;
        }

        .header-nav .dropdown-menu.show {
            display: block !important;
            max-height: 500px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .header-nav .dropdown-item {
            padding: 0.75rem 2rem;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            border-radius: 0;
            margin: 0;
            background: rgba(0, 0, 0, 0.15);
        }

        .header-nav .dropdown-item:first-child {
            border-radius: 0;
        }

        .header-nav .dropdown-item:last-child {
            border-radius: 0 0 8px 8px;
        }

        .header-nav .dropdown-item i {
            font-size: 0.9rem;
            width: 20px;
            text-align: center;
            margin-left: 0.5rem;
        }

        .header-nav .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(0);
            padding-right: 2rem;
        }

        .header-nav .dropdown-divider {
            margin: 0.4rem 1rem;
        }

        /* Scrollbar styling for mobile menu */
        .header-nav::-webkit-scrollbar {
            width: 6px;
        }

        .header-nav::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .header-nav::-webkit-scrollbar-thumb {
            background: var(--header-accent);
            border-radius: 10px;
        }

        .header-nav::-webkit-scrollbar-thumb:hover {
            background: #4fd675;
        }

        .mobile-menu-toggle {
            display: flex;
        }
    }

    @media (max-width: 768px) {
        .header-container {
            padding: 0 0.75rem;
        }

        .header-brand {
            font-size: 0.9rem;
            padding: 0.5rem 0.85rem;
            gap: 0.5rem;
        }

        .header-brand i {
            font-size: 1.1rem;
        }

        .header-brand span {
            font-size: 0.9rem;
        }

        .header-nav {
            left: 0.75rem;
            right: 0.75rem;
            max-height: 65vh;
            padding: 0.6rem 0;
        }

        .header-nav-list {
            padding: 0.4rem;
            gap: 0.25rem;
        }

        .header-nav .nav-link {
            font-size: 0.88rem;
            padding: 0.65rem 0.9rem;
            width: calc(100% - 0.8rem);
        }

        .header-nav .dropdown-menu {
            width: calc(100% - 0.8rem);
        }

        .header-nav .dropdown-item {
            padding: 0.6rem 1.25rem;
            font-size: 0.83rem;
        }

        .mobile-menu-toggle {
            width: 40px;
            height: 40px;
        }

        .mobile-menu-toggle .icon-bar {
            width: 22px;
            height: 2.5px;
        }
    }

    @media (max-width: 480px) {
        .header-container {
            padding: 0 0.5rem;
        }

        .header-brand {
            font-size: 0.85rem;
            padding: 0.45rem 0.75rem;
            gap: 0.4rem;
        }

        .header-brand i {
            font-size: 1rem;
        }

        .header-brand span {
            font-size: 0.85rem;
        }

        .header-nav {
            left: 0.5rem;
            right: 0.5rem;
            max-height: 60vh;
            padding: 0.5rem 0;
        }

        .header-nav-list {
            padding: 0.35rem;
            gap: 0.2rem;
        }

        .header-nav .nav-link {
            padding: 0.6rem 0.8rem;
            font-size: 0.85rem;
            width: calc(100% - 0.7rem);
        }

        .header-nav .dropdown-menu {
            width: calc(100% - 0.7rem);
            max-height: 200px;
        }

        .header-nav .dropdown-item {
            padding: 0.55rem 1.1rem;
            font-size: 0.8rem;
        }

        .mobile-menu-toggle {
            width: 38px;
            height: 38px;
            padding: 0.4rem;
        }
    }
</style>

<header class="custom-header">
    <div class="header-container">
        {{-- Brand Logo - Enhanced --}}
        <a class="header-brand" href="{{ route('sila') }}" title="الرجوع للصفحة الرئيسية">
            <i class="fas fa-sitemap"></i>
            <span>تواصل عائلة السريِّع</span>
        </a>

        {{-- Separator --}}
        <div class="header-separator"></div>

        {{-- Navigation Menu --}}
        <nav class="header-nav" id="main-nav">
            <ul class="header-nav-list">
                <li>
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        الرئيسية
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('sila') ? 'active' : '' }}" href="{{ route('sila') }}">
                        صلة
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('gallery.index') ? 'active' : '' }}"
                        href="{{ route('gallery.index') }}">
                        معرض الصور
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('gallery.articles') ? 'active' : '' }}"
                        href="{{ route('gallery.articles') }}">
                        شهادات و أبحاث
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('persons.badges') ? 'active' : '' }}"
                        href="{{ route('persons.badges') }}">
                        طلاب طموح
                    </a>
                </li>

                <li class="dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('quran-competitions.*') || request()->routeIs('quran-categories.*') ? 'active' : '' }}"
                        href="#" role="button" onclick="toggleDropdown(event, this)" aria-expanded="false"
                        aria-haspopup="true">
                        <span>القرآن والسنة</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="quranDropdown">
                        @php
                            $quranCategories = \App\Models\Category::whereHas('quranCompetitions', function ($q) {
                                $q->where('is_active', true);
                            })
                                ->ordered()
                                ->active()
                                ->get();
                        @endphp
                        @if ($quranCategories->count() > 0)
                            @foreach ($quranCategories as $category)
                                @php
                                    $activeCompetitions = $category->quranCompetitions()->active()->get();
                                    if ($activeCompetitions->count() === 1) {
                                        $competition = $activeCompetitions->first();
                                        $url = route('quran-competitions.show', $competition->id);
                                        $isActive =
                                            request()->routeIs('quran-competitions.show') &&
                                            request()->route('id') == $competition->id;
                                    } else {
                                        $url = route('quran-categories.show', $category);
                                        $isActive =
                                            request()->routeIs('quran-categories.show') &&
                                            request()->route('category') == $category->id;
                                    }
                                @endphp
                                <a class="dropdown-item {{ $isActive ? 'active' : '' }}" href="{{ $url }}">
                                    <i class="fas fa-folder"></i>
                                    <span>{{ $category->name }}</span>
                                </a>
                            @endforeach
                        @else
                            <a class="dropdown-item" href="{{ route('quran-competitions.index') }}">
                                <i class="fas fa-trophy"></i>
                                <span>المسابقات</span>
                            </a>
                        @endif
                    </div>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('breastfeeding.public.*') ? 'active' : '' }}"
                        href="{{ route('breastfeeding.public.index') }}">
                        الرضاعة
                    </a>
                </li>
                @auth
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs(['rental.*', 'health-websites.*', 'my-rentals.*']) ? 'active' : '' }}"
                            href="#" role="button" onclick="toggleDropdown(event, this)" aria-expanded="false"
                            aria-haspopup="true">
                            <span>الصحة واللياقة</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="healthFitnessDropdown">
                            <a class="dropdown-item {{ request()->routeIs('rental.*') ? 'active' : '' }}"
                                href="{{ route('rental.index') }}">
                                <span>استعارة الأدوات الرياضية</span>
                            </a>
                            <a class="dropdown-item {{ request()->routeIs('health-websites.*') ? 'active' : '' }}"
                                href="{{ route('health-websites.index') }}">
                                <span>مواقع مهتمة بالصحة</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item {{ request()->routeIs('my-rentals.*') ? 'active' : '' }}"
                                href="{{ route('my-rentals.index') }}">
                                <span>طلبات الاستعارة الخاصة بي</span>
                            </a>
                        </div>
                    </li>
                @endauth

                <li>
                    <a class="nav-link {{ request()->routeIs('store.*') ? 'active' : '' }}"
                        href="{{ route('store.index') }}">
                        متجر الأسر المنتجة
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}"
                        href="{{ route('reports.index') }}">
                        التقارير
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Mobile Menu Toggle --}}
        <button class="mobile-menu-toggle" id="mobile-menu-btn" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
</header>

<script>
    (function() {
        'use strict';

        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mainNav = document.getElementById('main-nav');

        // Initialize: Close all dropdowns on mobile
        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
                const toggle = menu.previousElementSibling;
                if (toggle && toggle.classList.contains('dropdown-toggle')) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Close dropdowns when mobile menu closes
        if (mainNav) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        if (!mainNav.classList.contains('is-open')) {
                            closeAllDropdowns();
                        }
                    }
                });
            });

            observer.observe(mainNav, {
                attributes: true,
                attributeFilter: ['class']
            });
        }

        // Mobile Menu Toggle
        if (mobileMenuBtn && mainNav) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mainNav.classList.toggle('is-open');
                mobileMenuBtn.classList.toggle('active');

                // Close all dropdowns when opening mobile menu
                if (!mainNav.classList.contains('is-open')) {
                    closeAllDropdowns();
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (mainNav.classList.contains('is-open') &&
                    !mainNav.contains(e.target) &&
                    !mobileMenuBtn.contains(e.target)) {
                    mainNav.classList.remove('is-open');
                    mobileMenuBtn.classList.remove('active');
                    closeAllDropdowns();
                }
            });
        }

        // Dropdown Toggle Function
        window.toggleDropdown = function(event, element) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const menu = element.nextElementSibling;
            if (!menu || !menu.classList.contains('dropdown-menu')) {
                return;
            }

            const isOpen = menu.classList.contains('show');

            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(m => {
                if (m !== menu) {
                    m.classList.remove('show');
                    const toggle = m.previousElementSibling;
                    if (toggle && toggle.classList.contains('dropdown-toggle')) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Toggle current dropdown
            if (isOpen) {
                menu.classList.remove('show');
                element.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.add('show');
                element.setAttribute('aria-expanded', 'true');
            }
        };

        // Close dropdown when clicking outside (desktop only)
        document.addEventListener('click', function(e) {
            if (window.innerWidth > 992) {
                if (!e.target.closest('.dropdown')) {
                    closeAllDropdowns();
                }
            }
        });

        // Close dropdown when clicking on item
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const menu = this.closest('.dropdown-menu');
                if (menu) {
                    menu.classList.remove('show');
                    const toggle = menu.previousElementSibling;
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                    // Close mobile menu if open
                    if (mainNav && mainNav.classList.contains('is-open')) {
                        mainNav.classList.remove('is-open');
                        if (mobileMenuBtn) {
                            mobileMenuBtn.classList.remove('active');
                        }
                    }
                }
            });
        });

        // Triple click on brand to go to login
        const headerBrand = document.querySelector('.header-brand');
        if (headerBrand) {
            let clickCount = 0;
            let clickTimer = null;
            const CLICK_TIMEOUT = 2000;
            const REQUIRED_CLICKS = 3;
            let isRedirecting = false;

            headerBrand.addEventListener('click', function(e) {
                if (isRedirecting) {
                    e.preventDefault();
                    return;
                }

                clickCount++;
                if (clickTimer) clearTimeout(clickTimer);

                clickTimer = setTimeout(() => {
                    clickCount = 0;
                }, CLICK_TIMEOUT);

                if (clickCount >= REQUIRED_CLICKS) {
                    e.preventDefault();
                    clickCount = 0;
                    clearTimeout(clickTimer);
                    isRedirecting = true;
                    window.location.href = '{{ route('login') }}';
                }
            });
        }

        // Ensure dropdowns are closed on page load (mobile)
        if (window.innerWidth <= 992) {
            closeAllDropdowns();
        }

        // Close dropdowns on window resize to mobile
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth <= 992) {
                    closeAllDropdowns();
                }
            }, 250);
        });
    })();
</script>
