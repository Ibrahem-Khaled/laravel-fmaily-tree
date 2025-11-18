{{-- ====================================================================== --}}
{{-- | ملف الهيدر المنفصل (كود مخصص بالكامل بدون Bootstrap) | --}}
{{-- ====================================================================== --}}

<style>
    /* --- المتغيرات الأساسية --- */
    :root {
        --header-bg: #145147;
        --header-accent: #37a05c;
        --header-text: rgba(255, 255, 255, 0.8);
        --header-text-hover: #ffffff;
        --header-height: 70px;
        /* ارتفاع الهيدر */
    }

    /* --- الهيكل العام للهيدر --- */
    .custom-header {
        background-color: var(--header-bg);
        height: var(--header-height);
        width: 100%;
        z-index: 1000;
        position: relative;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        border-bottom: 2px solid var(--header-accent);
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
        padding: 0 1rem;
        /* مساحة على الأطراف */
        max-width: 1400px;
        margin: 0 auto;
    }

    /* --- الشعار --- */
    .header-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--header-text-hover);
        text-decoration: none;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        box-shadow:
            0 4px 8px rgba(0, 0, 0, 0.2),
            0 2px 4px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.3),
            inset 0 -1px 0 rgba(0, 0, 0, 0.2);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .header-brand::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .header-brand:hover::before {
        left: 100%;
    }

    .header-brand::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 6px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 50%);
        pointer-events: none;
    }

    .header-brand:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.15) 100%);
        border-color: rgba(255, 255, 255, 0.6);
        transform: translateY(-2px);
        box-shadow:
            0 6px 12px rgba(0, 0, 0, 0.25),
            0 4px 8px rgba(0, 0, 0, 0.15),
            inset 0 2px 4px rgba(255, 255, 255, 0.4),
            inset 0 -2px 4px rgba(0, 0, 0, 0.2);
        color: #ffffff;
    }

    .header-brand:active {
        transform: translateY(0);
        box-shadow:
            0 2px 4px rgba(0, 0, 0, 0.2),
            inset 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .header-brand i {
        transition: transform 0.3s ease;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
    }

    .header-brand:hover i {
        transform: rotate(15deg) scale(1.1);
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
    }

    .header-brand span {
        position: relative;
        z-index: 1;
    }

    /* --- قائمة الروابط الرئيسية --- */
    .header-nav {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .header-nav-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0.25rem;
    }

    .header-nav .nav-link {
        display: block;
        padding: 0 0.75rem;
        height: var(--header-height);
        line-height: var(--header-height);
        color: var(--header-text);
        text-decoration: none;
        font-weight: 500;
        position: relative;
        transition: color 0.3s ease;
    }

    .header-nav .nav-link:hover {
        color: var(--header-text-hover);
    }

    /* --- حركة الخط السفلي --- */
    .header-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background-color: var(--header-accent);
        transition: width 0.3s ease;
    }

    .header-nav .nav-link:hover::after,
    .header-nav .nav-link.active::after {
        width: 60%;
    }

    .header-nav .nav-link.active {
        color: var(--header-text-hover);
        font-weight: 700;
    }

    /* --- أزرار الإجراءات (لوحة التحكم) --- */
    .header-actions {
        display: flex;
        align-items: center;
    }

    .dashboard-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(255, 255, 255, 0.5);
        color: var(--header-text);
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .dashboard-link:hover {
        background-color: #fff;
        color: var(--header-bg);
    }

    /* --- زر قائمة الجوال --- */
    .mobile-menu-toggle {
        display: none;
        /* مخفي في الشاشات الكبيرة */
        background: none;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 5px;
        padding: 0.5rem;
        cursor: pointer;
    }

    .mobile-menu-toggle .icon-bar {
        display: block;
        width: 22px;
        height: 2px;
        background-color: var(--header-text-hover);
        margin: 4px 0;
    }

    /* --- التجاوب مع شاشات الجوال (Responsive) --- */
    @media (max-width: 992px) {

        .header-nav,
        .header-actions {
            display: none;
            /* إخفاء القائمة والأزرار */
        }

        .mobile-menu-toggle {
            display: block;
            /* إظهار زر القائمة */
        }

        .header-container {
            padding: 0 1rem;
        }

        /* تنسيقات القائمة المنسدلة للجوال */
        .header-nav.is-open {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: var(--header-height);
            left: 0;
            right: 0;
            background-color: var(--header-bg);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
            padding: 1rem 0;
            border-top: 1px solid var(--header-accent);
        }

        .header-nav.is-open .header-nav-list {
            flex-direction: column;
            width: 100%;
            align-items: center;
        }

        .header-nav.is-open .nav-link {
            height: auto;
            line-height: 1;
            padding: 1rem;
            width: 100%;
            text-align: center;
        }

        .header-nav.is-open .nav-link::after {
            bottom: 5px;
            /* تعديل مكان الخط */
        }

        .header-nav.is-open .header-actions {
            display: block;
            margin-top: 1rem;
        }
    }
