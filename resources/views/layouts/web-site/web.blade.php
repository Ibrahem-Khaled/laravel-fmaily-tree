<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'تواصل عائلة السريِّع')</title>
    <meta name="description" content="@yield(
        'description',
        'عائلة السّرّيع
    أسرة عريقة تجمع بين أصالة الجذور وسمو القيم. نعتز بإرثنا التاريخي الممتد، ونفخر بمسيرة أبنائنا في خدمة الدين والوطن. هدفنا ترسيخ أواصر الترابط بين الأجيال، وتقديم نموذج يُحتذى به في التلاحم والعطاء المجتمعي.'
    )">

    {{--icons--}}
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">

    {{-- Stylesheets --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Alexandria:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body {
            font-family: 'Tajawal', 'Alexandria', sans-serif;
        }

        /* Gradient Backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, #145147 0%, #37a05c 50%, #2d8a4e 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Dynamic Rich Text Styles */
        .dynamic-rich-text h1,
        .dynamic-rich-text h2,
        .dynamic-rich-text h3,
        .dynamic-rich-text h4,
        .dynamic-rich-text h5,
        .dynamic-rich-text h6 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.5em;
            margin-top: 1em;
        }

        .dynamic-rich-text h1 {
            font-size: 1.8rem;
        }

        .dynamic-rich-text h2 {
            font-size: 1.5rem;
        }

        .dynamic-rich-text h3 {
            font-size: 1.25rem;
        }

        .dynamic-rich-text p {
            margin-bottom: 0.75em;
            line-height: 1.8;
        }

        .dynamic-rich-text ul,
        .dynamic-rich-text ol {
            padding-right: 1.5em;
            margin-bottom: 0.75em;
        }

        .dynamic-rich-text li {
            margin-bottom: 0.25em;
        }

        .dynamic-rich-text a {
            color: #4e73df;
            text-decoration: underline;
        }

        .dynamic-rich-text a:hover {
            color: #2e59d9;
        }

        .dynamic-rich-text img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1em 0;
        }

        .dynamic-rich-text blockquote {
            border-right: 4px solid #4e73df;
            padding: 12px 20px;
            margin: 1em 0;
            background: #f8f9fc;
            border-radius: 0 8px 8px 0;
            font-style: italic;
        }

        .dynamic-rich-text table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
        }

        .dynamic-rich-text table th {
            background: linear-gradient(135deg, #f0f4ff, #e8eeff);
            font-weight: 700;
            padding: 10px 14px;
            border: 1px solid #d1d9e6;
            text-align: right;
        }

        .dynamic-rich-text table td {
            padding: 10px 14px;
            border: 1px solid #d1d9e6;
            text-align: right;
        }

        .dynamic-rich-text table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .dynamic-rich-text pre {
            background: #f8f9fc;
            padding: 12px;
            border-radius: 8px;
            overflow-x: auto;
            direction: ltr;
            text-align: left;
        }

        .dynamic-rich-text code {
            background: #f0f2f8;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-scale {
            animation: fadeInScale 0.6s ease-out forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Card Hover Effects */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Section Title */
        .section-title {
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: 0;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, #37a05c 0%, transparent 100%);
            border-radius: 2px;
        }

        /* Text Gradient */
        .text-gradient {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Degree Cards */
        .degree-card {
            position: relative;
            overflow: hidden;
        }

        .degree-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            transition: all 0.6s ease;
            opacity: 0;
        }

        .degree-card:hover::before {
            opacity: 1;
            transform: scale(1.2);
        }

        /* Responsive */
        @media (max-width: 640px) {
            section {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }

            .container {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .section-title {
                font-size: 1.1rem !important;
            }

            .section-title::after {
                bottom: -4px;
                height: 3px;
            }

            .card-hover:hover {
                transform: translateY(-4px) scale(1.01);
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/50">
    @include('layouts.web-site.header')
    @yield('content')

    @stack('scripts')
</body>

</html>
