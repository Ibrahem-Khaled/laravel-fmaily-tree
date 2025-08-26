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
        --header-height: 70px; /* ارتفاع الهيدر */
    }

    /* --- الهيكل العام للهيدر --- */
    .custom-header {
        background-color: var(--header-bg);
        height: var(--header-height);
        width: 100%;
        position: relative;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        border-bottom: 2px solid var(--header-accent);
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
        padding: 0 2rem; /* مساحة على الأطراف */
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
    }

    .header-brand i {
        font-size: 1.5rem;
    }

    /* --- قائمة الروابط الرئيسية --- */
    .header-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-nav-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0.5rem;
    }

    .header-nav .nav-link {
        display: block;
        padding: 0 1rem;
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
        display: none; /* مخفي في الشاشات الكبيرة */
        background: none;
        border: 1px solid rgba(255,255,255,0.3);
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
        .header-nav, .header-actions {
            display: none; /* إخفاء القائمة والأزرار */
        }
        .mobile-menu-toggle {
            display: block; /* إظهار زر القائمة */
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
            box-shadow: 0 10px 15px rgba(0,0,0,0.2);
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
            bottom: 5px; /* تعديل مكان الخط */
        }
        .header-nav.is-open .header-actions {
            display: block;
            margin-top: 1rem;
        }
    }
</style>

<header class="custom-header">
    <div class="header-container">

        <a class="header-brand" href="{{ route('old.family-tree') }}">
            <i class="fas fa-tree"></i>
            <span>تواصل عائلة السريع</span>
        </a>

        <nav class="header-nav" id="main-nav">
            <ul class="header-nav-list">
                <li>
                    <a class="nav-link {{ request()->routeIs('old.family-tree') ? 'active' : '' }}" href="{{ route('old.family-tree') }}">الرئيسية</a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('family-tree') ? 'active' : '' }}" href="{{ route('family-tree') }}">العرض الجديد</a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('gallery.index') ? 'active' : '' }}" href="{{ route('gallery.index') }}">المعرض</a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('gallery.articles') ? 'active' : '' }}" href="{{ route('gallery.articles') }}">شهادات و أبحاث</a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="#">عن العائلة</a>
                </li>
            </ul>
            <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="dashboard-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
            </div>
        </nav>

        <button class="mobile-menu-toggle" id="mobile-menu-btn" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

    </div>
</header>

<script>
    // جافاسكريبت بسيط لتشغيل القائمة في وضع الجوال
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuButton = document.getElementById('mobile-menu-btn');
        const mainNav = document.getElementById('main-nav');

        if (mobileMenuButton && mainNav) {
            mobileMenuButton.addEventListener('click', function () {
                mainNav.classList.toggle('is-open');
            });
        }
    });
</script>