</style>

<header class="custom-header">
    <div class="header-container">

        <a class="header-brand" href="{{ route('sila') }}" title="الرجوع للصفحة الرئيسية - شجرة العائلة">
            <span>تواصل عائلة السريِّع</span>
            <i class="fas fa-sitemap"></i>
        </a>

        <nav class="header-nav" id="main-nav">
            <ul class="header-nav-list">
                <li>
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">الرئيسية</a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('sila') ? 'active' : '' }}" href="{{ route('sila') }}">
                        <i class="fas fa-sitemap me-1"></i>صلة
                    </a>
                </li>
                {{-- <li>
                    <a class="nav-link {{ request()->routeIs('family-tree') ? 'active' : '' }}" href="{{ route('family-tree') }}">العرض الجديد</a>
                </li> --}}
                <li>
                    <a class="nav-link {{ request()->routeIs('gallery.index') ? 'active' : '' }}"
                        href="{{ route('gallery.index') }}">معرض الصور</a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('gallery.articles') ? 'active' : '' }}"
                        href="{{ route('gallery.articles') }}">شهادات و أبحاث</a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('persons.badges') ? 'active' : '' }}"
                        href="{{ route('persons.badges') }}">
                        طلاب طموح
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('breastfeeding.public.*') ? 'active' : '' }}"
                        href="{{ route('breastfeeding.public.index') }}">
                        <i class="fas fa-baby me-1"></i>الرضاعة
                    </a>
                </li>
                @if (auth()->check())
                    <li>
                        <a class="nav-link {{ request()->routeIs('store.*') ? 'active' : '' }}"
                            href="{{ route('store.index') }}">
                            <i class="fas fa-shopping-bag me-1"></i>متجر الأسر المنتجة
                        </a>
                    </li>
                @endif
                <li>
                    <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}"
                        href="{{ route('reports.index') }}">
                        التقارير
                    </a>
                </li>
                {{-- <li>
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="#">عن العائلة</a>
                </li> --}}
            </ul>
            {{-- <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="dashboard-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
            </div> --}}
        </nav>

        <button class="mobile-menu-toggle" id="mobile-menu-btn" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-btn');
        const mainNav = document.getElementById('main-nav');

        if (mobileMenuButton && mainNav) {
            // فتح/إغلاق القائمة
            mobileMenuButton.addEventListener('click', function(event) {
                event.stopPropagation(); // علشان ما يقفلش من نفس الكليك
                mainNav.classList.toggle('is-open');
            });

            // لو ضغط في أي مكان في الصفحة يقفل القائمة
            document.addEventListener('click', function(event) {
                // شرط: يكون الضغط خارج الـ nav وخارج زر القائمة
                if (
                    mainNav.classList.contains('is-open') &&
                    !mainNav.contains(event.target) &&
                    !mobileMenuButton.contains(event.target)
                ) {
                    mainNav.classList.remove('is-open');
                }
            });
        }
    });
</script>
