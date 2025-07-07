<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شجرة عائلة السريع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Cairo:wght@200;300;400;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: linear-gradient(135deg, #10B981 0%, #059669 100%);
            --secondary-green: linear-gradient(135deg, #86efac 0%, #34d399 100%);
            --accent-green: linear-gradient(135deg, #a7f3d0 0%, #6ee7b7 100%);
            --dark-bg: #064e3b;
            --glass-bg: rgba(16, 185, 129, 0.1);
            --glass-border: rgba(16, 185, 129, 0.2);
        }

        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            background: var(--dark-bg);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Grayscale filter for deceased individuals */
        .grayscale {
            filter: grayscale(100%);
            opacity: 0.8;
        }

        /* Glassmorphism Effects */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
        }

        /* Animated Background */
        .animated-bg {
            background: linear-gradient(-45deg, #064e3b, #059669, #10b981, #34d399, #047857);
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(167, 243, 208, 0.6);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
                opacity: 1;
            }

            50% {
                transform: translateY(-40px);
                opacity: 0.3;
            }
        }

        /* Modern Cards */
        .person-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .person-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(16, 185, 129, 0.5);
        }

        /* Profile Images */
        .person-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            flex-shrink: 0;
        }

        .person-img::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent, rgba(210, 255, 239, 0.2), transparent 30%);
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Tree Layout Styles */
        .tree-traditional {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            gap: 3rem;
        }

        .tree-level {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 3rem;
            position: relative;
        }

        .children-level {
            position: relative;
            padding-top: 3rem;
            margin-top: 3rem;
        }

        .children-level::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 3rem;
            background-color: var(--glass-border);
        }

        .children-level .tree-level::before {
            content: '';
            position: absolute;
            top: -1.5rem;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--glass-border);
        }

        /* Vertical Tree Layout */
        .tree-vertical {
            display: flex;
            flex-direction: row;
            height: calc(100vh - 5rem);
            /* Adjust for header height */
            overflow: hidden;
        }

        .tree-sidebar {
            width: 400px;
            flex-shrink: 0;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            border-left: 1px solid var(--glass-border);
            overflow-y: auto;
            padding: 2rem 1rem;
        }

        .vertical-node {
            transition: all 0.2s ease-in-out;
        }

        .vertical-node.active,
        .vertical-node:hover {
            background: rgba(16, 185, 129, 0.2);
        }

        .children-vertical-container {
            padding-right: 1.5rem;
            margin-top: 0.5rem;
            border-right: 2px solid var(--glass-border);
        }

        /* Modern Buttons */
        .modern-btn {
            background: var(--primary-green);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .modern-btn:hover::before {
            left: 100%;
        }

        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            cursor: pointer;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            right: 2px;
            width: 26px;
            height: 26px;
            background: var(--primary-green);
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .toggle-switch.active::after {
            right: 32px;
        }

        /* Loading Animation */
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(16, 185, 129, 0.3);
            border-radius: 50%;
            border-top-color: #6ee7b7;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Modal Styles */
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
        }

        .modal-content {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.8), rgba(4, 120, 87, 0.8));
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tree-sidebar {
                width: 100%;
                border-left: none;
                height: 50vh;
            }

            .tree-vertical {
                flex-direction: column;
                height: auto;
            }

            .person-img {
                width: 80px;
                height: 80px;
            }
        }

        /* Hide scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-green);
            border-radius: 10px;
        }
    </style>
</head>

<body class="animated-bg">
    <div class="particles" id="particles"></div>

    <header class="fixed top-0 left-0 right-0 glass-dark z-50">
        <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4 space-x-reverse">
                <div class="text-white text-2xl font-black tracking-wider">شجرة العائلة</div>
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                <span class="text-white text-sm hidden sm:block">هرمي</span>
                <div class="toggle-switch" id="viewToggle"></div>
                <span class="text-white text-sm hidden sm:block">عمودي</span>
            </div>
        </nav>
    </header>

    <main class="pt-20 min-h-screen">
        <div id="traditionalView" class="tree-traditional">
            <div class="text-center mb-8">
                <h1 class="text-5xl md:text-6xl font-black text-white mb-4 tracking-wider">عائلة السريع</h1>
                <p class="text-xl text-white opacity-80">شجرة العائلة التفاعلية</p>
                <div
                    class="w-32 h-1 bg-gradient-to-r from-transparent via-green-300 to-transparent mx-auto mt-4 opacity-50">
                </div>
            </div>
            <div id="familyTreeContainer" class="w-full"></div>
        </div>

        <div id="verticalView" class="tree-vertical hidden">
            <div class="tree-sidebar">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">شجرة العائلة</h2>
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

    <script>
        // --- Globals & DOM Elements ---
        const API_BASE_URL = '/api'; // <-- غيّر هذا الرابط إلى رابط الخادم الفعلي الخاص بك
        const viewToggle = document.getElementById('viewToggle');
        const traditionalView = document.getElementById('traditionalView');
        const verticalView = document.getElementById('verticalView');
        const traditionalTreeContainer = document.getElementById('familyTreeContainer');
        const verticalTreeContainer = document.getElementById('verticalTreeContainer');
        const verticalDetailsContainer = document.getElementById('verticalDetails');
        const modal = document.getElementById('personModal');
        const modalContent = document.getElementById('modalContent');
        const closeModalBtn = document.getElementById('closeModal');
        let familyDataCache = null;

        // --- Initializer ---
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
            loadFamilyTree();

            viewToggle.addEventListener('click', () => {
                viewToggle.classList.toggle('active');
                const isVerticalView = viewToggle.classList.contains('active');
                traditionalView.classList.toggle('hidden', isVerticalView);
                verticalView.classList.toggle('hidden', !isVerticalView);
                if (isVerticalView && !verticalTreeContainer.dataset.loaded) {
                    loadVerticalTree();
                }
            });

            closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });

        // --- Particle Creation ---
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 8}s`;
                particle.style.animationDuration = `${Math.random() * 5 + 5}s`;
                particlesContainer.appendChild(particle);
            }
        }

        // --- API Fetching ---
        // هذه الدالة تقوم بجلب البيانات من الخادم
        // تم إضافة محاكاة للبيانات لأغراض العرض فقط
        async function fetchFromAPI(endpoint) {
            console.log(`Fetching from: ${API_BASE_URL}${endpoint}`);

            // --- بداية منطقة المحاكاة ---
            // في التطبيق الفعلي، ستقوم بحذف هذا الجزء واستخدام fetch مباشرة
            const mockApiSimulator = async () => {
                const mockDatabase = {
                    1: {
                        id: 1,
                        first_name: 'سريع',
                        full_name: 'سريع الجد الأكبر',
                        birth_date: '1900',
                        death_date: '1970',
                        gender: 'male',
                        occupation: 'مؤسس العائلة',
                        location: 'القصيم',
                        biography: 'الجد الأكبر ومؤسس عائلة السريع. كان معروفًا بالحكمة والكرم.',
                        photo_url: 'https://placehold.co/150x150/10B981/FFFFFF?text=س',
                        parent_name: null,
                        children_count: 2,
                        spouses: [{
                            id: 2,
                            name: 'موضي',
                            gender: 'female'
                        }]
                    },
                    2: {
                        id: 2,
                        first_name: 'موضي',
                        full_name: 'موضي',
                        birth_date: '1905',
                        death_date: '1965',
                        gender: 'female',
                        occupation: 'ربة منزل',
                        location: 'القصيم',
                        biography: '',
                        photo_url: 'https://placehold.co/150x150/ec4899/FFFFFF?text=م',
                        parent_name: null,
                        children_count: 2,
                        spouses: [{
                            id: 1,
                            name: 'سريع',
                            gender: 'male'
                        }]
                    },
                    3: {
                        id: 3,
                        first_name: 'عبدالله',
                        full_name: 'عبدالله بن سريع',
                        birth_date: '1940',
                        death_date: null,
                        gender: 'male',
                        occupation: 'مزارع',
                        location: 'الرياض',
                        biography: 'الابن الأكبر لسريع، توسع في أعمال الزراعة.',
                        photo_url: 'https://placehold.co/150x150/10B981/FFFFFF?text=ع',
                        parent_name: 'سريع',
                        children_count: 2,
                        spouses: [{
                            id: 5,
                            name: 'فاطمة',
                            gender: 'female'
                        }]
                    },
                    4: {
                        id: 4,
                        first_name: 'محمد',
                        full_name: 'محمد بن سريع',
                        birth_date: '1945',
                        death_date: '2015',
                        gender: 'male',
                        occupation: 'تاجر',
                        location: 'جدة',
                        biography: 'الابن الثاني لسريع، أسس أعمال التجارة للعائلة.',
                        photo_url: 'https://placehold.co/150x150/3b82f6/FFFFFF?text=م',
                        parent_name: 'سريع',
                        children_count: 1,
                        spouses: [{
                            id: 8,
                            name: 'سارة',
                            gender: 'female'
                        }]
                    },
                    6: {
                        id: 6,
                        first_name: 'سليمان',
                        full_name: 'سليمان بن عبدالله',
                        birth_date: '1965',
                        death_date: null,
                        gender: 'male',
                        children_count: 0,
                        photo_url: null,
                        parent_name: 'عبدالله'
                    },
                    7: {
                        id: 7,
                        first_name: 'نورة',
                        full_name: 'نورة بنت عبدالله',
                        birth_date: '1968',
                        death_date: null,
                        gender: 'female',
                        children_count: 0,
                        photo_url: 'https://placehold.co/100x100/ec4899/FFFFFF?text=ن',
                        parent_name: 'عبدالله'
                    },
                    9: {
                        id: 9,
                        first_name: 'خالد',
                        full_name: 'خالد بن محمد',
                        birth_date: '1970',
                        death_date: null,
                        gender: 'male',
                        children_count: 0,
                        photo_url: 'https://placehold.co/100x100/3b82f6/FFFFFF?text=خ',
                        parent_name: 'محمد'
                    }
                };

                await new Promise(resolve => setTimeout(resolve, 500)); // محاكاة تأخير الشبكة

                if (endpoint === '/family-tree') {
                    return {
                        tree: [mockDatabase[1]]
                    }; // ابدأ بالجد الأكبر فقط
                }

                const personChildrenMatch = endpoint.match(/\/person\/(\d+)\/children/);
                if (personChildrenMatch) {
                    const personId = parseInt(personChildrenMatch[1], 10);
                    const childrenMap = {
                        1: [mockDatabase[3], mockDatabase[4]],
                        3: [mockDatabase[6], mockDatabase[7]],
                        4: [mockDatabase[9]]
                    };
                    return {
                        children: childrenMap[personId] || []
                    };
                }

                const personMatch = endpoint.match(/\/person\/(\d+)/);
                if (personMatch) {
                    const personId = parseInt(personMatch[1], 10);
                    const personData = Object.values(mockDatabase).find(p => p.id === personId);
                    return {
                        person: personData || null
                    };
                }

                throw new Error(`Endpoint not found in mock API: ${endpoint}`);
            };

            try {
                // --- الجزء الفعلي للاتصال بالخادم ---
                // قم بإلغاء التعليق عن السطر التالي وتعديله عند الربط مع خادم حقيقي
                // const response = await fetch(`${API_BASE_URL}${endpoint}`);
                // if (!response.ok) throw new Error(`Network response was not ok for ${endpoint}`);
                // return await response.json();

                // للغرض التجريبي، سنستخدم المحاكي دائما
                return await mockApiSimulator();

            } catch (error) {
                console.error('API Fetch Error:', error);
                // في حالة فشل الـ fetch الحقيقي، نستخدم المحاكاة كبديل للعرض
                console.log('Falling back to mock API simulator.');
                return await mockApiSimulator();
            }
            // --- نهاية منطقة المحاكاة ---
        }

        // --- Helper Functions ---
        const getLoaderHtml = (text = 'جاري التحميل...') =>
            `<div class="text-center py-12"><div class="loading-spinner mx-auto"></div><p class="text-white mt-4 opacity-75">${text}</p></div>`;
        const getErrorStateHtml = (text) =>
            `<div class="text-center py-12 text-red-300"><i class="fas fa-exclamation-triangle text-4xl mb-4"></i><p>${text}</p></div>`;
        const getEmptyStateHtml = (text) =>
            `<div class="text-center py-12 text-white opacity-60"><i class="fas fa-info-circle text-4xl mb-4"></i><p>${text}</p></div>`;
        const createInfoItem = (icon, label, value) => !value ? '' :
            `<div class="glass rounded-xl p-4"><h4 class="text-white font-semibold mb-2"><i class="fas ${icon} ml-2 opacity-70"></i> ${label}</h4><p class="text-white opacity-80">${value}</p></div>`;
        const createDetailRow = (icon, label, value) => !value ? '' :
            `<div class="flex items-center text-white bg-white bg-opacity-5 p-3 rounded-lg"><i class="fas ${icon} w-6 text-center text-xl opacity-70 ml-4"></i><div><p class="text-sm opacity-70">${label}</p><p class="font-semibold">${value}</p></div></div>`;

        // --- Traditional Tree Functions ---
        async function loadFamilyTree() {
            traditionalTreeContainer.innerHTML = getLoaderHtml();
            try {
                const data = await fetchFromAPI('/family-tree');
                familyDataCache = data.tree;
                traditionalTreeContainer.innerHTML = '';
                const rootLevel = document.createElement('div');
                rootLevel.className = 'tree-level';
                if (data.tree && data.tree.length > 0) {
                    data.tree.forEach(person => rootLevel.appendChild(createPersonCard(person)));
                    traditionalTreeContainer.appendChild(rootLevel);
                } else {
                    traditionalTreeContainer.innerHTML = getEmptyStateHtml('لا توجد بيانات متاحة في الشجرة.');
                }
            } catch (error) {
                traditionalTreeContainer.innerHTML = getErrorStateHtml('حدث خطأ أثناء تحميل شجرة العائلة.');
            }
        }

        async function loadChildren(personId, button) {
            const cardWrapper = button.closest('.flex-col');
            let childrenLevel = cardWrapper.querySelector('.children-level');

            if (childrenLevel) {
                childrenLevel.remove();
                button.innerHTML = '<i class="fas fa-plus mr-1"></i> الأبناء';
            } else {
                button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i> تحميل...`;
                try {
                    const data = await fetchFromAPI(`/person/${personId}/children`);
                    childrenLevel = document.createElement('div');
                    childrenLevel.className = 'children-level w-full';
                    const childrenContainer = document.createElement('div');
                    childrenContainer.className = 'tree-level';

                    if (data.children && data.children.length > 0) {
                        data.children.forEach(child => {
                            const childCard = createPersonCard(child);
                            childCard.classList.add('scale-90');
                            childrenContainer.appendChild(childCard);
                        });
                    } else {
                        childrenContainer.innerHTML =
                            `<p class="text-white opacity-70 text-center col-span-full">لا يوجد أبناء مسجلين.</p>`;
                    }
                    childrenLevel.appendChild(childrenContainer);
                    cardWrapper.appendChild(childrenLevel);
                    button.innerHTML = '<i class="fas fa-minus mr-1"></i> إخفاء';
                } catch (error) {
                    button.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> خطأ';
                }
            }
        }

        function createPersonCard(person) {
            const cardWrapper = document.createElement('div');
            cardWrapper.className = 'flex flex-col items-center';
            const card = document.createElement('div');
            card.className = 'person-card p-6 cursor-pointer min-w-[250px] text-center';
            card.dataset.personId = person.id;
            card.innerHTML = `
                <div class="person-img ${person.death_date ? 'grayscale' : ''}">
                    ${person.photo_url ?
                        `<img src="${person.photo_url}" alt="${person.full_name}" class="w-full h-full object-cover rounded-full z-10 relative" onerror="this.onerror=null;this.src='https://placehold.co/100x100/cccccc/FFFFFF?text=?';">` :
                        `<i class="${person.gender === 'male' ? 'fas fa-male text-4xl text-white' : 'fas fa-female text-4xl text-white'} z-10 relative"></i>`
                    }
                    ${person.death_date ? '<div class="absolute bottom-1 right-1 bg-black bg-opacity-60 rounded-full p-2 flex items-center justify-center w-8 h-8"><i class="fas fa-dove text-white"></i></div>' : ''}
                </div>
                <h3 class="text-xl font-bold text-white text-center mb-2">${person.first_name}</h3>
                <p class="text-white opacity-75 text-center text-sm mb-4">${person.birth_date || 'تاريخ غير محدد'}</p>
                ${person.children_count > 0 ? `<div class="text-center mb-4"><span class="inline-block bg-white bg-opacity-20 rounded-full px-3 py-1 text-xs text-white"><i class="fas fa-users mr-1"></i> ${person.children_count} أبناء</span></div>` : ''}
                <div class="flex justify-center space-x-2 space-x-reverse">
                    <button class="modern-btn text-xs" onclick="event.stopPropagation(); showPersonDetails(${person.id});">
                        <i class="fas fa-info-circle mr-1"></i> التفاصيل
                    </button>
                    ${person.children_count > 0 ? `<button class="modern-btn text-xs" onclick="event.stopPropagation(); loadChildren(${person.id}, this);"><i class="fas fa-plus mr-1"></i> الأبناء</button>` : ''}
                </div>
            `;
            card.addEventListener('click', () => showPersonDetails(person.id));
            cardWrapper.appendChild(card);
            return cardWrapper;
        }

        // --- Vertical Tree Functions ---
        async function loadVerticalTree() {
            verticalTreeContainer.innerHTML = getLoaderHtml();
            try {
                const data = familyDataCache ? {
                    tree: familyDataCache
                } : await fetchFromAPI('/family-tree');
                if (!familyDataCache) familyDataCache = data.tree;
                verticalTreeContainer.innerHTML = '';
                if (familyDataCache && familyDataCache.length > 0) {
                    familyDataCache.forEach(person => verticalTreeContainer.appendChild(createVerticalNode(person)));
                } else {
                    verticalTreeContainer.innerHTML = getEmptyStateHtml('لا توجد بيانات');
                }
                verticalTreeContainer.dataset.loaded = 'true';
            } catch (error) {
                verticalTreeContainer.innerHTML = getErrorStateHtml('فشل تحميل الشجرة');
            }
        }

        function createVerticalNode(person) {
            const node = document.createElement('div');
            node.className = 'vertical-node-wrapper';
            node.innerHTML = `
                <div class="vertical-node glass rounded-xl p-3 cursor-pointer flex items-center space-x-4 space-x-reverse" data-person-id="${person.id}">
                    <div class="person-img ${person.death_date ? 'grayscale' : ''}" style="width: 50px; height: 50px; margin: 0;">
                        ${person.photo_url ? `<img src="${person.photo_url}" alt="${person.full_name}" class="w-full h-full object-cover rounded-full z-10 relative" onerror="this.onerror=null;this.src='https://placehold.co/50x50/cccccc/FFFFFF?text=?';">` : `<i class="${person.gender === 'male' ? 'fas fa-male text-xl text-white' : 'fas fa-female text-xl text-white'} z-10 relative"></i>`}
                        ${person.death_date ? '<div class="absolute bottom-0 right-0 bg-black bg-opacity-60 rounded-full p-1 flex items-center justify-center w-5 h-5"><i class="fas fa-dove text-white text-xs"></i></div>' : ''}
                    </div>
                    <div class="flex-1" onclick="showVerticalDetails(${person.id})"><h4 class="text-white font-bold">${person.first_name}</h4></div>
                    ${person.children_count > 0 ? `<button class="text-white opacity-60 hover:opacity-100 p-2" onclick="event.stopPropagation(); loadVerticalChildren(${person.id}, this.parentElement.parentElement)"><i class="fas fa-chevron-down transition-transform"></i></button>` : `<div class="w-8"></div>`}
                </div>
                <div class="children-vertical-container hidden"></div>
            `;
            node.querySelector('.vertical-node').addEventListener('click', (e) => {
                if (e.target.closest('.flex-1') || e.target.classList.contains('vertical-node')) {
                    document.querySelectorAll('.vertical-node.active').forEach(n => n.classList.remove('active'));
                    e.currentTarget.classList.add('active');
                    showVerticalDetails(person.id);
                }
            });
            return node;
        }

        async function loadVerticalChildren(personId, wrapperNode) {
            const childrenContainer = wrapperNode.querySelector('.children-vertical-container');
            const icon = wrapperNode.querySelector(
                'i.fa-chevron-down, i.fa-chevron-up, i.fa-spinner, i.fa-exclamation-triangle');

            if (childrenContainer.classList.contains('hidden')) {
                icon.className = 'fas fa-spinner fa-spin transition-transform';
                try {
                    if (!childrenContainer.dataset.loaded) {
                        const data = await fetchFromAPI(`/person/${personId}/children`);
                        childrenContainer.innerHTML = '';
                        if (data.children && data.children.length > 0) {
                            data.children.forEach(child => childrenContainer.appendChild(createVerticalNode(child)));
                        } else {
                            childrenContainer.innerHTML =
                                `<p class="text-white opacity-60 text-center text-sm p-2">لا يوجد أبناء</p>`;
                        }
                        childrenContainer.dataset.loaded = 'true';
                    }
                    childrenContainer.classList.remove('hidden');
                    icon.className = 'fas fa-chevron-up transition-transform';
                } catch (error) {
                    icon.className = 'fas fa-exclamation-triangle transition-transform';
                }
            } else {
                childrenContainer.classList.add('hidden');
                icon.className = 'fas fa-chevron-down transition-transform';
            }
        }

        async function showVerticalDetails(personId) {
            verticalDetailsContainer.innerHTML = getLoaderHtml();
            try {
                const data = await fetchFromAPI(`/person/${personId}`);
                const person = data.person;
                if (person) {
                    verticalDetailsContainer.innerHTML = `
                        <div class="text-center mb-8">
                            <div class="person-img mx-auto mb-4 ${person.death_date ? 'grayscale' : ''}" style="width: 120px; height: 120px;">
                                ${person.photo_url ? `<img src="${person.photo_url}" class="w-full h-full object-cover rounded-full z-10 relative" onerror="this.onerror=null;this.src='https://placehold.co/120x120/cccccc/FFFFFF?text=?';">` : `<i class="${person.gender === 'male' ? 'fas fa-male text-5xl text-white' : 'fas fa-female text-5xl text-white'} z-10 relative"></i>`}
                                ${person.death_date ? '<div class="absolute bottom-1 right-1 bg-black bg-opacity-60 rounded-full p-2 flex items-center justify-center w-8 h-8"><i class="fas fa-dove text-white"></i></div>' : ''}
                            </div>
                            <h3 class="text-3xl font-bold text-white mb-2">${person.full_name}</h3>
                        </div>
                        <div class="space-y-4">
                            ${createDetailRow('fa-birthday-cake', 'تاريخ الميلاد', person.birth_date)}
                            ${createDetailRow('fa-briefcase', 'المهنة', person.occupation)}
                            ${createDetailRow('fa-map-marker-alt', 'الموقع', person.location)}
                            ${createDetailRow('fa-users', 'عدد الأبناء', person.children_count > 0 ? person.children_count : 'لا يوجد')}
                            ${person.death_date ? createDetailRow('fa-dove', 'تاريخ الوفاة', person.death_date) : ''}
                        </div>`;
                } else {
                    throw new Error('Person not found');
                }
            } catch (error) {
                verticalDetailsContainer.innerHTML = getErrorStateHtml('فشل تحميل التفاصيل.');
            }
        }

        // --- Modal Function ---
        async function showPersonDetails(personId) {
            modal.classList.remove('hidden');
            modalContent.innerHTML = getLoaderHtml('جاري تحميل البيانات...');
            try {
                const data = await fetchFromAPI(`/person/${personId}`);
                const person = data.person;
                if (person) {
                    // Spouses Section
                    let spousesHtml = '';
                    if (person.spouses && person.spouses.length > 0) {
                        spousesHtml = `<h4 class="text-lg font-bold text-white mb-4 mt-6 border-t border-white border-opacity-10 pt-4">الزوج/الزوجات</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        ${person.spouses.map(spouse => `
                                <div class="glass p-3 rounded-lg flex items-center space-x-3 space-x-reverse cursor-pointer hover:bg-white hover:bg-opacity-10 transition-colors" onclick="showPersonDetails(${spouse.id})">
                                     <div class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center text-2xl text-white ${spouse.gender === 'male' ? 'bg-blue-500' : 'bg-pink-500'}">
                                         <i class="fas fa-${spouse.gender === 'male' ? 'male' : 'female'}"></i>
                                     </div>
                                     <div><p class="font-bold text-white">${spouse.name}</p></div>
                                </div>`).join('')}
                        </div>`;
                    }

                    // Children Section
                    let childrenHtml = '';
                    if (person.children_count > 0) {
                        try {
                            const childrenData = await fetchFromAPI(`/person/${personId}/children`);
                            if (childrenData.children && childrenData.children.length > 0) {
                                childrenHtml = `
                                    <h4 class="text-lg font-bold text-white mb-4 mt-6 border-t border-white border-opacity-10 pt-4">الأبناء</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        ${childrenData.children.map(child => `
                                                <div class="glass p-3 rounded-lg flex items-center space-x-3 space-x-reverse cursor-pointer hover:bg-white hover:bg-opacity-10 transition-colors" onclick="showPersonDetails(${child.id})">
                                                    <div class="person-img flex-shrink-0 ${child.death_date ? 'grayscale' : ''}" style="width: 50px; height: 50px; margin: 0;">
                                                         ${child.photo_url ?
                                                            `<img src="${child.photo_url}" alt="${child.full_name}" class="w-full h-full object-cover rounded-full z-10 relative" onerror="this.onerror=null;this.src='https://placehold.co/50x50/cccccc/FFFFFF?text=?';">` :
                                                            `<i class="fas fa-${child.gender === 'male' ? 'male' : 'female'} text-xl text-white z-10 relative"></i>`
                                                         }
                                                         ${child.death_date ? '<div class="absolute bottom-0 right-0 bg-black bg-opacity-60 rounded-full p-1 flex items-center justify-center w-5 h-5"><i class="fas fa-dove text-white text-xs"></i></div>' : ''}
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-white">${child.first_name}</p>
                                                        <p class="text-xs text-white opacity-60">${child.birth_date || ''}</p>
                                                    </div>
                                                </div>
                                            `).join('')}
                                    </div>
                                `;
                            }
                        } catch (err) {
                            console.error("Failed to load children in modal:", err);
                            childrenHtml = `<p class="text-red-300 mt-4">حدث خطأ في تحميل قائمة الأبناء.</p>`;
                        }
                    }

                    const age = person.birth_date && !person.death_date ? new Date().getFullYear() - new Date(person
                        .birth_date).getFullYear() : null;

                    modalContent.innerHTML = `
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-shrink-0 text-center md:w-1/3">
                                <div class="person-img mx-auto mb-4 ${person.death_date ? 'grayscale' : ''}" style="width: 150px; height: 150px;">
                                    ${person.photo_url ? `<img src="${person.photo_url}" class="w-full h-full object-cover rounded-full z-10 relative" onerror="this.onerror=null;this.src='https://placehold.co/150x150/cccccc/FFFFFF?text=?';">` : `<i class="${person.gender === 'male' ? 'fas fa-male text-6xl text-white' : 'fas fa-female text-6xl text-white'} z-10 relative"></i>`}
                                    ${person.death_date ? '<div class="absolute bottom-2 right-2 bg-black bg-opacity-60 rounded-full p-3 flex items-center justify-center w-10 h-10"><i class="fas fa-dove text-white text-lg"></i></div>' : ''}
                                </div>
                                <h3 class="text-3xl font-bold text-white mb-2">${person.full_name}</h3>
                                <p class="text-white opacity-75">${person.parent_name ? `ابن/ابنة: ${person.parent_name}` : 'الجيل الأول'}</p>
                            </div>
                            <div class="flex-1">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    ${createInfoItem('fa-birthday-cake', 'تاريخ الميلاد', person.birth_date)}
                                    ${createInfoItem('fa-calendar-alt', 'العمر', age ? `${age} سنة` : null)}
                                    ${createInfoItem('fa-briefcase', 'المهنة', person.occupation)}
                                    ${createInfoItem('fa-map-marker-alt', 'الموقع', person.location)}
                                    ${person.death_date ? createInfoItem('fa-dove', 'تاريخ الوفاة', person.death_date) : ''}
                                </div>
                                ${person.biography ? `<div class="glass rounded-xl p-4 mt-6"><h4 class="text-white font-semibold mb-2"><i class="fas fa-book-open ml-2"></i> سيرة ذاتية</h4><p class="text-white opacity-80">${person.biography}</p></div>` : ''}
                                ${spousesHtml}
                                ${childrenHtml}
                            </div>
                        </div>`;
                } else {
                    throw new Error('Person not found');
                }
            } catch (error) {
                modalContent.innerHTML = getErrorStateHtml('فشل تحميل تفاصيل الشخص.');
            }
        }
        // Make functions globally available for inline onclick
        window.showPersonDetails = showPersonDetails;
        window.loadChildren = loadChildren;
        window.loadVerticalChildren = loadVerticalChildren;
        window.showVerticalDetails = showVerticalDetails;
    </script>
</body>

</html>
