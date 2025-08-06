<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل عائلة السريع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Cairo:wght@200;300;400;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/new-page.css') }}">
</head>

<body class="animated-bg">
    <div class="particles" id="particles"></div>

    @include('components.web.header')

    <div id="viewToggle" style="display: none;"></div>


    <main class="pt-20 min-h-screen">
        <div id="traditionalView" class="tree-traditional">
            <div class="text-center mb-8">
                <h1 class="text-5xl md:text-6xl font-black text-white mb-4 tracking-wider">عائلة السريع</h1>
                {{-- <p class="text-xl text-white opacity-80">تواصل العائلة التفاعلية</p> --}}
                <div
                    class="w-32 h-1 bg-gradient-to-r from-transparent via-green-300 to-transparent mx-auto mt-4 opacity-50">
                </div>
            </div>
            <div id="familyTreeContainer" class="w-full"></div>
        </div>

        <div id="verticalView" class="tree-vertical hidden">
            <div class="tree-sidebar">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">تواصل العائلة</h2>
                    <div class="w-16 h-0.5 bg-green-300 opacity-50 mx-auto"></div>
                </div>
                <div id="verticalTreeContainer" class="space-y-2"></div>
            </div>
            <div class="flex-1 p-4 sm:p-8 overflow-y-auto">
                <div id="verticalDetails" class="glass rounded-2xl p-8 h-full">
                    <div class="text-center text-white opacity-50 h-full flex items-center justify-center">
                        <div>
                            <i class="fas fa-hand-pointer text-4xl mb-4"></i>
                            <p>اختر عضواً من القائمة لعرض تفاصيله</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="personModal" class="fixed inset-0 modal-backdrop z-50 hidden items-center justify-center p-4">
        <div class="modal-content max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-green-300 border-opacity-20">
                <h3 class="text-2xl font-bold text-white">تفاصيل العضو</h3>
                <button id="closeModal"
                    class="text-white hover:text-red-400 text-3xl transition-colors">&times;</button>
            </div>
            <div id="modalContent" class="p-6"></div>
        </div>
    </div>


    <script src="{{ asset('assets/js/new-page.js') }}"></script>
</body>

</html>