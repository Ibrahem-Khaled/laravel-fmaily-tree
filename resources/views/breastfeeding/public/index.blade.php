<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
            --border-color: #e0e0e0;
            --accent-color: #37a05c;
            --light-accent: #DCF2DD;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
        }

        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e9 100%);
            font-family: 'Alexandria', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        .breastfeeding-section {
            padding-top: 100px;
            padding-bottom: 40px;
            min-height: 100vh;
        }

        .section-title {
            color: var(--dark-green);
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        /* View Mode Toggle */
        .view-mode-toggle {
            background: white;
            border-radius: 15px;
            padding: 8px;
            box-shadow: var(--shadow-sm);
            border: 2px solid var(--border-color);
            display: inline-flex;
            gap: 5px;
            margin-bottom: 25px;
        }

        .view-mode-btn {
            padding: 10px 20px;
            border: none;
            background: transparent;
            border-radius: 10px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .view-mode-btn.active {
            background: linear-gradient(135deg, var(--accent-color), var(--dark-green));
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .view-mode-btn:hover:not(.active) {
            background: var(--light-gray);
            color: var(--dark-green);
        }

        /* Search Section */
        .search-section {
            background: linear-gradient(135deg, white 0%, var(--light-gray) 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .search-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--light-green));
        }

        .search-section .form-control {
            border-radius: 15px;
            border: 2px solid var(--border-color);
            padding: 15px 50px 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }

        .search-section .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(55, 160, 92, 0.25), inset 0 2px 4px rgba(0,0,0,0.05);
            outline: none;
        }

        .search-section .form-control::placeholder {
            color: #999;
            font-style: italic;
        }

        .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-input-wrapper i.search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-color);
            font-size: 1.1rem;
            pointer-events: none;
        }

        .search-section .form-control {
            flex: 1;
        }

        .search-btn-icon {
            background: linear-gradient(135deg, var(--accent-color), var(--dark-green));
            border: none;
            border-radius: 15px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }

        .search-btn-icon:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: linear-gradient(135deg, var(--dark-green), var(--accent-color));
        }

        .search-btn-icon:active {
            transform: translateY(0);
        }

        /* Stats Section */
        .stats-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--border-color);
        }

        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            background: linear-gradient(135deg, var(--light-gray) 0%, white 100%);
            margin-bottom: 15px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--accent-color);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--accent-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Info Card Styles */
        .info-card-wrapper {
            margin-bottom: 30px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 2px solid #e8f5e9;
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 5px;
            background: linear-gradient(90deg, #37a05c, #4ade80, #37a05c);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(55, 160, 92, 0.2);
        }

        .info-card-header {
            background: linear-gradient(135deg, #37a05c 0%, #145147 100%);
            padding: 25px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            flex: 1;
        }

        .info-card-body {
            padding: 30px;
        }

        .info-intro {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            border-right: 4px solid #37a05c;
            position: relative;
        }

        .info-intro::before {
            content: '"';
            position: absolute;
            top: -10px;
            right: 20px;
            font-size: 4rem;
            color: #37a05c;
            opacity: 0.2;
            font-family: serif;
        }

        .info-list {
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 18px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            border: 2px solid #e8f5e9;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #37a05c, #4ade80);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 20px rgba(55, 160, 92, 0.15);
            border-color: #37a05c;
        }

        .info-item:hover::before {
            transform: scaleY(1);
        }

        .info-item-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #37a05c, #4ade80);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(55, 160, 92, 0.3);
        }

        .info-item-content {
            flex: 1;
            font-size: 1rem;
            line-height: 1.7;
            color: #444;
        }

        .info-item-content strong {
            color: #145147;
            font-weight: 700;
        }

        .info-summary {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-radius: 15px;
            border: 2px solid #fed7aa;
            margin-bottom: 20px;
            position: relative;
        }

        .info-summary::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
            border-radius: 15px 15px 0 0;
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .summary-content {
            flex: 1;
            font-size: 1.05rem;
            line-height: 1.7;
            color: #78350f;
        }

        .summary-content strong {
            color: #92400e;
            font-weight: 700;
        }

        .info-note {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 15px;
            border: 2px solid #fecaca;
            position: relative;
        }

        .info-note::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #ef4444, #f87171);
            border-radius: 15px 15px 0 0;
        }

        .note-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ef4444, #f87171);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .note-content {
            flex: 1;
            font-size: 1.05rem;
            line-height: 1.7;
            color: #7f1d1d;
        }

        .note-content strong {
            color: #991b1b;
            font-weight: 700;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .info-card-wrapper {
                margin-bottom: 20px;
            }

            .info-card-header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }

            .info-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .info-title {
                font-size: 1.3rem;
            }

            .info-card-body {
                padding: 20px;
            }

            .info-intro {
                font-size: 1rem;
                padding: 15px;
            }

            .info-item {
                padding: 15px;
            }

            .info-item-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .info-item-content {
                font-size: 0.95rem;
            }

            .info-summary,
            .info-note {
                padding: 15px;
                flex-direction: column;
                text-align: center;
            }

            .summary-icon,
            .note-icon {
                margin: 0 auto;
            }
        }

        /* List Section */
        .list-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--border-color);
            height: fit-content;
            max-height: 75vh;
            overflow-y: auto;
        }

        .list-section::-webkit-scrollbar {
            width: 8px;
        }

        .list-section::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 10px;
        }

        .list-section::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 10px;
        }

        .list-title {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-green);
        }

        /* Card Styles */
        .person-card {
            background: linear-gradient(135deg, white 0%, var(--light-gray) 100%);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 18px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .person-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--accent-color), var(--light-green));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .person-card:hover {
            border-color: var(--accent-color);
            transform: translateX(-5px);
            box-shadow: var(--shadow-md);
        }

        .person-card:hover::before {
            transform: scaleY(1);
        }

        .person-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, var(--light-accent) 0%, white 100%);
            box-shadow: var(--shadow-md);
        }

        .person-card.active::before {
            transform: scaleY(1);
        }

        .person-photo {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            margin-left: 15px;
        }

        .person-card:hover .person-photo {
            transform: scale(1.08);
            box-shadow: var(--shadow-md);
        }

        .person-info {
            flex: 1;
        }

        .person-name {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.05rem;
            margin-bottom: 5px;
            transition: color 0.3s ease;
        }

        .person-card:hover .person-name {
            color: var(--accent-color);
        }

        .person-name a {
            color: inherit;
            text-decoration: none;
        }

        .person-name a:hover {
            text-decoration: underline;
        }

        .profile-link {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .profile-link:hover {
            color: var(--dark-green);
            transform: translateX(-3px);
        }

        .profile-link i {
            font-size: 0.75rem;
        }

        .card-hint {
            font-size: 0.75rem;
            color: #999;
            margin-top: 8px;
            font-style: italic;
        }

        .person-meta {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.5;
        }

        .count-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, var(--accent-color), var(--dark-green));
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: var(--shadow-sm);
        }

        /* Details Section */
        .details-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--border-color);
            min-height: 75vh;
        }

        .details-header {
            background: linear-gradient(135deg, var(--light-green) 0%, white 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border: 2px solid var(--accent-color);
        }

        .details-title {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .detail-card {
            background: linear-gradient(135deg, var(--light-gray) 0%, white 100%);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--light-green));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .detail-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .detail-card:hover::before {
            transform: scaleX(1);
        }

        .detail-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, var(--light-accent) 0%, white 100%);
            box-shadow: var(--shadow-md);
        }

        .detail-card.active::before {
            transform: scaleX(1);
        }

        .detail-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .detail-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: var(--shadow-sm);
            margin-left: 15px;
        }

        .detail-info {
            flex: 1;
        }

        .detail-name {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .detail-name a {
            color: inherit;
            text-decoration: none;
        }

        .detail-name a:hover {
            text-decoration: underline;
        }

        .detail-dates {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.5;
        }

        .father-info {
            background: var(--light-accent);
            border-radius: 10px;
            padding: 12px;
            margin-top: 12px;
            border-right: 3px solid var(--accent-color);
        }

        .father-info-label {
            font-size: 0.8rem;
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .father-name {
            font-size: 0.9rem;
            color: var(--dark-green);
            font-weight: 500;
        }

        .father-name a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .father-name a:hover {
            text-decoration: underline;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .status-completed {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        .notes-section {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            margin-top: 25px;
            display: none;
        }

        .notes-section.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notes-title {
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-green);
        }

        .notes-content {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 15px;
            line-height: 1.8;
            color: #555;
        }

        .empty-state {
            text-align: center;
            padding: 50px 30px;
            color: #666;
            background: linear-gradient(135deg, var(--light-gray) 0%, white 100%);
            border-radius: 15px;
            border: 2px dashed var(--border-color);
        }

        .empty-state i {
            font-size: 3.5rem;
            color: var(--accent-color);
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state p {
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0;
        }

        /* Mobile Children Section */
        .mobile-children-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--light-green);
            animation: slideDown 0.3s ease;
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

        .mobile-notes {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--border-color);
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .breastfeeding-section {
                padding-top: 80px;
                padding-left: 10px;
                padding-right: 10px;
            }

            .section-title {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .search-section, .stats-section {
                padding: 20px;
                margin-bottom: 20px;
            }

            .search-section .form-control {
                padding: 12px 45px 12px 15px;
                font-size: 0.95rem;
            }

            .search-input-wrapper i.search-icon {
                right: 15px;
                font-size: 1rem;
            }

            .search-btn-icon {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .stat-card {
                padding: 15px;
                margin-bottom: 10px;
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .stat-label {
                font-size: 0.85rem;
            }

            .list-section {
                padding: 15px;
                margin-bottom: 20px;
                max-height: none;
            }

            .list-title {
                font-size: 1.1rem;
                margin-bottom: 15px;
            }

            .person-card {
                padding: 15px;
                margin-bottom: 10px;
            }

            .person-photo {
                width: 50px;
                height: 50px;
                margin-left: 12px;
            }

            .person-name {
                font-size: 1rem;
            }

            .person-meta {
                font-size: 0.8rem;
            }

            .count-badge {
                width: 24px;
                height: 24px;
                font-size: 0.75rem;
            }

            .details-section {
                padding: 15px;
                min-height: auto;
            }

            .details-header {
                padding: 15px;
                margin-bottom: 20px;
            }

            .details-title {
                font-size: 1.1rem;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .detail-card {
                padding: 15px;
            }

            .detail-photo {
                width: 50px;
                height: 50px;
                margin-left: 12px;
            }

            .detail-name {
                font-size: 1rem;
            }

            .detail-dates {
                font-size: 0.8rem;
            }

            .view-mode-toggle {
                width: 100%;
                justify-content: center;
                margin-bottom: 20px;
            }

            .view-mode-btn {
                flex: 1;
                padding: 10px 15px;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .breastfeeding-section {
                padding-top: 70px;
                padding-left: 5px;
                padding-right: 5px;
            }

            .section-title {
                font-size: 1.3rem;
            }

            .search-section, .stats-section, .list-section, .details-section {
                padding: 12px;
                border-radius: 15px;
            }

            .person-card, .detail-card {
                padding: 12px;
            }

            .person-photo, .detail-photo {
                width: 45px;
                height: 45px;
            }

            .person-name, .detail-name {
                font-size: 0.95rem;
            }

            .view-mode-btn {
                padding: 8px 12px;
                font-size: 0.8rem;
            }

            .card-hint {
                font-size: 0.7rem;
                margin-top: 6px;
            }

            .profile-link {
                font-size: 0.8rem;
                margin-top: 4px;
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

                {{-- بطاقة معلومات الرضاعة --}}
                <div class="info-card-wrapper">
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h3 class="info-title">معلومات مهمة عن الرضاعة</h3>
                        </div>
                        <div class="info-card-body">
                            <p class="info-intro">
                                الرضاعة تجعل الطفل من أهل الأسرة من ناحية المحارم فقط. فإذا رضع طفل أقل من سنتين خمس رضعات مشبعات من امرأة، تصبح هذه المرأة أمه بالرضاعة، ويصبح زوجها أباه بالرضاعة لأن اللبن يُنسب إليه أيضًا.
                            </p>

                            <div class="info-list">
                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="info-item-content">
                                        <strong>إخوان وأخوات الأم</strong> يصيرون خالات وأخوالًا له بالرضاعة.
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <div class="info-item-content">
                                        <strong>إخوان وأخوات الأب</strong> يصيرون أعمامًا وعمّات بالرضاعة.
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-child"></i>
                                    </div>
                                    <div class="info-item-content">
                                        <strong>أبناء الأم أو الأب</strong> من أي زواج يصبحون إخوة له بالرضاعة.
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-item-icon">
                                        <i class="fas fa-chess-king"></i>
                                    </div>
                                    <div class="info-item-content">
                                        <strong>والدا الأم والأب</strong> يصبحون أجدادًا له بالرضاعة.
                                    </div>
                                </div>
                            </div>

                            <div class="info-summary">
                                <div class="summary-icon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div class="summary-content">
                                    <strong>وباختصار:</strong> كل قريب بالنسب يتحول لنفس القريب بالرضاعة إذا كان اللبن واحدًا.
                                </div>
                            </div>

                            <div class="info-note">
                                <div class="note-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="note-content">
                                    <strong>ملاحظة مهمة:</strong> الرضاعة تعطي محرمية وتحريم زواج فقط، ولا تغيّر النسب، ولا تُورث، ولا توجب نفقة.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- زر التبديل بين الوضعين --}}
                <div class="text-center">
                    <div class="view-mode-toggle">
                        <button class="view-mode-btn {{ $viewMode === 'mothers' ? 'active' : '' }}"
                                onclick="switchViewMode('mothers')">
                            <i class="fas fa-female"></i> عرض حسب الأمهات
                        </button>
                        <button class="view-mode-btn {{ $viewMode === 'children' ? 'active' : '' }}"
                                onclick="switchViewMode('children')">
                            <i class="fas fa-baby"></i> عرض حسب الأطفال
                        </button>
                    </div>
                </div>

                {{-- شريط البحث --}}
                <div class="search-section">
                    <form method="GET" action="{{ route('breastfeeding.public.index') }}" id="searchForm">
                        <input type="hidden" name="view_mode" value="{{ $viewMode }}">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   class="form-control"
                                   placeholder="ابحث عن أم مرضعة أو طفل مرتضع..."
                                   id="searchInput"
                                   autocomplete="off">
                            <button type="submit" class="search-btn-icon" title="بحث">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @if($search)
                            <div class="mt-3 text-center">
                                <a href="{{ route('breastfeeding.public.index', ['view_mode' => $viewMode]) }}"
                                   class="text-decoration-none"
                                   style="color: var(--accent-color); font-weight: 600;">
                                    <i class="fas fa-times-circle"></i> إلغاء البحث
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                {{-- الإحصائيات --}}
                <div class="stats-section">
                    <div class="row">
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['total_relationships'] }}</div>
                                <div class="stat-label">إجمالي العلاقات</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['total_nursing_mothers'] }}</div>
                                <div class="stat-label">الأمهات المرضعات</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['total_breastfed_children'] }}</div>
                                <div class="stat-label">الأطفال المرتضعين</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['active_breastfeeding'] }}</div>
                                <div class="stat-label">رضاعة نشطة</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- المحتوى حسب الوضع --}}
                @if($viewMode === 'mothers')
                    {{-- وضع عرض حسب الأمهات --}}
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="list-section">
                                <h4 class="list-title"><i class="fas fa-female text-success"></i> الأمهات المرضعات</h4>
                                @if($mothersData->isNotEmpty())
                                    <div id="mothersList">
                                        @foreach($mothersData as $mother)
                                            <div class="person-card" onclick="showMotherDetails({{ $mother['id'] }}, event)"
                                                 data-mother-id="{{ $mother['id'] }}">
                                                <div class="count-badge">{{ count($mother['children']) }}</div>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $mother['avatar'] }}" alt="{{ $mother['name'] }}" class="person-photo">
                                                    <div class="person-info">
                                                        <div class="person-name">{{ $mother['name'] }}</div>
                                                        <div class="person-meta">{{ count($mother['children']) }} طفل مرتضع</div>
                                                        <a href="{{ route('people.profile.show', $mother['id']) }}"
                                                           onclick="event.stopPropagation();"
                                                           class="profile-link"
                                                           title="عرض الملف الشخصي">
                                                            <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="card-hint">
                                                    <i class="fas fa-info-circle"></i> اضغط على البطاقة لعرض التفاصيل
                                                </div>
                                                {{-- عرض الأبناء على الهواتف --}}
                                                <div class="mobile-children-section" id="mobileChildren_{{ $mother['id'] }}" style="display: none;">
                                                    <div class="details-grid" id="mobileChildrenGrid_{{ $mother['id'] }}">
                                                        <!-- سيتم ملؤها بواسطة JavaScript -->
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-baby"></i>
                                        <p>لا توجد أمهات مرضعات مسجلة</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-7 desktop-family-section">
                            <div class="details-section">
                                <div id="motherDetails">
                                    <div class="empty-state">
                                        <i class="fas fa-info-circle"></i>
                                        <p>اختر أمًا لعرض تفاصيل عائلتها</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- وضع عرض حسب الأطفال --}}
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="list-section">
                                <h4 class="list-title"><i class="fas fa-baby text-success"></i> الأطفال المرتضعين</h4>
                                @if(isset($childrenData) && $childrenData->isNotEmpty())
                                    <div id="childrenList">
                                        @foreach($childrenData as $child)
                                            <div class="person-card" onclick="showChildDetails({{ $child['id'] }})"
                                                 data-child-id="{{ $child['id'] }}">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $child['avatar'] }}" alt="{{ $child['name'] }}" class="person-photo">
                                                    <div class="person-info">
                                                        <div class="person-name">{{ $child['name'] }}</div>
                                                        <div class="person-meta">
                                                            @if($child['nursing_mother'])
                                                                أمه: {{ $child['nursing_mother']['name'] }}
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('people.profile.show', $child['id']) }}"
                                                           onclick="event.stopPropagation();"
                                                           class="profile-link"
                                                           title="عرض الملف الشخصي">
                                                            <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="card-hint">
                                                    <i class="fas fa-info-circle"></i> اضغط على البطاقة لعرض التفاصيل
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-baby"></i>
                                        <p>لا توجد أطفال مرتضعين مسجلين</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-7 desktop-family-section">
                            <div class="details-section">
                                <div id="childDetails">
                                    <div class="empty-state">
                                        <i class="fas fa-info-circle"></i>
                                        <p>اختر طفلًا لعرض تفاصيل علاقة الرضاعة</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // بيانات الأمهات والأطفال
        const mothersData = @json($mothersData);
        const childrenData = @json($childrenData ?? []);
        let selectedMotherId = null;
        let selectedChildId = null;

        // التبديل بين الوضعين
        function switchViewMode(mode) {
            const url = new URL(window.location.href);
            url.searchParams.set('view_mode', mode);
            if (url.searchParams.get('search')) {
                url.searchParams.set('search', '{{ $search }}');
            }
            window.location.href = url.toString();
        }

        // عرض تفاصيل الأم (للشاشات الكبيرة)
        function showMotherDetails(motherId, event) {
            // منع event propagation إذا كان هناك event
            if (event) {
                // التحقق من أن النقر ليس على بطاقة طفل أو رابط أو قسم الأبناء
                const target = event.target;
                if (target.closest('.detail-card') ||
                    target.closest('.profile-link') ||
                    target.closest('.father-info') ||
                    target.closest('.mobile-children-section') ||
                    target.closest('.mobile-notes')) {
                    return; // لا نفعل شيء إذا كان النقر على بطاقة طفل أو رابط
                }
            }

            // إزالة التحديد من جميع الأمهات
            document.querySelectorAll('.person-card').forEach(card => {
                card.classList.remove('active');
            });

            // تحديد الأم المختارة
            const selectedCard = document.querySelector(`[data-mother-id="${motherId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('active');
            }

            selectedMotherId = motherId;

            // العثور على بيانات الأم
            const mother = mothersData.find(m => m.id === motherId);
            if (!mother) return;

            // التحقق من حجم الشاشة
            const isMobile = window.innerWidth <= 768;

            if (isMobile) {
                // عرض الأبناء على الهواتف
                const mobileChildrenSection = document.getElementById(`mobileChildren_${motherId}`);
                const mobileChildrenGrid = document.getElementById(`mobileChildrenGrid_${motherId}`);

                if (mother.children.length > 0) {
                    mobileChildrenGrid.innerHTML = mother.children.map(child => {
                        let fatherHtml = '';
                        if (child.breastfeeding_father) {
                            fatherHtml = `
                                <div class="father-info" onclick="event.stopPropagation();">
                                    <div class="father-info-label">
                                        <i class="fas fa-male"></i> الأب من الرضاعة:
                                    </div>
                                    <div class="father-name">
                                        ${child.breastfeeding_father.name}
                                        <a href="/people/profile/${child.breastfeeding_father.id}"
                                           onclick="event.stopPropagation();"
                                           class="profile-link"
                                           style="display: inline-block; margin-right: 8px;"
                                           title="عرض الملف الشخصي">
                                            <i class="fas fa-user-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            `;
                        }

                        return `
                            <div class="detail-card" onclick="showChildNotesMobile(event, ${child.relationship_id}, '${child.name.replace(/'/g, "\\'")}')"
                                 data-relationship-id="${child.relationship_id}">
                                <div class="detail-header">
                                    <img src="${child.avatar}" alt="${child.name}" class="detail-photo">
                                    <div class="detail-info">
                                        <div class="detail-name">${child.name}</div>
                                        <div class="detail-dates">
                                            ${child.start_date ? `من: ${child.start_date}` : ''}
                                            ${child.end_date ? `إلى: ${child.end_date}` : ''}
                                            ${child.duration_months ? ` (${child.duration_months} شهر)` : ''}
                                        </div>
                                        <a href="/people/profile/${child.id}"
                                           onclick="event.stopPropagation();"
                                           class="profile-link"
                                           title="عرض الملف الشخصي">
                                            <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                        </a>
                                    </div>
                                </div>
                                ${fatherHtml}
                                <span class="status-badge ${child.is_active ? 'status-active' : 'status-completed'}">
                                    ${child.is_active ? 'نشط' : 'مكتمل'}
                                </span>
                                <div class="card-hint">
                                    <i class="fas fa-info-circle"></i> اضغط على البطاقة لعرض الملاحظات
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    mobileChildrenGrid.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-baby"></i>
                            <p>لا يوجد أطفال مسجلين لهذه الأم</p>
                        </div>
                    `;
                }

                // إظهار/إخفاء قسم الأبناء على الهواتف
                if (mobileChildrenSection) {
                    const isCurrentlyVisible = mobileChildrenSection.style.display === 'block';
                    // إغلاق جميع أقسام الأبناء الأخرى وإزالة الملاحظات
                    document.querySelectorAll('.mobile-children-section').forEach(section => {
                        if (section !== mobileChildrenSection) {
                            section.style.display = 'none';
                        }
                    });
                    // إزالة جميع الملاحظات المفتوحة
                    document.querySelectorAll('.mobile-notes').forEach(notes => {
                        notes.remove();
                    });
                    document.querySelectorAll('.detail-card').forEach(c => {
                        c.classList.remove('active');
                    });
                    // تبديل حالة القسم الحالي
                    mobileChildrenSection.style.display = isCurrentlyVisible ? 'none' : 'block';
                }
            } else {
                // عرض على الشاشات الكبيرة
                const detailsSection = document.getElementById('motherDetails');

                let childrenHtml = '';
                if (mother.children.length > 0) {
                    childrenHtml = mother.children.map(child => {
                        let fatherHtml = '';
                        if (child.breastfeeding_father) {
                            fatherHtml = `
                                <div class="father-info" onclick="event.stopPropagation();">
                                    <div class="father-info-label">
                                        <i class="fas fa-male"></i> الأب من الرضاعة:
                                    </div>
                                    <div class="father-name">
                                        ${child.breastfeeding_father.name}
                                        <a href="/people/profile/${child.breastfeeding_father.id}"
                                           onclick="event.stopPropagation();"
                                           class="profile-link"
                                           style="display: inline-block; margin-right: 8px;"
                                           title="عرض الملف الشخصي">
                                            <i class="fas fa-user-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            `;
                        }

                        return `
                            <div class="detail-card" onclick="showChildNotes(${child.relationship_id})"
                                 data-relationship-id="${child.relationship_id}">
                                <div class="detail-header">
                                    <img src="${child.avatar}" alt="${child.name}" class="detail-photo">
                                    <div class="detail-info">
                                        <div class="detail-name">${child.name}</div>
                                        <div class="detail-dates">
                                            ${child.start_date ? `من: ${child.start_date}` : ''}
                                            ${child.end_date ? `إلى: ${child.end_date}` : ''}
                                            ${child.duration_months ? ` (${child.duration_months} شهر)` : ''}
                                        </div>
                                        <a href="/people/profile/${child.id}"
                                           onclick="event.stopPropagation();"
                                           class="profile-link"
                                           title="عرض الملف الشخصي">
                                            <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                        </a>
                                    </div>
                                </div>
                                ${fatherHtml}
                                <span class="status-badge ${child.is_active ? 'status-active' : 'status-completed'}">
                                    ${child.is_active ? 'نشط' : 'مكتمل'}
                                </span>
                                <div class="card-hint">
                                    <i class="fas fa-info-circle"></i> اضغط على البطاقة لعرض الملاحظات
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    childrenHtml = `
                        <div class="empty-state">
                            <i class="fas fa-baby"></i>
                            <p>لا يوجد أطفال مسجلين لهذه الأم</p>
                        </div>
                    `;
                }

                detailsSection.innerHTML = `
                    <div class="details-header">
                        <div class="d-flex align-items-center">
                            <img src="${mother.avatar}" alt="${mother.name}" class="detail-photo" style="width: 70px; height: 70px;">
                            <div class="ms-3">
                                <div class="details-title">${mother.name}</div>
                                <p class="mb-0"><i class="fas fa-baby"></i> أم مرضعة لـ ${mother.children.length} طفل</p>
                                <a href="/people/profile/${mother.id}"
                                   onclick="event.stopPropagation();"
                                   class="profile-link"
                                   style="margin-top: 8px;"
                                   title="عرض الملف الشخصي">
                                    <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="details-grid">
                        ${childrenHtml}
                    </div>
                    <div class="notes-section" id="notesSection">
                        <div class="empty-state">
                            <i class="fas fa-info-circle"></i>
                            <p>اختر طفلًا لعرض ملاحظاته</p>
                        </div>
                    </div>
                `;
            }
        }

        // عرض تفاصيل الطفل (لوضع الأطفال)
        function showChildDetails(childId) {
            // إزالة التحديد من جميع الأطفال
            document.querySelectorAll('.person-card').forEach(card => {
                card.classList.remove('active');
            });

            // تحديد الطفل المختار
            const selectedCard = document.querySelector(`[data-child-id="${childId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('active');
            }

            selectedChildId = childId;

            // العثور على بيانات الطفل
            const child = childrenData.find(c => c.id === childId);
            if (!child) return;

            // التحقق من حجم الشاشة
            const isMobile = window.innerWidth <= 768;

            if (!isMobile) {
                // عرض على الشاشات الكبيرة
                const detailsSection = document.getElementById('childDetails');

                let motherHtml = '';
                if (child.nursing_mother) {
                    motherHtml = `
                        <div class="detail-card">
                            <div class="detail-header">
                                <img src="${child.nursing_mother.avatar}" alt="${child.nursing_mother.name}" class="detail-photo">
                                <div class="detail-info">
                                    <div class="father-info-label">
                                        <i class="fas fa-female"></i> الأم المرضعة:
                                    </div>
                                    <div class="detail-name">${child.nursing_mother.name}</div>
                                    <a href="/people/profile/${child.nursing_mother.id}"
                                       onclick="event.stopPropagation();"
                                       class="profile-link"
                                       title="عرض الملف الشخصي">
                                        <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                }

                let fatherHtml = '';
                if (child.breastfeeding_father) {
                    fatherHtml = `
                        <div class="detail-card">
                            <div class="detail-header">
                                <img src="${child.breastfeeding_father.avatar}" alt="${child.breastfeeding_father.name}" class="detail-photo">
                                <div class="detail-info">
                                    <div class="father-info-label">
                                        <i class="fas fa-male"></i> الأب من الرضاعة:
                                    </div>
                                    <div class="detail-name">${child.breastfeeding_father.name}</div>
                                    <a href="/people/profile/${child.breastfeeding_father.id}"
                                       onclick="event.stopPropagation();"
                                       class="profile-link"
                                       title="عرض الملف الشخصي">
                                        <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                }

                detailsSection.innerHTML = `
                    <div class="details-header">
                        <div class="d-flex align-items-center">
                            <img src="${child.avatar}" alt="${child.name}" class="detail-photo" style="width: 70px; height: 70px;">
                            <div class="ms-3">
                                <div class="details-title">${child.name}</div>
                                <p class="mb-0">
                                    ${child.start_date ? `من: ${child.start_date}` : ''}
                                    ${child.end_date ? `إلى: ${child.end_date}` : ''}
                                    ${child.duration_months ? ` (${child.duration_months} شهر)` : ''}
                                </p>
                                <span class="status-badge ${child.is_active ? 'status-active' : 'status-completed'}" style="position: relative; top: 0; right: 0; margin-top: 10px;">
                                    ${child.is_active ? 'نشط' : 'مكتمل'}
                                </span>
                                <a href="/people/profile/${child.id}"
                                   onclick="event.stopPropagation();"
                                   class="profile-link"
                                   style="margin-top: 8px;"
                                   title="عرض الملف الشخصي">
                                    <i class="fas fa-user-circle"></i> عرض الملف الشخصي
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="details-grid">
                        ${motherHtml}
                        ${fatherHtml}
                    </div>
                    <div class="notes-section show" id="notesSection">
                        <div class="notes-title">
                            <i class="fas fa-sticky-note"></i> ملاحظات
                        </div>
                        <div class="notes-content">
                            ${child.notes || 'لا توجد ملاحظات مسجلة'}
                        </div>
                    </div>
                `;
            }
        }

        // عرض ملاحظات الطفل على الهواتف
        function showChildNotesMobile(event, relationshipId, childName) {
            // منع event propagation لمنع إغلاق قسم الأبناء
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }

            // العثور على بيانات الطفل
            let selectedChild = null;
            mothersData.forEach(mother => {
                const child = mother.children.find(c => c.relationship_id === relationshipId);
                if (child) {
                    selectedChild = child;
                }
            });

            if (!selectedChild) return;

            // إنشاء نافذة منبثقة أو عرض الملاحظات
            const notesHtml = selectedChild.notes ? `
                <div class="notes-content" style="margin-top: 15px;">
                    ${selectedChild.notes}
                </div>
            ` : `
                <div class="empty-state" style="margin-top: 15px;">
                    <i class="fas fa-sticky-note"></i>
                    <p>لا توجد ملاحظات مسجلة</p>
                </div>
            `;

            // إضافة الملاحظات للبطاقة
            const card = document.querySelector(`[data-relationship-id="${relationshipId}"]`);
            if (card) {
                const existingNotes = card.querySelector('.mobile-notes');
                if (existingNotes) {
                    existingNotes.remove();
                    card.classList.remove('active');
                } else {
                    // إزالة الملاحظات من البطاقات الأخرى
                    document.querySelectorAll('.mobile-notes').forEach(notes => {
                        notes.remove();
                    });
                    document.querySelectorAll('.detail-card').forEach(c => {
                        c.classList.remove('active');
                    });

                    const notesDiv = document.createElement('div');
                    notesDiv.className = 'mobile-notes';
                    notesDiv.innerHTML = `
                        <div class="notes-title" style="font-size: 0.9rem; margin-top: 15px;">
                            <i class="fas fa-sticky-note"></i> ملاحظات عن ${childName}
                        </div>
                        ${notesHtml}
                    `;
                    card.appendChild(notesDiv);
                    card.classList.add('active');

                    // تمرير سلس للملاحظات
                    setTimeout(() => {
                        notesDiv.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 100);
                }
            }
        }

        // عرض ملاحظات الطفل
        function showChildNotes(relationshipId) {
            // إزالة التحديد من جميع البطاقات
            document.querySelectorAll('.detail-card').forEach(card => {
                card.classList.remove('active');
            });

            // تحديد البطاقة المختارة
            const selectedCard = document.querySelector(`[data-relationship-id="${relationshipId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('active');
            }

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
            if (notesSection) {
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
        }

        // تهيئة الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // تحسين البحث
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');

            if (searchInput) {
                // إضافة تأثير focus
                searchInput.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                    this.parentElement.style.transition = 'transform 0.3s ease';
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });

                // البحث عند الضغط على Enter
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });

                // إضافة زر مسح البحث
                if (searchInput.value) {
                    const clearBtn = document.createElement('button');
                    clearBtn.type = 'button';
                    clearBtn.className = 'btn btn-sm';
                    clearBtn.style.cssText = 'position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #999; padding: 5px;';
                    clearBtn.innerHTML = '<i class="fas fa-times"></i>';
                    clearBtn.onclick = function() {
                        searchInput.value = '';
                        searchInput.focus();
                    };
                    searchInput.parentElement.style.position = 'relative';
                    searchInput.parentElement.appendChild(clearBtn);
                }
            }

            console.log('Breastfeeding page initialized');
        });

        // إعادة تحميل البيانات عند تغيير حجم الشاشة
        window.addEventListener('resize', function() {
            if (selectedMotherId && window.innerWidth > 768) {
                showMotherDetails(selectedMotherId);
            }
            if (selectedChildId && window.innerWidth > 768) {
                showChildDetails(selectedChildId);
            }
        });
    </script>
</body>

</html>

