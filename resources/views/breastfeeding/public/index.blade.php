<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>علاقات الرضاعة - تواصل عائلة السريع</title>

    {{-- 🎨 Stylesheets --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #37a05c;
            --light-green: #DCF2DD;
            --dark-green: #145147;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --baby-blue: #E3F2FD;
            --baby-pink: #FCE4EC;
        }

        body {
            background: var(--light-gray);
            font-family: 'Alexandria', sans-serif;
        }

        /* Header Styles */
        .custom-header {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-color) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .header-nav-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 2rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
        }

        .dashboard-link {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .dashboard-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Main Content */
        .breastfeeding-section {
            background: linear-gradient(180deg, var(--baby-blue) 0%, #FFF 100%);
            padding: 2rem 0;
            min-height: 100vh;
        }

        .page-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title h1 {
            color: var(--dark-green);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .page-title p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Statistics Cards */
        .stats-container {
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Nursing Mothers Section */
        .nursing-mothers-section {
            margin-bottom: 3rem;
        }

        .section-title {
            color: var(--dark-green);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .nursing-mother-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }

        .nursing-mother-card:hover {
            transform: translateY(-3px);
        }

        .mother-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-green);
        }

        .mother-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--light-green);
            margin-left: 1rem;
        }

        .mother-info h3 {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .mother-info p {
            color: #666;
            margin: 0;
        }

        .breastfed-children {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .child-card {
            background: var(--baby-pink);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            border: 2px solid #F8BBD9;
            transition: all 0.3s ease;
        }

        .child-card:hover {
            background: #F8BBD9;
            transform: scale(1.02);
        }

        .child-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin: 0 auto 1rem;
        }

        .child-name {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 0.5rem;
        }

        .breastfeeding-duration {
            background: white;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            display: inline-block;
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .breastfeeding-status {
            margin-top: 0.5rem;
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #D4EDDA;
            color: #155724;
        }

        .status-completed {
            background: #D1ECF1;
            color: #0C5460;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: var(--dark-green);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            .header-nav-list {
                gap: 1rem;
            }

            .page-title h1 {
                font-size: 2rem;
            }

            .mother-header {
                flex-direction: column;
                text-align: center;
            }

            .mother-photo {
                margin-left: 0;
                margin-bottom: 1rem;
            }

            .breastfed-children {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <header class="custom-header">
        <div class="header-container">
            <a class="header-brand" href="{{ route('old.family-tree') }}">
                <i class="fas fa-baby me-2"></i>تواصل عائلة السريع
            </a>

            <nav class="header-nav">
                <ul class="header-nav-list">
                    <li>
                        <a class="nav-link" href="{{ route('old.family-tree') }}">الرئيسية</a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('breastfeeding.public.index') ? 'active' : '' }}"
                           href="{{ route('breastfeeding.public.index') }}">الرضاعة</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('gallery.index') }}">معرض الصور</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('gallery.articles') }}">شهادات و أبحاث</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('persons.badges') }}">طلاب طموح</a>
                    </li>
                </ul>
            </nav>

            <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="dashboard-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="breastfeeding-section">
        <div class="container">
            {{-- Page Title --}}
            <div class="page-title">
                <h1><i class="fas fa-baby me-3"></i>علاقات الرضاعة</h1>
                <p>تعرف على الأمهات المرضعات والأطفال المرتضعين في العائلة</p>
            </div>

            {{-- Statistics --}}
            <div class="stats-container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon text-primary">
                                <i class="fas fa-link"></i>
                            </div>
                            <div class="stat-number">{{ $stats['total_relationships'] }}</div>
                            <div class="stat-label">إجمالي العلاقات</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon text-success">
                                <i class="fas fa-female"></i>
                            </div>
                            <div class="stat-number">{{ $stats['total_nursing_mothers'] }}</div>
                            <div class="stat-label">الأمهات المرضعات</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon text-info">
                                <i class="fas fa-child"></i>
                            </div>
                            <div class="stat-number">{{ $stats['total_breastfed_children'] }}</div>
                            <div class="stat-label">الأطفال المرتضعين</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-number">{{ $stats['active_breastfeeding'] }}</div>
                            <div class="stat-label">رضاعة مستمرة</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nursing Mothers --}}
            <div class="nursing-mothers-section">
                <h2 class="section-title">
                    <i class="fas fa-female me-2"></i>الأمهات المرضعات
                </h2>

                @if($nursingMothers->isNotEmpty())
                    @foreach($nursingMothers as $motherId => $relationships)
                        @php
                            $mother = $relationships->first()->nursingMother;
                        @endphp
                        <div class="nursing-mother-card">
                            <div class="mother-header">
                                <img src="{{ $mother->avatar }}" alt="{{ $mother->first_name }}" class="mother-photo">
                                <div class="mother-info">
                                    <h3>{{ $mother->first_name }} {{ $mother->last_name }}</h3>
                                    <p><i class="fas fa-baby me-1"></i>أرضعت {{ $relationships->count() }} طفل/أطفال</p>
                                    <a href="{{ route('breastfeeding.public.show', $mother->id) }}"
                                       class="btn btn-sm btn-outline-success mt-2">
                                        <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                    </a>
                                </div>
                            </div>

                            <div class="breastfed-children">
                                @foreach($relationships as $relationship)
                                    <div class="child-card">
                                        <img src="{{ $relationship->breastfedChild->avatar }}"
                                             alt="{{ $relationship->breastfedChild->first_name }}"
                                             class="child-photo">
                                        <div class="child-name">{{ $relationship->breastfedChild->first_name }}</div>

                                        <a href="{{ route('breastfeeding.public.show', $relationship->breastfedChild->id) }}"
                                           class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                        </a>

                                        @if($relationship->duration_in_months)
                                            <div class="breastfeeding-duration">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $relationship->duration_in_months }} شهر
                                            </div>
                                        @endif

                                        <div class="breastfeeding-status">
                                            @if($relationship->end_date)
                                                <span class="status-badge status-completed">
                                                    <i class="fas fa-check-circle me-1"></i>مكتملة
                                                </span>
                                            @else
                                                <span class="status-badge status-active">
                                                    <i class="fas fa-clock me-1"></i>مستمرة
                                                </span>
                                            @endif
                                        </div>

                                        @if($relationship->notes)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-sticky-note me-1"></i>
                                                    {{ Str::limit($relationship->notes, 50) }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-baby"></i>
                        <h3>لا توجد علاقات رضاعة مسجلة</h3>
                        <p>لم يتم تسجيل أي علاقات رضاعة في النظام بعد</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>إضافة علاقة رضاعة
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
