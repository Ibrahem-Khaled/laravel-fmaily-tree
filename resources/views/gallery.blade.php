<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض صور العائلة</title>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- خطوط مميزة --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700;800;900&family=Readex+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #2dd4bf;
            --secondary: #f59e0b;
            --accent: #ec4899;
            --accent-rgb: 236, 72, 153;
            --dark: #0f172a;
            --surface: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Readex Pro', 'Noto Kufi Arabic', sans-serif;
            background: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Noto Kufi Arabic', sans-serif;
        }

        /* ===== الخلفية المتحركة ===== */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(13, 148, 136, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(245, 158, 11, 0.08) 0%, transparent 60%),
                linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.4;
            animation: float 20s infinite ease-in-out;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--accent), #f472b6);
            bottom: -50px;
            left: -50px;
            animation-delay: -5s;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--secondary), #fbbf24);
            top: 50%;
            left: 50%;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 30px) scale(1.05); }
        }

        /* ===== المحتوى الرئيسي ===== */
        .main-content {
            position: relative;
            z-index: 10;
        }

        /* ===== هيدر المعرض ===== */
        .gallery-hero {
            padding: 2rem 1rem;
            text-align: center;
            position: relative;
        }

        .gallery-title {
            font-size: clamp(1.5rem, 5vw, 3.5rem);
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, var(--primary-light) 50%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 40px rgba(45, 212, 191, 0.3);
            line-height: 1.2;
        }

        /* ===== الإحصائيات ===== */
        .stats-bar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 1.5rem 0;
            padding: 0 1rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .stats-row {
            display: flex;
            gap: 1rem;
            width: 100%;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.25rem;
            border-radius: 100px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            min-width: 0;
            flex: 1;
        }

        .stat-item.info-only {
            cursor: default;
            user-select: none;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: none;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: none;
        }

        .stat-item.info-only .stat-icon {
            opacity: 0.5;
            filter: grayscale(0.5);
            transform: scale(0.9);
        }

        .stat-item.info-only .stat-value {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
        }

        .stat-item.info-only .stat-label {
            color: rgba(255, 255, 255, 0.4);
        }

        .stat-item.highlight {
            background: linear-gradient(135deg, rgba(var(--accent-rgb), 0.2), rgba(var(--accent-rgb), 0.05));
            border-color: rgba(var(--accent-rgb), 0.4);
            cursor: pointer;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(var(--accent-rgb), 0.1);
            -webkit-tap-highlight-color: transparent;
            position: relative;
            overflow: hidden;
        }

        .stat-item.highlight::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            to { left: 100%; }
        }

        .stat-item.highlight:hover {
            background: linear-gradient(135deg, rgba(var(--accent-rgb), 0.3), rgba(var(--accent-rgb), 0.1));
            transform: translateY(-3px);
            border-color: var(--accent);
            box-shadow: 0 15px 40px rgba(var(--accent-rgb), 0.3);
        }

        .stat-item.highlight:active {
            transform: scale(0.98);
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.teal { background: linear-gradient(135deg, var(--primary), var(--primary-light)); }
        .stat-icon.amber { background: linear-gradient(135deg, var(--secondary), #fbbf24); }
        .stat-icon.pink { background: linear-gradient(135deg, var(--accent), #f472b6); }

        .stat-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 0.15rem;
        }

        /* ===== مسار التنقل ===== */
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            margin: 0 1rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            flex-wrap: wrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
            user-select: none;
        }

        .breadcrumb-item:hover {
            color: var(--primary-light);
            background: rgba(45, 212, 191, 0.1);
        }

        .breadcrumb-item.active {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            font-weight: 600;
        }

        .breadcrumb-separator {
            color: rgba(255, 255, 255, 0.3);
        }

        /* ===== شبكة الفئات ===== */
        .categories-section {
            padding: 0 1rem 2rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
        }

        /* ===== بطاقات الفئات ===== */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .category-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            animation: cardAppear 0.6s ease-out backwards;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        .category-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: var(--primary);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 0 60px rgba(13, 148, 136, 0.2);
        }

        .category-card:active {
            transform: translateY(-4px) scale(0.98);
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* معاينة الصور في البطاقة */
        .card-preview {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
        }

        .category-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .category-icon {
            color: var(--primary-light);
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .category-card:hover .category-icon {
            opacity: 1;
            transform: scale(1.1);
            color: var(--primary);
        }

        /* محتوى البطاقة */
        .card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .card-meta-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .category-info-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.5s ease;
        }

        .category-info-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .category-info-description {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.7;
        }

        /* شارة الفئة الجديدة */
        .new-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, var(--accent), #f472b6);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 100px;
            animation: pulse 2s infinite;
            z-index: 10;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(236, 72, 153, 0.5); }
            50% { box-shadow: 0 0 0 10px rgba(236, 72, 153, 0); }
        }

        /* شارة الفئات الفرعية */
        .subcategories-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: linear-gradient(135deg, var(--secondary), #fbbf24);
            color: var(--dark);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 100px;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            z-index: 10;
        }

        /* تمييز الفئات غير المتاحة */
        .category-card.inactive {
            opacity: 0.7;
            border-color: rgba(255, 255, 255, 0.15);
            position: relative;
        }

        .category-card.inactive::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 24px;
            z-index: 1;
            pointer-events: none;
        }

        .category-card.inactive:hover {
            opacity: 0.85;
        }

        .inactive-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, rgba(107, 114, 128, 0.9), rgba(75, 85, 99, 0.9));
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 100px;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        /* تمييز الفئات الفرعية */
        .category-card.subcategory {
            border-left: 3px solid var(--secondary);
        }

        .subcategory-indicator {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            width: 8px;
            height: 8px;
            background: var(--secondary);
            border-radius: 50%;
            z-index: 10;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.6);
        }

        /* ===== عرض الصور ===== */
        .images-section {
            padding: 0 1rem 2rem;
            display: none;
        }

        .images-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .images-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
        }

        /* بطاقة الصورة */
        .image-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: imageAppear 0.5s ease-out backwards;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        .image-card:hover {
            transform: translateY(-5px) scale(1.02);
            border-color: var(--primary);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .image-card:active {
            transform: translateY(-2px) scale(0.98);
        }

        @keyframes imageAppear {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .image-wrapper {
            aspect-ratio: 1;
            overflow: hidden;
            position: relative;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .image-card:hover .image-wrapper img {
            transform: scale(1.1);
        }

        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.9), transparent 60%);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .image-card:hover .image-overlay {
            opacity: 1;
        }

        .image-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .image-card:hover .image-info {
            transform: translateY(0);
            opacity: 1;
        }

        .image-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .image-author {
            font-size: 0.75rem;
            color: var(--primary-light);
        }

        /* شارات نوع الملف */
        .file-type-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            z-index: 5;
        }

        .file-type-badge.youtube {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }

        .file-type-badge.pdf {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
        }

        /* ===== رسالة لا توجد صور ===== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 24px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: rgba(255, 255, 255, 0.5);
        }

        /* ===== Modal النافذة المنبثقة ===== */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-backdrop.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 24px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: modalSlide 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes modalSlide {
            from {
                transform: translateY(50px) scale(0.95);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
        }

        .modal-close {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .modal-preview {
            aspect-ratio: 16/10;
            overflow: hidden;
        }

        .modal-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-body {
            padding: 1.25rem;
        }

        .modal-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .modal-info-item:last-child {
            border-bottom: none;
        }

        .modal-info-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(13, 148, 136, 0.2);
            color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-info-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .modal-info-value {
            font-size: 0.9rem;
            color: #fff;
            font-weight: 500;
        }

        .modal-actions {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .modal-btn {
            padding: 0.875rem 1.25rem;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
        }

        .modal-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 148, 136, 0.3);
        }

        .modal-btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .modal-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* ===== Fullscreen Modal ===== */
        .fullscreen-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-modal.active {
            display: flex;
        }

        .fullscreen-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .fullscreen-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .fullscreen-image {
            max-width: 90vw;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
        }

        /* ===== Lazy Loading ===== */
        .lazy-image {
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .lazy-image.loaded {
            opacity: 1;
        }

        .lazy-placeholder {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(255,255,255,0.03) 25%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0.03) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            border-radius: 4px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .gallery-hero {
                padding: 1.5rem 0.5rem;
            }

            .gallery-title {
                font-size: 1.75rem;
                margin-bottom: 0.25rem;
            }

            .gallery-subtitle {
                font-size: 0.875rem;
            }

            .stats-bar {
                gap: 0.5rem;
                margin: 1rem 0;
                padding: 0.5rem;
            }

            .stat-item {
                padding: 0.5rem 0.75rem;
                gap: 0.5rem;
            }

            .stat-icon {
                width: 28px;
                height: 28px;
                font-size: 0.875rem;
            }

            .stat-value {
                font-size: 1rem;
            }

            .stat-label {
                font-size: 0.65rem;
            }

            .breadcrumb-nav {
                padding: 0.75rem 0.5rem;
                margin: 0 0.5rem 1rem;
                gap: 0.25rem;
            }

            .breadcrumb-item {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }

            .categories-section {
                padding: 0 0.5rem 1.5rem;
            }

            .section-header {
                margin-bottom: 1rem;
            }

            .section-title {
                font-size: 1.125rem;
                gap: 0.5rem;
            }

            .section-title-icon {
                width: 32px;
                height: 32px;
            }

            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .category-card {
                border-radius: 16px;
            }

            .card-preview {
                aspect-ratio: 16/9;
            }

            .card-content {
                padding: 1rem;
            }

            .card-title {
                font-size: 1rem;
                margin-bottom: 0.375rem;
            }

            .card-meta {
                font-size: 0.75rem;
                gap: 0.75rem;
            }

            .category-info-box {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .category-info-title {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }

            .category-info-description {
                font-size: 0.875rem;
            }

            .subcategories-badge,
            .inactive-badge {
                top: 0.75rem;
                font-size: 0.6rem;
                padding: 0.25rem 0.5rem;
            }

            .subcategories-badge {
                left: 0.75rem;
            }

            .inactive-badge {
                right: 0.75rem;
            }

            .subcategory-indicator {
                top: 0.375rem;
                left: 0.375rem;
                width: 6px;
                height: 6px;
            }

            .images-section {
                padding: 0 0.5rem 1.5rem;
            }

            .images-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .image-card {
                border-radius: 12px;
            }

            .image-title {
                font-size: 0.75rem;
            }

            .image-author {
                font-size: 0.65rem;
            }

            .file-type-badge {
                top: 0.5rem;
                right: 0.5rem;
                padding: 0.25rem 0.5rem;
                font-size: 0.6rem;
            }

            .modal-content {
                max-width: 95vw;
                margin: 1rem;
                border-radius: 16px;
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-title {
                font-size: 1rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-actions {
                padding: 1rem;
            }

            .modal-btn {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .empty-state {
                padding: 2rem 1rem;
            }

            .empty-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }

            .empty-title {
                font-size: 1rem;
            }

            .empty-text {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .gallery-hero {
                padding: 1rem 0.5rem;
            }

            .gallery-title {
                font-size: 1.5rem;
            }

            .stats-bar {
                gap: 0.75rem;
                padding: 0 0.25rem;
            }

            .stats-row {
                flex-direction: row; /* Keep info items side-by-side */
                gap: 0.5rem;
            }

            .stat-item {
                padding: 0.5rem 0.75rem;
                gap: 0.5rem;
            }

            .stat-icon {
                width: 28px;
                height: 28px;
            }

            .stat-value {
                font-size: 1rem;
            }

            .stat-label {
                font-size: 0.65rem;
            }

            .stat-item.highlight {
                padding: 0.75rem 1rem;
            }

            .categories-grid {
                gap: 0.5rem;
            }

            .images-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .breadcrumb-nav {
                padding: 0.5rem;
                margin: 0 0.25rem 0.75rem;
            }

            .breadcrumb-item {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
                white-space: nowrap;
            }

            .card-content {
                padding: 0.75rem;
            }

            .card-title {
                font-size: 0.9rem;
            }

            .card-meta {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .card-meta-item {
                font-size: 0.7rem;
            }


            .mentioned-persons {
                gap: 0.25rem;
            }

            .mentioned-tag {
                font-size: 0.6rem;
                padding: 0.15rem 0.4rem;
            }

            .fullscreen-close {
                top: 0.5rem;
                right: 0.5rem;
                width: 40px;
                height: 40px;
            }

            .fullscreen-image {
                max-width: 95vw;
                max-height: 85vh;
            }
        }

        /* تحسينات إضافية للشاشات الصغيرة جداً */
        @media (max-width: 360px) {
            .gallery-title {
                font-size: 1.25rem;
            }

            .stat-item {
                padding: 0.5rem;
            }

            .stat-icon {
                width: 24px;
                height: 24px;
            }

            .stat-value {
                font-size: 0.9rem;
            }

            .category-card {
                border-radius: 12px;
            }

            .card-content {
                padding: 0.5rem;
            }

            .card-title {
                font-size: 0.85rem;
            }

            .section-title {
                font-size: 1rem;
            }

            .category-info-box {
                padding: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .category-info-title {
                font-size: 1.125rem;
                margin-bottom: 0.375rem;
            }

            .category-info-description {
                font-size: 0.8rem;
            }
        }

        /* ===== Mentioned Persons ===== */
        .mentioned-persons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            margin-top: 0.5rem;
        }

        .mentioned-tag {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            font-size: 0.65rem;
            padding: 0.2rem 0.5rem;
            border-radius: 100px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    @include('partials.main-header')

    <div class="animated-bg"></div>
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="main-content">
        <!-- هيدر المعرض -->
        <section class="gallery-hero">
            <h1 class="gallery-title">معرض صور العائلة</h1>
            <!-- الإحصائيات -->
            <div class="stats-bar">
                <div class="stats-row">
                    <div class="stat-item info-only">
                        <div class="stat-icon teal">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['total_categories'] }}</div>
                            <div class="stat-label">فئة</div>
                        </div>
                    </div>
                    <div class="stat-item info-only">
                        <div class="stat-icon amber">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['total_images'] }}</div>
                            <div class="stat-label">صورة</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-item highlight" onclick="showRecentUploads()">
                    <div class="stat-icon pink">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stats['recent_uploads'] }}</div>
                        <div class="stat-label">جديد هذا الأسبوع (اضغط للعرض)</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- مسار التنقل -->
        <nav class="breadcrumb-nav" id="breadcrumb">
            <div class="breadcrumb-item active" onclick="showRootCategories()">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span>الرئيسية</span>
            </div>
        </nav>

        <!-- قسم الفئات -->
        <section class="categories-section" id="categories-section">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-title-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </span>
                    <span id="section-title-text">جميع الفئات</span>
                </h2>
            </div>

            <div class="category-info-box" id="category-info-box" style="display: none;">
                <h3 class="category-info-title" id="category-info-title"></h3>
                <p class="category-info-description" id="category-info-description"></p>
            </div>

            <div class="categories-grid" id="categories-grid">
                @foreach($categories as $index => $category)
                    <div class="category-card {{ !$category->is_active ? 'inactive' : '' }} {{ $category->parent_id ? 'subcategory' : '' }}"
                         style="animation-delay: {{ $index * 0.1 }}s"
                         onclick="openCategory({{ $category->id }})">

                        @if(!$category->is_active && $isAuthenticated)
                            <div class="inactive-badge">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                غير متاحة
                            </div>
                        @endif

                        @if($category->parent_id)
                            <div class="subcategory-indicator"></div>
                        @endif

                        @if($category->children->count() > 0)
                            <div class="subcategories-badge">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                                </svg>
                                {{ $category->children->count() }}
                            </div>
                        @endif

                        <div class="card-preview">
                            {{-- <div class="category-icon-wrapper">
                                <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24" class="category-icon">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                </svg>
                            </div> --}}
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">
                                @if($category->children->count() > 0)
                                    <svg width="18" height="18" fill="var(--secondary)" viewBox="0 0 24 24">
                                        <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                                    </svg>
                                @else
                                    <svg width="18" height="18" fill="var(--primary-light)" viewBox="0 0 24 24">
                                        <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                                    </svg>
                                @endif
                                {{ $category->name }}
                            </h3>
                            <div class="card-meta">
                                <span class="card-meta-item">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2z"/>
                                    </svg>
                                    {{ $category->total_images_count ?? $category->images_count }} صورة
                                </span>
                                @if($category->children->count() > 0)
                                    <span class="card-meta-item">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                                        </svg>
                                        {{ $category->children->count() }} فئة فرعية
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- قسم الصور -->
        <section class="images-section" id="images-section">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-title-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                    </span>
                    <span id="images-section-title">الصور</span>
                </h2>
            </div>

            <div class="category-info-box" id="images-category-info-box" style="display: none;">
                <h3 class="category-info-title" id="images-category-info-title"></h3>
                <p class="category-info-description" id="images-category-info-description"></p>
            </div>

            <div class="images-grid" id="images-grid"></div>

            <div class="empty-state" id="empty-state" style="display: none;">
                <div class="empty-icon">
                    <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                    </svg>
                </div>
                <h3 class="empty-title">هذا المجلد فارغ</h3>
                <p class="empty-text">لا توجد صور في هذه الفئة حالياً</p>
            </div>
        </section>
    </div>

    <!-- Modal خيارات الصورة -->
    <div class="modal-backdrop" id="imageModal">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">تفاصيل الصورة</h3>
                <button class="modal-close" onclick="closeImageModal()">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>
            <div class="modal-preview">
                <img id="modalImage" src="" alt="">
            </div>
            <div class="modal-body">
                <div class="modal-info-item" id="modalTitleItem" style="display: none;">
                    <div class="modal-info-icon">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="modal-info-label">العنوان</div>
                        <div class="modal-info-value" id="modalTitle"></div>
                    </div>
                </div>
                <div class="modal-info-item" id="modalAuthorItem" style="display: none;">
                    <div class="modal-info-icon">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="modal-info-label">المؤلف</div>
                        <div class="modal-info-value" id="modalAuthor"></div>
                    </div>
                </div>
                <div class="modal-info-item" id="modalCategoryItem" style="display: none;">
                    <div class="modal-info-icon">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="modal-info-label">الفئة</div>
                        <div class="modal-info-value" id="modalCategory"></div>
                    </div>
                </div>
                <div id="modalMentionedPersons" style="display: none;"></div>
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-primary" onclick="viewFullscreen()">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                    </svg>
                    عرض بالحجم الكامل
                </button>
                <button class="modal-btn modal-btn-secondary" id="modalPdfBtn" style="display: none;" onclick="downloadCurrentPdf()">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                    </svg>
                    تحميل PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Fullscreen Modal -->
    <div class="fullscreen-modal" id="fullscreenModal" onclick="closeFullscreen()">
        <button class="fullscreen-close" onclick="closeFullscreen()">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
        </button>
        <img id="fullscreenImage" class="fullscreen-image" src="" alt="" onclick="event.stopPropagation()">
    </div>

    <script>
        // بيانات الفئات والصور
        const galleryData = @json($categoriesWithImages);
        const storageUrl = '{{ asset("storage") }}';
        const pdfPlaceholder = '{{ asset("assets/img/base-pdf-img.jpg") }}';

        let currentImageData = null;
        let breadcrumbPath = [];

        // عرض الفئات الرئيسية
        function showRootCategories() {
            breadcrumbPath = [];
            updateBreadcrumb();
            document.getElementById('categories-section').style.display = 'block';
            document.getElementById('images-section').classList.remove('active');
            document.getElementById('section-title-text').textContent = 'جميع الفئات';
            document.getElementById('category-info-box').style.display = 'none';
            renderCategories(getRootCategories());
        }

        // الحصول على الفئات الرئيسية
        function getRootCategories() {
            return galleryData.filter(cat => !cat.parent_id || cat.parent_id === null);
        }

        // الحصول على الفئات الفرعية
        function getChildCategories(parentId) {
            // تحويل parentId إلى number للتأكد من المقارنة الصحيحة
            const parentIdNum = parseInt(parentId);
            return galleryData.filter(cat => {
                // تحويل parent_id إلى number أيضاً
                const catParentId = cat.parent_id ? parseInt(cat.parent_id) : null;
                return catParentId === parentIdNum;
            });
        }

        // الحصول على فئة بالـ ID
        function getCategoryById(id) {
            // تحويل id إلى number للتأكد من المقارنة الصحيحة
            const idNum = parseInt(id);
            return galleryData.find(cat => {
                const catId = cat.id ? parseInt(cat.id) : null;
                return catId === idNum;
            });
        }

        // عرض الصور الجديدة هذا الأسبوع
        function showRecentUploads() {
            let recentImages = [];
            const now = new Date();
            const sevenDaysAgo = new Date();
            sevenDaysAgo.setDate(now.getDate() - 7);

            function collectImages(categories) {
                categories.forEach(cat => {
                    if (cat.images) {
                        cat.images.forEach(img => {
                            const createdAt = new Date(img.created_at);
                            if (createdAt >= sevenDaysAgo) {
                                if (!recentImages.some(ri => ri.id === img.id)) {
                                    recentImages.push(img);
                                }
                            }
                        });
                    }
                    if (cat.children) {
                        collectImages(cat.children);
                    }
                });
            }

            collectImages(galleryData);

            // ترتيب الصور من الأحدث للأقدم
            recentImages.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            breadcrumbPath = [{ id: 'recent', name: 'جديد هذا الأسبوع' }];
            updateBreadcrumb();
            
            document.getElementById('categories-section').style.display = 'none';
            document.getElementById('category-info-box').style.display = 'none';
            document.getElementById('images-category-info-box').style.display = 'none';
            
            const section = document.getElementById('images-section');
            const grid = document.getElementById('images-grid');
            const emptyState = document.getElementById('empty-state');

            document.getElementById('images-section-title').textContent = 'جديد هذا الأسبوع';
            section.classList.add('active');

            if (recentImages.length === 0) {
                grid.style.display = 'none';
                emptyState.style.display = 'block';
            } else {
                grid.style.display = 'grid';
                emptyState.style.display = 'none';
                grid.innerHTML = '';
                recentImages.forEach((image, index) => {
                    const card = createImageCard(image, index);
                    grid.appendChild(card);
                });
                initLazyLoading();
            }
        }

        // فتح فئة
        function openCategory(categoryId) {
            const category = getCategoryById(categoryId);
            if (!category) return;

            const children = getChildCategories(categoryId);

            // إضافة للمسار
            breadcrumbPath.push({ id: category.id, name: category.name });
            updateBreadcrumb();

            // عرض معلومات الفئة
            displayCategoryInfo(category);

            if (children.length > 0) {
                // عرض الفئات الفرعية
                document.getElementById('categories-section').style.display = 'block';
                document.getElementById('section-title-text').textContent = category.name;
                renderCategories(children);

                // إذا كانت هناك صور في الفئة الحالية، اعرضها أيضاً
                if (category.images && category.images.length > 0) {
                    showImagesSection(category);
                } else {
                    // إخفاء قسم الصور إذا لم تكن هناك صور مباشرة
                    document.getElementById('images-section').classList.remove('active');
                }
            } else {
                // لا توجد فئات فرعية - عرض الصور فقط
                document.getElementById('categories-section').style.display = 'none';
                showImagesSection(category);
            }
        }

        // عرض معلومات الفئة
        function displayCategoryInfo(category) {
            const infoBox = document.getElementById('category-info-box');
            const infoTitle = document.getElementById('category-info-title');
            const infoDescription = document.getElementById('category-info-description');
            const imagesInfoBox = document.getElementById('images-category-info-box');
            const imagesInfoTitle = document.getElementById('images-category-info-title');
            const imagesInfoDescription = document.getElementById('images-category-info-description');

            if (category && category.description) {
                // عرض في قسم الفئات
                infoTitle.textContent = category.name;
                infoDescription.textContent = category.description;
                infoBox.style.display = 'block';

                // عرض في قسم الصور
                imagesInfoTitle.textContent = category.name;
                imagesInfoDescription.textContent = category.description;
                imagesInfoBox.style.display = 'block';
            } else {
                // إخفاء إذا لم يكن هناك وصف
                infoBox.style.display = 'none';
                imagesInfoBox.style.display = 'none';
            }
        }

        // عرض الفئات
        function renderCategories(categories) {
            const grid = document.getElementById('categories-grid');
            grid.innerHTML = '';

            const isAuthenticated = {{ $isAuthenticated ? 'true' : 'false' }};

            categories.forEach((category, index) => {
                const children = getChildCategories(category.id);
                const totalImages = countTotalImages(category);
                const previewImages = category.images ? category.images.slice(0, 4) : [];

                // تحديد الفئات غير المتاحة والفئات الفرعية
                const isInactive = category.is_active === false;
                const isSubcategory = category.parent_id !== null;

                let cardClasses = 'category-card';
                if (isInactive) cardClasses += ' inactive';
                if (isSubcategory) cardClasses += ' subcategory';

                const card = document.createElement('div');
                card.className = cardClasses;
                card.style.animationDelay = `${index * 0.1}s`;
                card.onclick = () => openCategory(category.id);

                card.innerHTML = `
                    ${isInactive && isAuthenticated ? `
                        <div class="inactive-badge">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            غير متاحة
                        </div>
                    ` : ''}
                    ${isSubcategory ? '<div class="subcategory-indicator"></div>' : ''}
                    ${children.length > 0 ? `
                        <div class="subcategories-badge">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                            </svg>
                            ${children.length}
                        </div>
                    ` : ''}
                    <div class="card-preview">
                        {{-- <div class="category-icon-wrapper">
                            <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24" class="category-icon">
                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                            </svg>
                        </div> --}}
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">
                            <svg width="18" height="18" fill="${children.length > 0 ? 'var(--secondary)' : 'var(--primary-light)'}" viewBox="0 0 24 24">
                                <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                            </svg>
                            ${category.name}
                        </h3>
                        <div class="card-meta">
                            <span class="card-meta-item">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2z"/>
                                </svg>
                                ${totalImages} صورة
                            </span>
                            ${children.length > 0 ? `
                                <span class="card-meta-item">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                                    </svg>
                                    ${children.length} فئة فرعية
                                </span>
                            ` : ''}
                        </div>
                    </div>
                `;

                grid.appendChild(card);
            });
        }

        // حساب عدد الصور الكلي
        function countTotalImages(category) {
            let count = category.images ? category.images.length : 0;
            const children = getChildCategories(category.id);
            children.forEach(child => {
                count += countTotalImages(child);
            });
            return count;
        }

        // التحقق من التحديث الحديث
        function isRecentlyUpdated(dateString) {
            if (!dateString) return false;
            const date = new Date(dateString);
            const now = new Date();
            const diffDays = Math.ceil((now - date) / (1000 * 60 * 60 * 24));
            return diffDays <= 7;
        }

        // عرض قسم الصور
        function showImagesSection(category) {
            const section = document.getElementById('images-section');
            const grid = document.getElementById('images-grid');
            const emptyState = document.getElementById('empty-state');

            document.getElementById('images-section-title').textContent = category.name;
            section.classList.add('active');

            // التحقق من وجود صور مباشرة
            if (!category.images || category.images.length === 0) {
                // لا توجد صور مباشرة - إخفاء قسم الصور
                grid.style.display = 'none';
                emptyState.style.display = 'none';
                section.classList.remove('active');
                return;
            }

            // عرض الصور المباشرة
            grid.style.display = 'grid';
            emptyState.style.display = 'none';
            grid.innerHTML = '';

            category.images.forEach((image, index) => {
                const card = createImageCard(image, index);
                grid.appendChild(card);
            });

            initLazyLoading();
        }

        // إنشاء بطاقة الصورة
        function createImageCard(image, index) {
            const card = document.createElement('div');
            card.className = 'image-card';
            card.style.animationDelay = `${index * 0.05}s`;

            const isYouTube = image.media_type === 'youtube';
            const isPdf = image.media_type === 'pdf';
            const title = image.article?.title || '';
            const author = image.article?.person?.name || '';

            let thumbnailUrl = '';
            if (isYouTube && image.youtube_url) {
                const videoId = extractVideoId(image.youtube_url);
                thumbnailUrl = image.thumbnail_path
                    ? `${storageUrl}/${image.thumbnail_path}`
                    : (videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '');
            } else if (isPdf) {
                thumbnailUrl = image.thumbnail_path
                    ? `${storageUrl}/${image.thumbnail_path}`
                    : pdfPlaceholder;
            } else {
                thumbnailUrl = image.thumbnail_path
                    ? `${storageUrl}/${image.thumbnail_path}`
                    : `${storageUrl}/${image.path}`;
            }

            const imageData = {
                id: image.id,
                path: image.path,
                thumbnail_path: image.thumbnail_path,
                youtube_url: image.youtube_url,
                media_type: image.media_type,
                file_size: image.file_size,
                title: title,
                author: author,
                category: image.article?.category?.name || '',
                mentioned_persons: image.mentioned_persons || []
            };

            card.onclick = () => showImageOptions(imageData);

            card.innerHTML = `
                ${isYouTube ? '<div class="file-type-badge youtube">YouTube</div>' : ''}
                ${isPdf ? '<div class="file-type-badge pdf">PDF</div>' : ''}
                <div class="image-wrapper">
                    <img data-src="${thumbnailUrl}" alt="${title}" class="lazy-image">
                    <div class="lazy-placeholder"></div>
                </div>
                <div class="image-overlay"></div>
                <div class="image-info">
                    ${title ? `<div class="image-title">${title}</div>` : ''}
                    ${author ? `<div class="image-author">${author}</div>` : ''}
                    ${renderMentionedPersons(image.mentioned_persons)}
                </div>
            `;

            return card;
        }

        // رسم الأشخاص المذكورين
        function renderMentionedPersons(persons) {
            if (!persons || persons.length === 0) return '';
            const validPersons = persons.filter(p => p && p.full_name);
            if (validPersons.length === 0) return '';

            return `
                <div class="mentioned-persons">
                    ${validPersons.slice(0, 3).map(p =>
                        `<span class="mentioned-tag">${p.full_name}</span>`
                    ).join('')}
                    ${validPersons.length > 3 ? `<span class="mentioned-tag">+${validPersons.length - 3}</span>` : ''}
                </div>
            `;
        }

        // استخراج معرف فيديو يوتيوب
        function extractVideoId(url) {
            if (!url) return '';
            const patterns = [
                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/
            ];
            for (let pattern of patterns) {
                const match = url.match(pattern);
                if (match) return match[1];
            }
            return '';
        }

        // تحديث مسار التنقل
        function updateBreadcrumb() {
            const nav = document.getElementById('breadcrumb');
            nav.innerHTML = `
                <div class="breadcrumb-item ${breadcrumbPath.length === 0 ? 'active' : ''}" onclick="showRootCategories()">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    <span>الرئيسية</span>
                </div>
            `;

            breadcrumbPath.forEach((item, index) => {
                const isLast = index === breadcrumbPath.length - 1;
                nav.innerHTML += `
                    <span class="breadcrumb-separator">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>
                    </span>
                    <div class="breadcrumb-item ${isLast ? 'active' : ''}" onclick="navigateToBreadcrumb(${index})">
                        <span>${item.name}</span>
                    </div>
                `;
            });
        }

        // التنقل عبر مسار التنقل
        function navigateToBreadcrumb(index) {
            const item = breadcrumbPath[index];
            if (item.id === 'recent') {
                showRecentUploads();
                return;
            }
            breadcrumbPath = breadcrumbPath.slice(0, index + 1);
            const categoryId = breadcrumbPath[index].id;
            breadcrumbPath.pop(); // سيتم إضافته مرة أخرى في openCategory
            openCategory(categoryId);
        }

        // عرض خيارات الصورة
        function showImageOptions(imageData) {
            currentImageData = imageData;
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalAuthor = document.getElementById('modalAuthor');
            const modalCategory = document.getElementById('modalCategory');
            const pdfBtn = document.getElementById('modalPdfBtn');

            // تحديد الصورة
            if (imageData.media_type === 'youtube' && imageData.youtube_url) {
                const videoId = extractVideoId(imageData.youtube_url);
                modalImage.src = imageData.thumbnail_path
                    ? `${storageUrl}/${imageData.thumbnail_path}`
                    : `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`;
            } else if (imageData.media_type === 'pdf') {
                modalImage.src = imageData.thumbnail_path
                    ? `${storageUrl}/${imageData.thumbnail_path}`
                    : pdfPlaceholder;
                pdfBtn.style.display = 'flex';
            } else {
                modalImage.src = `${storageUrl}/${imageData.path}`;
                pdfBtn.style.display = 'none';
            }

            // المعلومات
            if (imageData.title) {
                modalTitle.textContent = imageData.title;
                document.getElementById('modalTitleItem').style.display = 'flex';
            } else {
                document.getElementById('modalTitleItem').style.display = 'none';
            }

            if (imageData.author) {
                modalAuthor.textContent = imageData.author;
                document.getElementById('modalAuthorItem').style.display = 'flex';
            } else {
                document.getElementById('modalAuthorItem').style.display = 'none';
            }

            if (imageData.category) {
                modalCategory.textContent = imageData.category;
                document.getElementById('modalCategoryItem').style.display = 'flex';
            } else {
                document.getElementById('modalCategoryItem').style.display = 'none';
            }

            // الأشخاص المذكورين
            const mentionedDiv = document.getElementById('modalMentionedPersons');
            if (imageData.mentioned_persons && imageData.mentioned_persons.length > 0) {
                const validPersons = imageData.mentioned_persons.filter(p => p && p.full_name);
                if (validPersons.length > 0) {
                    mentionedDiv.innerHTML = `
                        <div class="modal-info-item">
                            <div class="modal-info-icon">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="modal-info-label">الأشخاص المذكورون</div>
                                <div class="mentioned-persons" style="margin-top: 0.5rem;">
                                    ${validPersons.map(p => `<span class="mentioned-tag">${p.full_name}</span>`).join('')}
                                </div>
                            </div>
                        </div>
                    `;
                    mentionedDiv.style.display = 'block';
                } else {
                    mentionedDiv.style.display = 'none';
                }
            } else {
                mentionedDiv.style.display = 'none';
            }

            modal.classList.add('active');
        }

        // إغلاق Modal
        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('active');
        }

        // عرض بالحجم الكامل
        function viewFullscreen() {
            if (!currentImageData) return;

            const modal = document.getElementById('fullscreenModal');
            const img = document.getElementById('fullscreenImage');

            if (currentImageData.media_type === 'youtube' && currentImageData.youtube_url) {
                window.open(currentImageData.youtube_url, '_blank');
                return;
            }

            if (currentImageData.media_type === 'pdf') {
                window.open(`${storageUrl}/${currentImageData.path}`, '_blank');
                return;
            }

            img.src = `${storageUrl}/${currentImageData.path}`;
            modal.classList.add('active');
            closeImageModal();
        }

        // إغلاق Fullscreen
        function closeFullscreen() {
            document.getElementById('fullscreenModal').classList.remove('active');
        }

        // تحميل PDF
        function downloadCurrentPdf() {
            if (currentImageData && currentImageData.path) {
                const link = document.createElement('a');
                link.href = `${storageUrl}/${currentImageData.path}`;
                link.download = currentImageData.title || 'document.pdf';
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        // Lazy Loading
        function initLazyLoading() {
            const lazyImages = document.querySelectorAll('.lazy-image:not(.loaded)');

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.onload = () => {
                                img.classList.add('loaded');
                                const placeholder = img.nextElementSibling;
                                if (placeholder) placeholder.style.display = 'none';
                            };
                            observer.unobserve(img);
                        }
                    });
                }, { rootMargin: '100px' });

                lazyImages.forEach(img => observer.observe(img));
            } else {
                lazyImages.forEach(img => {
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                });
            }
        }

        // إغلاق المودال بالضغط على Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeImageModal();
                closeFullscreen();
            }
        });

        // إغلاق Modal بالنقر خارجه
        document.getElementById('imageModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                closeImageModal();
            }
        });

        // تهيئة الصفحة
        document.addEventListener('DOMContentLoaded', () => {
            showRootCategories();
        });
    </script>
</body>
</html>
