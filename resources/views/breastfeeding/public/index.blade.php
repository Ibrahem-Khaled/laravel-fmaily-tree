<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>علاقات الرضاعة - عائلة السريع</title>

    {{-- Stylesheets --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #37a05c;
            --light-green: #DCF2DD;
            --dark-green: #145147;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --accent-color: #37a05c;
            --light-accent: #DCF2DD;
        }

        body {
            background: var(--light-gray);
            font-family: 'Alexandria', sans-serif;
        }

        .breastfeeding-section {
            background: linear-gradient(180deg, var(--light-green) 0%, #FFF 100%);
            padding-top: 120px;
            padding-bottom: 50px;
            min-height: 100vh;
        }

        .section-title {
            color: var(--dark-green);
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
        }

        .mother-card {
            background: #fff;
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .mother-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(55, 160, 92, 0.15);
        }

        .mother-card.active {
            border-color: var(--accent-color);
            background: var(--light-accent);
            box-shadow: 0 8px 20px rgba(55, 160, 92, 0.2);
        }

        .mother-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .mother-name {
            font-weight: 600;
            color: var(--dark-green);
            margin-top: 10px;
            font-size: 1.1rem;
        }

        .children-count {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .children-section {
            background: #fff;
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            min-height: 200px;
            display: none;
        }

        .children-section.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .child-card {
            background: var(--light-gray);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .child-card:hover {
            background: var(--light-green);
            border-color: var(--primary-color);
            transform: translateX(-5px);
        }

        .child-card.active {
            background: var(--light-green);
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(55, 160, 92, 0.2);
        }

        .child-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .child-name {
            font-weight: 600;
            color: var(--dark-green);
            margin-top: 8px;
            font-size: 0.95rem;
        }

        .notes-section {
            background: #fff;
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            min-height: 200px;
            display: none;
        }

        .notes-section.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .notes-title {
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid var(--light-green);
            padding-bottom: 10px;
        }

        .notes-content {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 15px;
            line-height: 1.6;
            color: #555;
        }

        .breastfeeding-dates {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-completed {
            background: #f8d7da;
            color: #721c24;
        }

        .search-section {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .stats-section {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .stat-card {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: var(--light-gray);
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .breastfeeding-section {
                padding-top: 90px;
                padding-left: 10px;
                padding-right: 10px;
            }

            .mother-photo {
                width: 60px;
                height: 60px;
            }

            .child-photo {
                width: 40px;
                height: 40px;
            }

            .mother-name, .child-name {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    {{-- تضمين الهيدر --}}
    @include('partials.main-header')

    <main>
        <section class="breastfeeding-section">
            <div class="container-fluid">
                <div class="section-title">
                    <h2><i class="fas fa-baby text-success"></i> علاقات الرضاعة</h2>
                </div>

                {{-- شريط البحث --}}
                <div class="search-section">
                    <form method="GET" action="{{ route('breastfeeding.public.index') }}">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" name="search" value="{{ $search }}"
                                       class="form-control"
                                       placeholder="البحث في أسماء الأمهات أو الأطفال...">
    </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> بحث
                                </button>
        </div>
                        </div>
                    </form>
                </div>

                {{-- الإحصائيات --}}
                <div class="stats-section">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['total_relationships'] }}</div>
                                <div class="stat-label">إجمالي العلاقات</div>
                    </div>
                </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['total_nursing_mothers'] }}</div>
                                <div class="stat-label">الأمهات المرضعات</div>
        </div>
                </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['total_breastfed_children'] }}</div>
                                <div class="stat-label">الأطفال المرتضعين</div>
            </div>
                </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['active_breastfeeding'] }}</div>
                                <div class="stat-label">رضاعة مستمرة</div>
            </div>
                </div>
            </div>
        </div>

                <div class="row">
                    {{-- قائمة الأمهات (اليمين) --}}
                    <div class="col-lg-4">
                        <h4 class="mb-3"><i class="fas fa-female text-success"></i> الأمهات المرضعات</h4>
                        @if($mothersData->isNotEmpty())
                            @foreach($mothersData as $mother)
                                <div class="mother-card" onclick="showChildren({{ $mother['id'] }})"
                                     data-mother-id="{{ $mother['id'] }}">
                                    <div class="children-count">{{ count($mother['children']) }}</div>
                                    <div class="text-center">
                                        <img src="{{ $mother['avatar'] }}" alt="{{ $mother['name'] }}" class="mother-photo">
                                        <div class="mother-name">{{ $mother['name'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-baby"></i>
                                <p>لا توجد أمهات مرضعات مسجلة</p>
                            </div>
                                                    @endif
                                                </div>

                    {{-- قائمة الأبناء (الوسط) --}}
                    <div class="col-lg-4">
                        <h4 class="mb-3"><i class="fas fa-child text-success"></i> الأطفال المرتضعون</h4>
                        <div class="children-section" id="childrenSection">
                            <div class="empty-state">
                                <i class="fas fa-hand-pointer"></i>
                                <p>اختر أمًا لعرض أطفالها</p>
                                                    </div>
                                            </div>
                                        </div>

                    {{-- الملاحظات (اليسار) --}}
                    <div class="col-lg-4">
                        <h4 class="mb-3"><i class="fas fa-sticky-note text-success"></i> الملاحظات</h4>
                        <div class="notes-section" id="notesSection">
                            <div class="empty-state">
                                <i class="fas fa-info-circle"></i>
                                <p>اختر طفلًا لعرض ملاحظاته</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // بيانات الأمهات والأطفال
        const mothersData = @json($mothersData);
        let selectedMotherId = null;
        let selectedChildId = null;

        function showChildren(motherId) {
            // إزالة التحديد من جميع الأمهات
            document.querySelectorAll('.mother-card').forEach(card => {
                card.classList.remove('active');
            });

            // تحديد الأم المختارة
            const selectedMotherCard = document.querySelector(`[data-mother-id="${motherId}"]`);
            selectedMotherCard.classList.add('active');

            selectedMotherId = motherId;

            // العثور على بيانات الأم
            const mother = mothersData.find(m => m.id === motherId);
            if (!mother) return;

            // عرض الأبناء
            const childrenSection = document.getElementById('childrenSection');
            childrenSection.classList.add('show');

            if (mother.children.length > 0) {
                childrenSection.innerHTML = `
                    <h5 class="mb-3">أبناء ${mother.name}</h5>
                    ${mother.children.map(child => `
                        <div class="child-card" onclick="showNotes(${child.relationship_id})"
                             data-child-id="${child.relationship_id}">
                            <div class="d-flex align-items-center">
                                <img src="${child.avatar}" alt="${child.name}" class="child-photo me-3">
                                <div class="flex-grow-1">
                                    <div class="child-name">${child.name}</div>
                                    <div class="breastfeeding-dates">
                                        ${child.start_date ? `من: ${child.start_date}` : ''}
                                        ${child.end_date ? `إلى: ${child.end_date}` : ''}
                                        ${child.duration_months ? ` (${child.duration_months} شهر)` : ''}
                                    </div>
                                </div>
                                <span class="status-badge ${child.is_active ? 'status-active' : 'status-completed'}">
                                    ${child.is_active ? 'مستمرة' : 'مكتملة'}
                                </span>
                            </div>
                        </div>
                    `).join('')}
                `;
            } else {
                childrenSection.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-baby"></i>
                        <p>لا يوجد أطفال مسجلين لهذه الأم</p>
                    </div>
                `;
            }

            // إخفاء الملاحظات
            const notesSection = document.getElementById('notesSection');
            notesSection.classList.remove('show');
            notesSection.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>اختر طفلًا لعرض ملاحظاته</p>
                </div>
            `;
        }

        function showNotes(relationshipId) {
            // إزالة التحديد من جميع الأطفال
            document.querySelectorAll('.child-card').forEach(card => {
                card.classList.remove('active');
            });

            // تحديد الطفل المختار
            const selectedChildCard = document.querySelector(`[data-child-id="${relationshipId}"]`);
            selectedChildCard.classList.add('active');

            selectedChildId = relationshipId;

            // العثور على بيانات الطفل
            let selectedChild = null;
            mothersData.forEach(mother => {
                const child = mother.children.find(c => c.relationship_id === relationshipId);
                if (child) {
                    selectedChild = child;
                }
            });

            if (!selectedChild) return;

            // عرض الملاحظات
            const notesSection = document.getElementById('notesSection');
            notesSection.classList.add('show');

            if (selectedChild.notes) {
                notesSection.innerHTML = `
                    <div class="notes-title">
                        <i class="fas fa-sticky-note"></i> ملاحظات عن ${selectedChild.name}
                    </div>
                    <div class="notes-content">
                        ${selectedChild.notes}
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i>
                            ${selectedChild.start_date ? `تاريخ البداية: ${selectedChild.start_date}` : ''}
                            ${selectedChild.end_date ? ` | تاريخ النهاية: ${selectedChild.end_date}` : ''}
                        </small>
                    </div>
                `;
            } else {
                notesSection.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-sticky-note"></i>
                        <p>لا توجد ملاحظات مسجلة لهذا الطفل</p>
                    </div>
                `;
            }
        }

        // تهيئة الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // إذا كان هناك بحث، اعرض النتائج
            @if($search)
                // يمكن إضافة منطق لعرض النتائج المفلترة
            @endif
        });
    </script>
</body>

</html>
