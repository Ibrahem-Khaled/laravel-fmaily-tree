<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>علاقات الرضاعة - {{ $person->first_name }} - تواصل عائلة السريع</title>

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
        .person-section {
            background: linear-gradient(180deg, var(--baby-blue) 0%, #FFF 100%);
            padding: 2rem 0;
            min-height: 100vh;
        }

        .person-header {
            text-align: center;
            margin-bottom: 3rem;
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .person-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid var(--light-green);
            margin: 0 auto 1.5rem;
            display: block;
        }

        .person-name {
            color: var(--dark-green);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .person-role {
            color: #666;
            font-size: 1.2rem;
        }

        /* Relationships Sections */
        .relationships-section {
            margin-bottom: 3rem;
        }

        .section-title {
            color: var(--dark-green);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .relationship-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .relationship-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-green);
        }

        .relationship-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--light-green);
            margin-left: 1rem;
        }

        .relationship-info h3 {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .relationship-info p {
            color: #666;
            margin: 0;
        }

        .relationship-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            background: var(--baby-pink);
            border-radius: 15px;
            padding: 1rem;
            text-align: center;
            border: 2px solid #F8BBD9;
        }

        .detail-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-weight: 600;
            color: var(--dark-green);
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

        /* Back Button */
        .back-button {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s ease;
            margin-bottom: 2rem;
        }

        .back-button:hover {
            background: var(--dark-green);
            color: white;
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

            .person-name {
                font-size: 2rem;
            }

            .relationship-header {
                flex-direction: column;
                text-align: center;
            }

            .relationship-photo {
                margin-left: 0;
                margin-bottom: 1rem;
            }

            .relationship-details {
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
                        <a class="nav-link {{ request()->routeIs('breastfeeding.public.*') ? 'active' : '' }}"
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
    <main class="person-section">
        <div class="container">
            {{-- Back Button --}}
            <a href="{{ route('breastfeeding.public.index') }}" class="back-button">
                <i class="fas fa-arrow-right"></i>
                العودة لصفحة الرضاعة
            </a>

            {{-- Person Header --}}
            <div class="person-header">
                <img src="{{ $person->avatar }}" alt="{{ $person->first_name }}" class="person-photo">
                <h1 class="person-name">{{ $person->full_name }}</h1>
                <p class="person-role">
                    @if($nursingRelationships->isNotEmpty() && $breastfedRelationships->isNotEmpty())
                        <i class="fas fa-baby me-1"></i>أم مرضعة وطفل مرتضع
                    @elseif($nursingRelationships->isNotEmpty())
                        <i class="fas fa-female me-1"></i>أم مرضعة
                    @elseif($breastfedRelationships->isNotEmpty())
                        <i class="fas fa-child me-1"></i>طفل مرتضع
                    @else
                        <i class="fas fa-user me-1"></i>عضو في العائلة
                    @endif
                </p>
            </div>

            {{-- Nursing Relationships (as nursing mother) --}}
            @if($nursingRelationships->isNotEmpty())
                <div class="relationships-section">
                    <h2 class="section-title">
                        <i class="fas fa-female me-2"></i>كأم مرضعة
                    </h2>

                    @foreach($nursingRelationships as $relationship)
                        <div class="relationship-card">
                            <div class="relationship-header">
                                <img src="{{ $relationship->breastfedChild->avatar }}"
                                     alt="{{ $relationship->breastfedChild->first_name }}"
                                     class="relationship-photo">
                                <div class="relationship-info">
                                    <h3>{{ $relationship->breastfedChild->full_name }}</h3>
                                    <small class="text-muted">{{ $relationship->breastfedChild->first_name }} {{ $relationship->breastfedChild->last_name }}</small>
                                    <p><i class="fas fa-child me-1"></i>الطفل المرتضع</p>
                                </div>
                            </div>

                            <div class="relationship-details">
                                @if($relationship->start_date)
                                    <div class="detail-item">
                                        <div class="detail-label">تاريخ البداية</div>
                                        <div class="detail-value">{{ $relationship->start_date->format('Y-m-d') }}</div>
                                    </div>
                                @endif

                                @if($relationship->end_date)
                                    <div class="detail-item">
                                        <div class="detail-label">تاريخ النهاية</div>
                                        <div class="detail-value">{{ $relationship->end_date->format('Y-m-d') }}</div>
                                    </div>
                                @endif

                                @if($relationship->duration_in_months)
                                    <div class="detail-item">
                                        <div class="detail-label">مدة الرضاعة</div>
                                        <div class="detail-value">{{ $relationship->duration_in_months }} شهر</div>
                                    </div>
                                @endif

                                <div class="detail-item">
                                    <div class="detail-label">الحالة</div>
                                    <div class="detail-value">
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
                                </div>
                            </div>

                            @if($relationship->notes)
                                <div class="mt-3">
                                    <h6 class="text-muted mb-2"><i class="fas fa-sticky-note me-1"></i>ملاحظات:</h6>
                                    <p class="text-muted">{{ $relationship->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Breastfed Relationships (as breastfed child) --}}
            @if($breastfedRelationships->isNotEmpty())
                <div class="relationships-section">
                    <h2 class="section-title">
                        <i class="fas fa-child me-2"></i>كطفل مرتضع
                    </h2>

                    @foreach($breastfedRelationships as $relationship)
                        <div class="relationship-card">
                            <div class="relationship-header">
                                <img src="{{ $relationship->nursingMother->avatar }}"
                                     alt="{{ $relationship->nursingMother->first_name }}"
                                     class="relationship-photo">
                                <div class="relationship-info">
                                    <h3>{{ $relationship->nursingMother->full_name }}</h3>
                                    <small class="text-muted">{{ $relationship->nursingMother->first_name }} {{ $relationship->nursingMother->last_name }}</small>
                                    <p><i class="fas fa-female me-1"></i>الأم المرضعة</p>
                                </div>
                            </div>

                            <div class="relationship-details">
                                @if($relationship->start_date)
                                    <div class="detail-item">
                                        <div class="detail-label">تاريخ البداية</div>
                                        <div class="detail-value">{{ $relationship->start_date->format('Y-m-d') }}</div>
                                    </div>
                                @endif

                                @if($relationship->end_date)
                                    <div class="detail-item">
                                        <div class="detail-label">تاريخ النهاية</div>
                                        <div class="detail-value">{{ $relationship->end_date->format('Y-m-d') }}</div>
                                    </div>
                                @endif

                                @if($relationship->duration_in_months)
                                    <div class="detail-item">
                                        <div class="detail-label">مدة الرضاعة</div>
                                        <div class="detail-value">{{ $relationship->duration_in_months }} شهر</div>
                                    </div>
                                @endif

                                <div class="detail-item">
                                    <div class="detail-label">الحالة</div>
                                    <div class="detail-value">
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
                                </div>
                            </div>

                            @if($relationship->notes)
                                <div class="mt-3">
                                    <h6 class="text-muted mb-2"><i class="fas fa-sticky-note me-1"></i>ملاحظات:</h6>
                                    <p class="text-muted">{{ $relationship->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Empty State --}}
            @if($nursingRelationships->isEmpty() && $breastfedRelationships->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-baby"></i>
                    <h3>لا توجد علاقات رضاعة مسجلة</h3>
                    <p>لم يتم تسجيل أي علاقات رضاعة لهذا الشخص</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة علاقة رضاعة
                    </a>
                </div>
            @endif
        </div>
    </main>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
