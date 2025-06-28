<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شجرة عائلة السريع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .bounce-arrow {
            animation: bounce 2s infinite;
        }

        body {
            font-family: 'Tajawal', sans-serif;
        }

        .person-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .child-img {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* --- بداية التعديلات على الشجرة الهرمية --- */

        .tree {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
            width: 100%;
        }

        .tree-level {
            display: flex;
            justify-content: center;
            margin-bottom: 40px; /* تقليل المسافة العمودية قليلاً */
            position: relative;
            /* التخطيط العمودي للهواتف (افتراضي) */
            flex-direction: column;
            align-items: center;
            gap: 40px; /* إضافة مسافة بين العناصر المكدسة عمودياً */
        }

        .tree-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            /* إزالة الهامش الأفقي للموبايل */
            margin: 0;
        }

        /* إعادة التخطيط الأفقي للشاشات الأكبر */
        @media (min-width: 768px) {
            .tree-level {
                flex-direction: row;
                gap: 0; /* إزالة المسافة عند العودة للشكل الأفقي */
                margin-bottom: 60px;
            }
            .tree-node {
                margin: 0 20px; /* إعادة الهامش الأفقي */
            }
        }

        .node-connector {
            display: none; /* إخفاء الموصلات العمودية في تخطيط الموبايل */
        }
        @media (min-width: 768px) {
            .node-connector {
                display: block; /* إظهار الموصلات في الشاشات الكبيرة */
                position: absolute;
                top: -30px;
                height: 30px;
                width: 2px;
                background: rgba(255, 255, 255, 0.5);
            }
        }

        .children-container {
            display: flex;
            margin-top: 20px; /* تقليل المسافة العلوية */
            position: relative;
            /* التخطيط العمودي للأبناء في الموبايل */
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding-top: 20px; /* مسافة للخط الواصل */
            border-left: 2px solid rgba(255, 255, 255, 0.5); /* خط عمودي يربط الأبناء */
            padding-left: 20px; /* إبعاد الأبناء عن الخط العمودي */
        }

        /* إخفاء الخط العمودي للشاشات الكبيرة */
        @media (min-width: 768px) {
            .children-container {
                flex-direction: row;
                margin-top: 40px;
                gap: 0;
                padding-top: 0;
                border-left: none;
                padding-left: 0;
            }
        }

        .children-container::before {
            content: '';
            position: absolute;
            top: -20px;
            /* الخط الأفقي سيظهر فقط في الشاشات الكبيرة */
            left: 50%;
            width: 2px;
            height: 20px;
            background: rgba(255, 255, 255, 0.5);
        }

        @media (min-width: 768px) {
             .children-container::before {
                /* هذا هو الخط الأفقي العلوي الذي يربط جميع الأبناء */
                top: -20px;
                left: 0;
                right: 0;
                width: auto;
                height: 2px;
                border-top: 2px solid rgba(255, 255, 255, 0.5);
                background: none;
            }
        }

        .child-connector {
            position: absolute;
            /* تغيير ليتناسب مع الخط العمودي في الموبايل */
            top: 0;
            left: -20px;
            height: 2px;
            width: 20px;
            background: rgba(255, 255, 255, 0.5);
        }

        @media (min-width: 768px) {
            .child-connector {
                 /* هذا هو الخط العمودي الصغير لكل طفل */
                top: -20px;
                left: 50%;
                transform: translateX(-50%);
                height: 20px;
                width: 2px;
            }
        }

        .generation-label {
            position: absolute;
            /* تعديل الموضع ليتناسب مع التخطيط العمودي */
            right: 10px;
            top: -20px;
            background: rgba(21, 128, 61, 0.2);
            color: #bbf7d0;
            padding: 4px 12px;
            border-radius: 9999px;
            border: 1px solid rgba(74, 222, 128, 0.3);
            backdrop-filter: blur(10px);
        }
        @media (min-width: 768px) {
            .generation-label {
                right: 0;
                top: -40px;
            }
        }

        /* --- نهاية التعديلات --- */

        .spouses-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
            justify-content: center;
        }

        .spouse-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            min-width: 200px;
            max-width: 300px;
        }

        .spouse-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #f0f0f0;
        }

        .children-list {
            display: grid;
            /* هذا التنسيق متجاوب بالفعل وهو ممتاز */
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .child-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .child-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }

        .child-item-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 0.5rem;
        }

        .mother-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-900 via-green-700 to-green-500 min-h-screen overflow-x-auto">
    <header
        class="fixed top-0 left-0 right-0 bg-black bg-opacity-30 backdrop-blur-md z-50 border-b border-green-500 border-opacity-30">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-green-400 text-2xl font-bold">🌳 شجرة العائلة</div>
            <ul class="hidden md:flex space-x-8">
                <li><a href="#home"
                        class="text-green-100 hover:text-green-400 transition duration-300 relative after:absolute after:bottom-[-5px] after:right-0 after:w-0 after:h-0.5 after:bg-green-500 hover:after:w-full after:transition-all">الرئيسية</a>
                </li>
                <li><a href="#family"
                        class="text-green-100 hover:text-green-400 transition duration-300 relative after:absolute after:bottom-[-5px] after:right-0 after:w-0 after:h-0.5 after:bg-green-500 hover:after:w-full after:transition-all">العائلة</a>
                </li>
                <li><a href="#gallery"
                        class="text-green-100 hover:text-green-400 transition duration-300 relative after:absolute after:bottom-[-5px] after:right-0 after:w-0 after:h-0.5 after:bg-green-500 hover:after:w-full after:transition-all">معرض
                        الصور</a></li>
                <li><a href="#stories"
                        class="text-green-100 hover:text-green-400 transition duration-300 relative after:absolute after:bottom-[-5px] after:right-0 after:w-0 after:h-0.5 after:bg-green-500 hover:after:w-full after:transition-all">قصص
                        العائلة</a></li>
                <li><a href="#contact"
                        class="text-green-100 hover:text-green-400 transition duration-300 relative after:absolute after:bottom-[-5px] after:right-0 after:w-0 after:h-0.5 after:bg-green-500 hover:after:w-full after:transition-all">اتصل
                        بنا</a></li>
            </ul>
            <button class="md:hidden flex flex-col space-y-1.5" id="hamburger">
                <span class="w-6 h-0.5 bg-green-400 transition-all"></span>
                <span class="w-6 h-0.5 bg-green-400 transition-all"></span>
                <span class="w-6 h-0.5 bg-green-400 transition-all"></span>
            </button>
        </nav>
    </header>

    <div class="fixed top-16 right-0 w-full bg-black bg-opacity-90 z-40 hidden" id="mobileMenu">
        <ul class="flex flex-col items-center py-4 space-y-4">
            <li><a href="#home" class="text-green-100 hover:text-green-400 transition duration-300">الرئيسية</a></li>
            <li><a href="#family" class="text-green-100 hover:text-green-400 transition duration-300">العائلة</a></li>
            <li><a href="#gallery" class="text-green-100 hover:text-green-400 transition duration-300">معرض الصور</a>
            </li>
            <li><a href="#stories" class="text-green-100 hover:text-green-400 transition duration-300">قصص العائلة</a>
            </li>
            <li><a href="#contact" class="text-green-100 hover:text-green-400 transition duration-300">اتصل بنا</a></li>
        </ul>
    </div>

    <main class="container mx-auto px-4 py-8 mt-20">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-light text-white tracking-wider mb-2">عائلة السريع</h1>
            <p class="text-xl text-green-200">شجرة العائلة الكاملة</p>
        </div>

        <div id="family-tree-container" class="tree">
            <div class="text-center py-12">
                <div class="loading-spinner mx-auto"></div>
                <p class="text-green-200 mt-4">جاري تحميل شجرة العائلة...</p>
            </div>
        </div>

        <div id="person-modal"
            class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
            <div
                class="bg-gradient-to-br from-green-600 to-green-800 rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center p-4 border-b border-green-500">
                    <h3 class="text-xl font-bold text-white">تفاصيل العضو</h3>
                    <button id="close-modal" class="text-white hover:text-green-300 text-2xl">&times;</button>
                </div>
                <div id="modal-content" class="p-6">
                    </div>
            </div>
        </div>
    </main>

    <script>
        // Mobile Menu Toggle
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('hidden');

            // Animate hamburger icon
            const spans = hamburger.querySelectorAll('span');
            if (hamburger.classList.contains('active')) {
                spans[0].style.transform = 'translateY(8px) rotate(45deg)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'translateY(-8px) rotate(-45deg)';
            } else {
                spans[0].style.transform = '';
                spans[1].style.opacity = '';
                spans[2].style.transform = '';
            }
        });

        // Close modal
        document.getElementById('close-modal').addEventListener('click', () => {
            document.getElementById('person-modal').classList.add('hidden');
        });

        // Load family tree initially
        document.addEventListener('DOMContentLoaded', function() {
            loadFamilyTree();
        });

        // Function to load family tree
        function loadFamilyTree() {
            fetch('/api/family-tree')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('family-tree-container');
                    container.innerHTML = '';

                    if (data.tree && data.tree.length > 0) {
                        // Create root level
                        const rootLevel = document.createElement('div');
                        rootLevel.className = 'tree-level';

                        // Add generation label
                        const genLabel = document.createElement('div');
                        genLabel.className = 'generation-label';
                        genLabel.textContent = 'الجيل الأول - الأجداد';
                        rootLevel.appendChild(genLabel);

                        // Add root nodes
                        data.tree.forEach(person => {
                            const node = createTreeNode(person, 0);
                            rootLevel.appendChild(node);
                        });

                        container.appendChild(rootLevel);
                    } else {
                        container.innerHTML =
                            '<div class="text-center py-12 text-green-200">لا توجد بيانات متاحة</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading family tree:', error);
                    document.getElementById('family-tree-container').innerHTML =
                        '<div class="text-center py-12 text-red-300">حدث خطأ أثناء تحميل شجرة العائلة</div>';
                });
        }

        // Function to create tree node
        function createTreeNode(person, level) {
            const nodeDiv = document.createElement('div');
            nodeDiv.className = 'tree-node';

            // Add connector if not root level
            if (level > 0) {
                const connector = document.createElement('div');
                connector.className = 'node-connector';
                nodeDiv.appendChild(connector);
            }

            // Add person card
            const card = createPersonCard(person, level);
            nodeDiv.appendChild(card);

            // If person has children, add children container (initially hidden)
            if (person.children_count > 0) {
                const childrenContainer = document.createElement('div');
                childrenContainer.className = 'children-container hidden';
                childrenContainer.dataset.parentId = person.id;

                // Add connector for each child
                const connector = document.createElement('div');
                connector.className = 'child-connector';
                childrenContainer.appendChild(connector);

                nodeDiv.appendChild(childrenContainer);
            }

            return nodeDiv;
        }

        // Function to create person card
        function createPersonCard(person, level) {
            const card = document.createElement('div');
            card.className =
                'person-card bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-center shadow-lg cursor-pointer transform transition-all hover:-translate-y-2 hover:scale-105 hover:shadow-xl';
            card.dataset.personId = person.id;
            card.dataset.level = level;

            // Person image or placeholder
            const imgDiv = document.createElement('div');
            imgDiv.className = 'person-img mx-auto mb-2 flex items-center justify-center';
            imgDiv.style.backgroundColor = person.gender === 'male' ? '#e3f2fd' : '#fce4ec';

            if (person.photo_url) {
                const img = document.createElement('img');
                img.src = person.photo_url;
                img.alt = person.full_name;
                img.className = 'w-full h-full object-cover rounded-full';
                imgDiv.appendChild(img);
            } else {
                const icon = document.createElement('i');
                icon.className = person.gender === 'male' ?
                    'fas fa-male text-2xl text-blue-500' :
                    'fas fa-female text-2xl text-pink-500';
                imgDiv.appendChild(icon);
            }
            card.appendChild(imgDiv);

            // Person name
            const name = document.createElement('h3');
            name.className = 'text-lg font-semibold text-white truncate';
            name.textContent = person.first_name;
            card.appendChild(name);

            // Birth date
            if (person.birth_date) {
                const birthDate = document.createElement('p');
                birthDate.className = 'text-green-100 text-xs';
                birthDate.textContent = `ولد: ${person.birth_date}`;
                card.appendChild(birthDate);
            }

            // Children count
            if (person.children_count > 0) {
                const childrenCount = document.createElement('p');
                childrenCount.className = 'text-green-200 text-xs mt-1';
                childrenCount.innerHTML = `<i class="fas fa-child mr-1"></i> ${person.children_count} أبناء`;
                card.appendChild(childrenCount);
            }

            // View details button
            const detailsBtn = document.createElement('button');
            detailsBtn.className = 'text-green-300 hover:text-white mt-2 text-xs';
            detailsBtn.innerHTML = '<i class="fas fa-info-circle mr-1"></i> التفاصيل';
            detailsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                showPersonDetails(person.id);
            });
            card.appendChild(detailsBtn);

            // Add click event to load children
            if (person.children_count > 0) {
                card.addEventListener('click', function(e) {
                    if (e.target.tagName === 'BUTTON') return;

                    const personId = this.dataset.personId;
                    const childrenContainer = this.parentNode.querySelector('.children-container');

                    if (childrenContainer) {
                        if (childrenContainer.classList.contains('hidden')) {
                            // Load children if not already loaded
                            if (childrenContainer.children.length <= 1) { // Only has the connector
                                loadChildren(personId, childrenContainer, level + 1);
                            } else {
                                childrenContainer.classList.remove('hidden');
                            }
                        } else {
                            childrenContainer.classList.add('hidden');
                        }
                    }
                });
            }

            return card;
        }

        // Function to load children
        function loadChildren(parentId, container, level) {
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'text-center py-4';
            loadingIndicator.innerHTML =
                '<div class="loading-spinner mx-auto"></div><p class="text-green-200 mt-2">جاري تحميل الأبناء...</p>';
            container.appendChild(loadingIndicator);

            fetch(`/api/person/${parentId}/children`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ''; // Remove loading indicator

                    // Add connector
                    const connector = document.createElement('div');
                    connector.className = 'child-connector';
                    container.appendChild(connector);

                    if (data.children && data.children.length > 0) {
                        data.children.forEach(child => {
                            const childNode = createTreeNode(child, level);
                            container.appendChild(childNode);
                        });
                    }

                    container.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading children:', error);
                    container.innerHTML =
                        '<div class="text-red-300 text-center py-4">حدث خطأ أثناء تحميل الأبناء</div>';
                });
        }

        // Function to show person details in modal
        function showPersonDetails(personId) {
            const modal = document.getElementById('person-modal');
            const modalContent = document.getElementById('modal-content');

            modalContent.innerHTML =
                '<div class="text-center py-8"><div class="loading-spinner mx-auto"></div><p class="text-green-200 mt-4">جاري تحميل البيانات...</p></div>';
            modal.classList.remove('hidden');

            fetch(`/api/person/${personId}`)
                .then(response => response.json())
                .then(data => {
                    const person = data.person;
                    let html = `
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-shrink-0">
                                <div class="person-img mx-auto mb-4 flex items-center justify-center" style="background-color: ${person.gender === 'male' ? '#e3f2fd' : '#fce4ec'}; width: 120px; height: 120px;">
                                    ${person.photo_url ?
                                        `<img src="${person.photo_url}" alt="${person.full_name}" class="w-full h-full object-cover rounded-full">` :
                                        `<i class="${person.gender === 'male' ? 'fas fa-male text-5xl text-blue-500' : 'fas fa-female text-5xl text-pink-500'}"></i>`
                                    }
                                </div>
                                <h3 class="text-2xl font-bold text-center text-white">${person.full_name}</h3>
                                ${person.parent_name ? `<p class="text-green-200 text-center mt-1">ابن/ابنة ${person.parent_name}</p>` : ''}
                                ${person.mother_name ? `<p class="text-green-200 text-center mt-1">ابن/ابنة ${person.mother_name} (الأم)</p>` : ''}
                            </div>
                            <div class="flex-grow">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        `;

                    if (person.birth_date) {
                        html += `
                                <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                                    <h4 class="text-green-300 font-semibold"><i class="fas fa-birthday-cake ml-2"></i> تاريخ الميلاد</h4>
                                    <p class="text-white mt-1">${person.birth_date}</p>
                                </div>
                            `;
                    }

                    if (person.death_date) {
                        html += `
                                <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                                    <h4 class="text-green-300 font-semibold"><i class="fas fa-cross ml-2"></i> تاريخ الوفاة</h4>
                                    <p class="text-white mt-1">${person.death_date}</p>
                                </div>
                            `;
                    }

                    if (person.age) {
                        html += `
                                <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                                    <h4 class="text-green-300 font-semibold"><i class="fas fa-calendar-alt ml-2"></i> العمر</h4>
                                    <p class="text-white mt-1">${person.age} سنة</p>
                                </div>
                            `;
                    }

                    if (person.occupation) {
                        html += `
                                <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                                    <h4 class="text-green-300 font-semibold"><i class="fas fa-briefcase ml-2"></i> المهنة</h4>
                                    <p class="text-white mt-1">${person.occupation}</p>
                                </div>
                            `;
                    }

                    if (person.location) {
                        html += `
                                <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                                    <h4 class="text-green-300 font-semibold"><i class="fas fa-map-marker-alt ml-2"></i> الموقع</h4>
                                    <p class="text-white mt-1">${person.location}</p>
                                </div>
                            `;
                    }

                    // إضافة قسم الأم إذا كانت موجودة
                    if (person.mother_id) {
                        html += `
                                <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                                    <h4 class="text-green-300 font-semibold"><i class="fas fa-female ml-2"></i> الأم</h4>
                                    <div class="flex items-center mt-2">
                                        <div class="child-item-img flex items-center justify-center ml-3" style="background-color: #fce4ec; width: 40px; height: 40px;">
                                            <i class="fas fa-female text-xl text-pink-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-white">${person.mother_name || 'غير معروفة'}</p>
                                            <button onclick="showPersonDetails(${person.mother_id})" class="text-green-300 hover:text-white text-xs mt-1">
                                                <i class="fas fa-info-circle mr-1"></i> عرض التفاصيل
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                    }

                    html += `
                                </div>
                                ${person.biography ? `
                                        <div class="mt-4 bg-green-500 bg-opacity-20 p-3 rounded-lg">
                                            <h4 class="text-green-300 font-semibold"><i class="fas fa-book-open ml-2"></i> السيرة الذاتية</h4>
                                            <p class="text-white mt-2">${person.biography}</p>
                                        </div>
                                    ` : ''}
                            </div>
                        </div>
                    `;

                    // إضافة قسم الزوج/الزوجات إذا كان موجودًا
                    if (person.spouses && person.spouses.length > 0) {
                        html += `
                            <div class="mt-6">
                                <h4 class="text-xl font-semibold text-white border-b border-green-500 pb-2 mb-4">
                                    ${person.gender === 'male' ? 'الزوجات' : 'الزوج'}
                                </h4>
                                <div class="spouses-container">
                        `;

                        // عرض جميع الزوجات بجانب بعضهم
                        person.spouses.forEach(spouse => {
                            html += `
                                <div class="spouse-card">
                                    <div class="spouse-img flex items-center justify-center" style="background-color: ${spouse.gender === 'male' ? '#e3f2fd' : '#fce4ec'}">
                                        ${spouse.photo ?
                                            `<img src="${spouse.photo}" alt="${spouse.name}" class="w-full h-full object-cover rounded-full">` :
                                            `<i class="${spouse.gender === 'male' ? 'fas fa-male text-3xl text-blue-500' : 'fas fa-female text-3xl text-pink-500'}"></i>`
                                        }
                                    </div>
                                    <div>
                                        <h5 class="text-lg font-medium text-white">${spouse.name}</h5>
                                        <p class="text-green-200 text-sm">${spouse.gender === 'male' ? 'زوج' : 'زوجة'}</p>
                                        <button onclick="showPersonDetails(${spouse.id})" class="text-green-300 hover:text-white mt-1 text-xs">
                                            <i class="fas fa-info-circle mr-1"></i> عرض التفاصيل
                                        </button>
                                    </div>
                                </div>
                            `;
                        });

                        html += `</div></div>`;
                    }

                    // إضافة قسم الأبناء المباشرين إذا كان لديه أبناء
                    if (person.children_count > 0) {
                        html += `
                            <div class="mt-6">
                                <h4 class="text-xl font-semibold text-white border-b border-green-500 pb-2 mb-4">
                                    الأبناء (${person.children_count})
                                </h4>
                                <div id="direct-children-list" class="text-center py-4">
                                    <div class="loading-spinner mx-auto"></div>
                                    <p class="text-green-200 mt-2">جاري تحميل قائمة الأبناء...</p>
                                </div>
                            </div>
                        `;
                    }

                    modalContent.innerHTML = html;

                    // إذا كان لديه أبناء، قم بتحميل قائمة الأبناء
                    if (person.children_count > 0) {
                        loadDirectChildren(personId);
                    }
                })
                .catch(error => {
                    console.error('Error loading person details:', error);
                    modalContent.innerHTML =
                        '<div class="text-center py-8 text-red-300">حدث خطأ أثناء تحميل البيانات</div>';
                });
        }

        // Function to load direct children for a person
        function loadDirectChildren(personId) {
            fetch(`/api/person/${personId}/children`)
                .then(response => response.json())
                .then(data => {
                    const childrenContainer = document.getElementById('direct-children-list');

                    if (data.children && data.children.length > 0) {
                        let html = '<div class="children-list">';

                        data.children.forEach(child => {
                            html += `
                                <div class="child-item" onclick="showPersonDetails(${child.id})">
                                    <div class="child-item-img flex items-center justify-center" style="background-color: ${child.gender === 'male' ? '#e3f2fd' : '#fce4ec'}">
                                        ${child.photo_url ?
                                            `<img src="${child.photo_url}" alt="${child.full_name}" class="w-full h-full object-cover rounded-full">` :
                                            `<i class="${child.gender === 'male' ? 'fas fa-male text-2xl text-blue-500' : 'fas fa-female text-2xl text-pink-500'}"></i>`
                                        }
                                    </div>
                                    <h5 class="text-white font-medium text-sm">${child.first_name}</h5>
                                    ${child.birth_date ? `<p class="text-green-200 text-xs">${child.birth_date}</p>` : ''}
                                </div>
                            `;
                        });

                        html += '</div>';
                        childrenContainer.innerHTML = html;
                    } else {
                        childrenContainer.innerHTML = '<p class="text-green-200">لا يوجد أبناء مسجلين</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading direct children:', error);
                    document.getElementById('direct-children-list').innerHTML =
                        '<p class="text-red-300">حدث خطأ أثناء تحميل قائمة الأبناء</p>';
                });
        }

        // جعل الدوال متاحة عالميًا للاستخدام في الأحداث
        window.showPersonDetails = showPersonDetails;
    </script>
</body>

</html>
