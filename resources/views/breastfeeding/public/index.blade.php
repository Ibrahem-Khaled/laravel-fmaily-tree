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
            --border-color: #dee2e6;
            --accent-color: #37a05c;
            --light-accent: #DCF2DD;
        }

        body {
            background: var(--light-gray);
            font-family: 'Alexandria', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* تحسينات للهواتف */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        .btn, .mother-card, .child-card {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
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

        .mothers-section {
            background: linear-gradient(135deg, #fff 0%, var(--light-gray) 100%);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border: 2px solid var(--border-color);
            height: fit-content;
        }

        .mothers-list {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        .mothers-list::-webkit-scrollbar {
            width: 6px;
        }

        .mothers-list::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 10px;
        }

        .mothers-list::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 10px;
        }

        .mother-card {
            background: linear-gradient(135deg, #fff 0%, var(--light-gray) 100%);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            touch-action: manipulation;
        }

        .mother-card::before {
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

        .mother-card:hover {
            border-color: var(--accent-color);
            transform: translateX(-5px);
            box-shadow: 0 10px 25px rgba(55, 160, 92, 0.2);
        }

        .mother-card:hover::before {
            transform: scaleX(1);
        }

        .mother-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, var(--light-accent) 0%, #fff 100%);
            box-shadow: 0 10px 25px rgba(55, 160, 92, 0.25);
            transform: translateX(-3px);
        }

        .mother-card.active::before {
            transform: scaleX(1);
        }

        .mother-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            margin-left: 15px;
        }

        .mother-card:hover .mother-photo {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(55, 160, 92, 0.3);
        }

        .mother-info {
            flex: 1;
        }

        .mother-name {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.1rem;
            margin-bottom: 5px;
            transition: color 0.3s ease;
        }

        .mother-card:hover .mother-name {
            color: var(--accent-color);
        }

        .mother-children-count {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }

        .children-count {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, var(--accent-color), var(--dark-green));
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(55, 160, 92, 0.3);
            transition: all 0.3s ease;
        }

        .mother-card:hover .children-count {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(55, 160, 92, 0.4);
        }

        .family-details-section {
            background: linear-gradient(135deg, #fff 0%, var(--light-gray) 100%);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border: 2px solid var(--border-color);
            min-height: 70vh;
        }

        .desktop-family-section {
            display: block;
        }

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


        .mobile-children-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .mobile-child-card {
            background: linear-gradient(135deg, var(--light-gray) 0%, #fff 100%);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
            overflow: hidden;
        }

        .mobile-child-card:hover {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(55, 160, 92, 0.15);
        }

        .mobile-child-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            box-shadow: 0 8px 20px rgba(55, 160, 92, 0.2);
        }

        /* الأنماط الجديدة للهاتف */
        .mobile-child-main-info {
            padding: 15px;
        }

        .mobile-child-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .mobile-child-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            margin-left: 15px;
        }

        .mobile-child-info {
            flex: 1;
        }

        .mobile-child-name {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .mobile-child-dates {
            font-size: 0.8rem;
            color: #666;
            line-height: 1.4;
        }

        .mobile-status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mobile-notes-toggle {
            background: var(--light-accent);
            color: var(--dark-green);
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--accent-color);
            user-select: none;
        }

        .mobile-notes-toggle:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-1px);
        }

        .mobile-notes-toggle i {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .mobile-child-card.active .mobile-notes-toggle i {
            transform: rotate(180deg);
        }

        .mobile-notes-container {
            background: #fff;
            border-top: 2px solid var(--accent-color);
            padding: 20px;
            animation: slideDown 0.3s ease;
        }

        .mobile-notes-title {
            color: var(--dark-green);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--light-green);
        }

        .mobile-notes-title i {
            margin-left: 8px;
        }

        .mobile-notes-content {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 15px;
            font-size: 0.85rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .mobile-child-dates-full {
            text-align: center;
            padding: 10px;
            background: var(--light-accent);
            border-radius: 8px;
        }

        .mobile-child-dates-full small {
            color: var(--dark-green);
            font-weight: 500;
        }

        /* الأنماط القديمة المحذوفة - يمكن حذفها */

        .family-group {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            display: none;
        }

        .family-group.show {
            display: block;
            animation: slideInRight 0.5s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .family-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            border-radius: 15px;
            border: 2px solid var(--accent-color);
        }

        .family-mother-info {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .mother-photo-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent-color);
            box-shadow: 0 6px 20px rgba(55, 160, 92, 0.3);
            margin-left: 20px;
        }

        .family-mother-details h4 {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.3rem;
        }

        .family-mother-details p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        .children-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .child-card {
            background: linear-gradient(135deg, var(--light-gray) 0%, #fff 100%);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            touch-action: manipulation;
        }

        .child-card::before {
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

        .child-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(55, 160, 92, 0.2);
        }

        .child-card:hover::before {
            transform: scaleX(1);
        }

        .child-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            box-shadow: 0 12px 30px rgba(55, 160, 92, 0.25);
        }

        .child-card.active::before {
            transform: scaleX(1);
        }

        .child-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .child-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-left: 15px;
        }

        .child-info {
            flex: 1;
        }

        .child-name {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .child-dates {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.4;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            box-shadow: 0 2px 8px rgba(21, 87, 36, 0.2);
        }

        .status-completed {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            box-shadow: 0 2px 8px rgba(114, 28, 36, 0.2);
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
            background: linear-gradient(135deg, #fff 0%, var(--light-gray) 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border: 2px solid var(--border-color);
        }

        .search-section .form-control {
            border-radius: 15px;
            border: 2px solid var(--border-color);
            padding: 12px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-section .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(55, 160, 92, 0.25);
        }

        .search-section .btn {
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            touch-action: manipulation;
        }

        .search-section .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(55, 160, 92, 0.3);
        }

        .stats-section {
            background: linear-gradient(135deg, #fff 0%, var(--light-gray) 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border: 2px solid var(--border-color);
        }

        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            background: linear-gradient(135deg, var(--light-gray) 0%, #fff 100%);
            margin-bottom: 15px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(55, 160, 92, 0.15);
            border-color: var(--accent-color);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--accent-color);
            text-shadow: 0 2px 4px rgba(55, 160, 92, 0.2);
        }

        .stat-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 50px 30px;
            color: #666;
            background: linear-gradient(135deg, var(--light-gray) 0%, #fff 100%);
            border-radius: 15px;
            border: 2px dashed var(--border-color);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--accent-color);
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state p {
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .breastfeeding-section {
                padding-top: 80px;
                padding-left: 5px;
                padding-right: 5px;
            }

            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }

            .section-title h2 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .search-section, .stats-section {
                padding: 15px;
                margin-bottom: 20px;
            }

            .search-section .form-control {
                font-size: 16px; /* منع التكبير على iOS */
                padding: 10px 15px;
            }

            .search-section .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .stat-card {
                padding: 15px;
                margin-bottom: 10px;
            }

            .stat-number {
                font-size: 2rem;
            }

            .stat-label {
                font-size: 0.85rem;
            }

            /* تخطيط الهواتف - عمودي كامل */
            .row {
                margin: 0;
            }

            .col-lg-4, .col-lg-8, .col-md-5, .col-md-7 {
                padding: 0;
                margin-bottom: 20px;
            }

            /* إخفاء قسم العائلة على الهواتف */
            .desktop-family-section {
                display: none !important;
            }

            .mothers-section {
                margin-bottom: 20px;
                padding: 15px;
                border-radius: 15px;
            }

            .mothers-section h4 {
                font-size: 1.2rem;
                margin-bottom: 15px;
                text-align: center;
            }

            .mothers-list {
                max-height: none;
                overflow: visible;
                padding-right: 0;
            }

            .mother-card {
                padding: 15px;
                margin-bottom: 10px;
                border-radius: 12px;
            }

            .mother-photo {
                width: 50px;
                height: 50px;
                margin-left: 10px;
            }

            .mother-name {
                font-size: 1rem;
                margin-bottom: 3px;
            }

            .mother-children-count {
                font-size: 0.8rem;
            }

            .children-count {
                width: 22px;
                height: 22px;
                font-size: 0.75rem;
                top: 10px;
                right: 10px;
            }

            .family-details-section {
                min-height: auto;
                padding: 15px;
                border-radius: 15px;
            }

            .family-header {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 12px;
            }

            .mother-photo-large {
                width: 60px;
                height: 60px;
                margin-left: 0;
                margin-bottom: 10px;
            }

            .family-mother-info {
                flex-direction: column;
                text-align: center;
            }

            .family-mother-details h4 {
                font-size: 1.1rem;
                margin-bottom: 5px;
            }

            .family-mother-details p {
                font-size: 0.85rem;
            }

            .children-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-top: 15px;
            }

            .child-card {
                padding: 12px;
                border-radius: 12px;
            }

            .child-header {
                flex-direction: row;
                align-items: center;
                text-align: right;
            }

            .child-photo {
                width: 45px;
                height: 45px;
                margin-left: 10px;
                margin-bottom: 0;
            }

            .child-name {
                font-size: 0.95rem;
                margin-bottom: 3px;
            }

            .child-dates {
                font-size: 0.8rem;
            }

            .status-badge {
                position: absolute;
                top: 8px;
                right: 8px;
                padding: 4px 8px;
                font-size: 0.7rem;
            }

            .notes-section {
                padding: 15px;
                border-radius: 12px;
                margin-top: 20px;
            }

            .notes-title {
                font-size: 1rem;
                margin-bottom: 10px;
            }

            .notes-content {
                padding: 12px;
                font-size: 0.9rem;
                line-height: 1.5;
            }

            .empty-state {
                padding: 30px 20px;
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 15px;
            }

            .empty-state p {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .breastfeeding-section {
                padding-top: 70px;
                padding-left: 0;
                padding-right: 0;
            }

            .container-fluid {
                padding-left: 5px;
                padding-right: 5px;
            }

            .section-title h2 {
                font-size: 1.3rem;
            }

            .search-section, .stats-section, .mothers-section, .family-details-section {
                padding: 12px;
                margin-bottom: 15px;
            }

            .mother-card {
                padding: 12px;
            }

            .mother-photo {
                width: 45px;
                height: 45px;
                margin-left: 8px;
            }

            .mother-name {
                font-size: 0.95rem;
            }

            .mother-children-count {
                font-size: 0.75rem;
            }

            .children-count {
                width: 20px;
                height: 20px;
                font-size: 0.7rem;
            }

            .family-header {
                padding: 12px;
            }

            .mother-photo-large {
                width: 50px;
                height: 50px;
            }

            .family-mother-details h4 {
                font-size: 1rem;
            }

            .child-card {
                padding: 10px;
            }

            .child-photo {
                width: 40px;
                height: 40px;
                margin-left: 8px;
            }

            .child-name {
                font-size: 0.9rem;
            }

            .child-dates {
                font-size: 0.75rem;
            }

            .status-badge {
                padding: 3px 6px;
                font-size: 0.65rem;
            }

            .notes-section {
                padding: 12px;
            }

            .notes-content {
                padding: 10px;
                font-size: 0.85rem;
            }

            .empty-state {
                padding: 25px 15px;
            }

            .empty-state i {
                font-size: 2.5rem;
            }

            .empty-state p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .breastfeeding-section {
                padding-top: 60px;
            }

            .section-title h2 {
                font-size: 1.2rem;
            }

            .search-section, .stats-section, .mothers-section, .family-details-section {
                padding: 10px;
            }

            .mother-card {
                padding: 10px;
            }

            .mother-photo {
                width: 40px;
                height: 40px;
                margin-left: 6px;
            }

            .mother-name {
                font-size: 0.9rem;
            }

            .mother-children-count {
                font-size: 0.7rem;
            }

            .children-count {
                width: 18px;
                height: 18px;
                font-size: 0.65rem;
            }

            .family-header {
                padding: 10px;
            }

            .mother-photo-large {
                width: 45px;
                height: 45px;
            }

            .family-mother-details h4 {
                font-size: 0.95rem;
            }

            .child-card {
                padding: 8px;
            }

            .child-photo {
                width: 35px;
                height: 35px;
                margin-left: 6px;
            }

            .child-name {
                font-size: 0.85rem;
            }

            .child-dates {
                font-size: 0.7rem;
            }

            .status-badge {
                padding: 2px 5px;
                font-size: 0.6rem;
            }

            .notes-section {
                padding: 10px;
            }

            .notes-content {
                padding: 8px;
                font-size: 0.8rem;
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

                {{-- التخطيط الرئيسي --}}
                <div class="row">
                    {{-- قائمة الأمهات (اليسار) --}}
                    <div class="col-lg-4 col-md-5">
                        <div class="mothers-section">
                            <h4 class="mb-3"><i class="fas fa-female text-success"></i> الأمهات المرضعات</h4>
                            @if($mothersData->isNotEmpty())
                                <div class="mothers-list">
                                    @foreach($mothersData as $mother)
                                        <div class="mother-card" onclick="showChildren({{ $mother['id'] }})"
                                             data-mother-id="{{ $mother['id'] }}">
                                            <div class="children-count">{{ count($mother['children']) }}</div>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $mother['avatar'] }}" alt="{{ $mother['name'] }}" class="mother-photo">
                                                <div class="mother-info">
                                                    <div class="mother-name">{{ $mother['name'] }}</div>
                                                    <div class="mother-children-count">{{ count($mother['children']) }} طفل</div>
                                                </div>
                                            </div>

                                            {{-- عرض الأبناء على الهواتف --}}
                                            <div class="mobile-children-section" id="mobileChildren_{{ $mother['id'] }}" style="display: none;">
                                                <div class="mobile-children-grid" id="mobileChildrenGrid_{{ $mother['id'] }}">
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

                    {{-- تفاصيل العائلة (اليمين) - للشاشات الكبيرة فقط --}}
                    <div class="col-lg-8 col-md-7 desktop-family-section">
                        <div class="family-details-section">
                            {{-- عرض العائلة المختارة --}}
                            <div class="family-group" id="familyGroup">
                                <div class="family-header" id="familyHeader">
                                    <!-- سيتم ملؤها بواسطة JavaScript -->
                                </div>
                                <div class="children-grid" id="childrenGrid">
                                    <!-- سيتم ملؤها بواسطة JavaScript -->
                                </div>
                            </div>

                            {{-- الملاحظات --}}
                            <div class="notes-section" id="notesSection">
                                <div class="empty-state">
                                    <i class="fas fa-info-circle"></i>
                                    <p>اختر أمًا لعرض تفاصيل عائلتها</p>
                                </div>
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
                if (card) {
                    card.classList.remove('active');
                }
            });

            // إخفاء جميع أقسام الأبناء على الهواتف
            document.querySelectorAll('.mobile-children-section').forEach(section => {
                if (section) {
                    section.style.display = 'none';
                }
            });

            // تحديد الأم المختارة
            const selectedMotherCard = document.querySelector(`[data-mother-id="${motherId}"]`);
            if (selectedMotherCard) {
                selectedMotherCard.classList.add('active');
            }

            selectedMotherId = motherId;

            // العثور على بيانات الأم
            const mother = mothersData.find(m => m.id === motherId);
            if (!mother) return;

            // التحقق من حجم الشاشة
            const isMobile = window.innerWidth <= 768;

            if (isMobile) {
                // عرض الأبناء على الهواتف - إعادة هيكلة كاملة
                const mobileChildrenSection = document.getElementById(`mobileChildren_${motherId}`);
                const mobileChildrenGrid = document.getElementById(`mobileChildrenGrid_${motherId}`);

                if (mother.children.length > 0) {
                    mobileChildrenGrid.innerHTML = mother.children.map(child => `
                        <div class="mobile-child-card mobile-child-item"
                             data-child-id="${child.relationship_id}"
                             data-mother-id="${motherId}"
                             data-child-name="${child.name}">
                            <div class="mobile-child-main-info">
                                <div class="mobile-child-header">
                                    <img src="${child.avatar}" alt="${child.name}" class="mobile-child-photo">
                                    <div class="mobile-child-info">
                                        <div class="mobile-child-name">${child.name}</div>
                                        <div class="mobile-child-dates">
                                            ${child.start_date ? `من: ${child.start_date}` : ''}
                                            ${child.end_date ? `إلى: ${child.end_date}` : ''}
                                            ${child.duration_months ? ` (${child.duration_months} شهر)` : ''}
                                        </div>
                                    </div>
                                    <span class="mobile-status-badge ${child.is_active ? 'status-active' : 'status-completed'}">
                                        ${child.is_active ? 'مستمرة' : 'مكتملة'}
                                    </span>
                                </div>
                                <div class="mobile-notes-toggle">
                                    <i class="fas fa-chevron-down"></i> اضغط لعرض الملاحظات
                                </div>
                            </div>
                            <div class="mobile-notes-container" style="display: none;">
                                <div class="mobile-notes-title">
                                    <i class="fas fa-sticky-note"></i> ملاحظات عن ${child.name}
                                </div>
                                <div class="mobile-notes-content">
                                    ${child.notes || 'لا توجد ملاحظات مسجلة'}
                                </div>
                                <div class="mobile-child-dates-full">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i>
                                        ${child.start_date ? `تاريخ البداية: ${child.start_date}` : ''}
                                        ${child.end_date ? ` | تاريخ النهاية: ${child.end_date}` : ''}
                                    </small>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    // إضافة event listeners مباشرة للأطفال الجدد
                    attachMobileEventListeners(mobileChildrenGrid);

                    console.log('Mobile children grid populated for mother:', motherId);
                    console.log('Children data:', mother.children);
                } else {
                    mobileChildrenGrid.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-baby"></i>
                            <p>لا يوجد أطفال مسجلين لهذه الأم</p>
                        </div>
                    `;
                }

                // إظهار قسم الأبناء على الهواتف
                mobileChildrenSection.style.display = 'block';
            } else {
                // عرض العائلة على الشاشات الكبيرة
                const familyGroup = document.getElementById('familyGroup');
                const familyHeader = document.getElementById('familyHeader');
                const childrenGrid = document.getElementById('childrenGrid');

                // ملء رأس العائلة
                familyHeader.innerHTML = `
                    <div class="family-mother-info">
                        <img src="${mother.avatar}" alt="${mother.name}" class="mother-photo-large">
                        <div class="family-mother-details">
                            <h4>${mother.name}</h4>
                            <p><i class="fas fa-baby"></i> أم مرضعة لـ ${mother.children.length} طفل</p>
                        </div>
                    </div>
                `;

                // ملء شبكة الأطفال
                if (mother.children.length > 0) {
                    childrenGrid.innerHTML = mother.children.map(child => `
                        <div class="child-card" onclick="showNotes(${child.relationship_id})"
                             data-child-id="${child.relationship_id}">
                            <div class="child-header">
                                <img src="${child.avatar}" alt="${child.name}" class="child-photo">
                                <div class="child-info">
                                    <div class="child-name">${child.name}</div>
                                    <div class="child-dates">
                                        ${child.start_date ? `من: ${child.start_date}` : ''}
                                        ${child.end_date ? `إلى: ${child.end_date}` : ''}
                                        ${child.duration_months ? ` (${child.duration_months} شهر)` : ''}
                                    </div>
                                </div>
                            </div>
                            <span class="status-badge ${child.is_active ? 'status-active' : 'status-completed'}">
                                ${child.is_active ? '' : 'مكتملة'}
                            </span>
                        </div>
                    `).join('');
                } else {
                    childrenGrid.innerHTML = `
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="fas fa-baby"></i>
                                <p>لا يوجد أطفال مسجلين لهذه الأم</p>
                            </div>
                        </div>
                    `;
                }

                // إظهار مجموعة العائلة
                if (familyGroup) {
                    familyGroup.classList.add('show');
                }

                // إخفاء الملاحظات
                const notesSection = document.getElementById('notesSection');
                if (notesSection) {
                    notesSection.classList.remove('show');
                    notesSection.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-info-circle"></i>
                            <p>اختر طفلًا لعرض ملاحظاته</p>
                        </div>
                    `;
                }
            }
        }

        function showNotes(relationshipId) {
            // إزالة التحديد من جميع الأطفال
            document.querySelectorAll('.child-card').forEach(card => {
                if (card) {
                    card.classList.remove('active');
                }
            });

            // تحديد الطفل المختار
            const selectedChildCard = document.querySelector(`[data-child-id="${relationshipId}"]`);
            if (selectedChildCard) {
                selectedChildCard.classList.add('active');
            }

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

        // دالة إضافة event listeners للهواتف
        function attachMobileEventListeners(container) {
            const childCards = container.querySelectorAll('.mobile-child-item');

            childCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // الحصول على البيانات
                    const relationshipId = this.getAttribute('data-child-id');
                    const childName = this.getAttribute('data-child-name');

                    console.log('Mobile child clicked:', { relationshipId, childName });

                    // تبديل حالة الملاحظات
                    toggleMobileNotes(this);

                    // إخفاء ملاحظات الآخرين
                    const allChildCards = document.querySelectorAll('.mobile-child-item');
                    allChildCards.forEach(otherCard => {
                        if (otherCard !== this) {
                            hideMobileNotes(otherCard);
                        }
                    });

                });
            });
        }

        // دالة تبديل عرض الملاحظات
        function toggleMobileNotes(childCard) {
            const notesContainer = childCard.querySelector('.mobile-notes-container');
            const toggleButton = childCard.querySelector('.mobile-notes-toggle');
            const chevronIcon = toggleButton.querySelector('i');

            if (!notesContainer || !toggleButton) {
                console.error('Notes container or toggle button not found');
                return;
            }

            // التحقق من الحالة الحالية
            const isVisible = notesContainer.style.display === 'block';

            if (isVisible) {
                // إخفاء الملاحظات
                hideMobileNotes(childCard);
            } else {
                // إظهار الملاحظات
                showMobileNotes(childCard);
            }
        }

        // دالة إخفاء الملاحظات
        function hideMobileNotes(childCard) {
            const notesContainer = childCard.querySelector('.mobile-notes-container');
            const toggleButton = childCard.querySelector('.mobile-notes-toggle');
            const chevronIcon = toggleButton.querySelector('i');

            if (notesContainer) {
                notesContainer.style.display = 'none';
            }

            if (chevronIcon) {
                chevronIcon.className = 'fas fa-chevron-down';
            }

            if (toggleButton) {
                toggleButton.innerHTML = '<i class="fas fa-chevron-down"></i> اضغط لعرض الملاحظات';
            }

            childCard.classList.remove('active');
        }

        // دالة إظهار الملاحظات
        function showMobileNotes(childCard) {
            const notesContainer = childCard.querySelector('.mobile-notes-container');
            const toggleButton = childCard.querySelector('.mobile-notes-toggle');
            const chevronIcon = toggleButton.querySelector('i');

            if (notesContainer) {
                notesContainer.style.display = 'block';

                // تمرير سلس للملاحظات
                setTimeout(() => {
                    notesContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }

            if (chevronIcon) {
                chevronIcon.className = 'fas fa-chevron-up';
            }

            if (toggleButton) {
                toggleButton.innerHTML = '<i class="fas fa-chevron-up"></i> اضغط لإخفاء الملاحظات';
            }

            childCard.classList.add('active');
        }

        // تهيئة الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // إذا كان هناك بحث، اعرض النتائج
            @if($search)
                // يمكن إضافة منطق لعرض النتائج المفلترة
            @endif

            // إضافة event listeners للهواتف - مبسطة
            if (window.innerWidth <= 768) {
                // تحسين الاستجابة للمس على الهواتف
                document.addEventListener('touchstart', function() {}, {passive: true});

                console.log('Mobile initialization complete');
            }
        });

        // إعادة تحميل البيانات عند تغيير حجم الشاشة
        window.addEventListener('resize', function() {
            if (selectedMotherId) {
                showChildren(selectedMotherId);
            }
        });
    </script>
</body>

</html>
