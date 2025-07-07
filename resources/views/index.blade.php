<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شجرة عائلة السريع</title>

    <!-- Modern View CSS (Tailwind & FontAwesome) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Old View CSS (Bootstrap RTL) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">

    <style>
        /* === MODERN VIEW STYLES === */
        :root {
            --primary-green: linear-gradient(135deg, #10B981 0%, #059669 100%);
            --dark-bg: #064e3b;
            --glass-bg: rgba(16, 185, 129, 0.1);
            --glass-border: rgba(16, 185, 129, 0.2);
        }
        * { scroll-behavior: smooth; }
        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            background: var(--dark-bg);
            min-height: 100vh;
            overflow-x: hidden;
        }
        .grayscale { filter: grayscale(100%); opacity: 0.8; }
        .glass { background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); }
        .glass-dark { background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); }
        .animated-bg { background: linear-gradient(-45deg, #064e3b, #059669, #10b981, #34d399, #047857); background-size: 400% 400%; animation: gradientShift 20s ease infinite; }
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1; }
        .particle { position: absolute; width: 4px; height: 4px; background: rgba(167, 243, 208, 0.6); border-radius: 50%; animation: float 8s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); opacity: 1; } 50% { transform: translateY(-40px); opacity: 0.3; } }
        .person-card { background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05)); backdrop-filter: blur(15px); border: 1px solid var(--glass-border); border-radius: 20px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; }
        .person-card:hover { transform: translateY(-10px) scale(1.05); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); border-color: rgba(16, 185, 129, 0.5); }
        .person-img { width: 100px; height: 100px; border-radius: 50%; background: var(--primary-green); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); flex-shrink: 0; }
        .person-img::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: conic-gradient(transparent, rgba(210, 255, 239, 0.2), transparent 30%); animation: rotate 4s linear infinite; }
        @keyframes rotate { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .tree-traditional { display: flex; flex-direction: column; align-items: center; padding: 2rem; gap: 3rem; }
        .tree-level { display: flex; justify-content: center; flex-wrap: wrap; gap: 3rem; position: relative; }
        .children-level { position: relative; padding-top: 3rem; margin-top: 3rem; }
        .children-level::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 2px; height: 3rem; background-color: var(--glass-border); }
        .children-level .tree-level::before { content: ''; position: absolute; top: -1.5rem; left: 0; width: 100%; height: 2px; background-color: var(--glass-border); }
        .modern-btn { background: var(--primary-green); border: none; border-radius: 25px; padding: 0.5rem 1.5rem; color: white; font-weight: 600; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .modern-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); }
        .loading-spinner { width: 50px; height: 50px; border: 3px solid rgba(16, 185, 129, 0.3); border-radius: 50%; border-top-color: #6ee7b7; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .modal-backdrop { background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(10px); }
        .modal-content-container { background: linear-gradient(135deg, rgba(6, 78, 59, 0.8), rgba(4, 120, 87, 0.8)); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 20px; }

        /* === OLD VIEW STYLES === */
        #oldViewContainer {
            background: linear-gradient(180deg, #DCF2DD 0%, #FFF 100%);
            color: #000;
            padding: 20px;
        }
        #oldTreeView {
            width: 100%;
            height: calc(100vh - 120px);
            overflow: auto;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .old-accordion-group-item { position: relative; max-width: 120px; }
        .old-accordion-group-item + .old-accordion-group-item { margin-top: 5px; }
        .old-accordion-body { padding: 0 5px 0 0; }
        .old-accordion-collapse { position: absolute; right: 100%; width: 100%; top: 0; }
        .old-accordion-button {
            box-shadow: none !important; outline: 0 !important; align-items: center !important;
            padding: 10px; width: 100%; text-align: center;
            border: 1px solid #ddd; border-radius: 4px 4px 0 0;
        }
        .old-accordion-button.collapsed { background-color: #fff; }
        .old-accordion-button:not(.collapsed) { background-color: lightslategray !important; color: white !important; }
        .old-accordion-button:not(.collapsed) .old-profile-name { color: white !important; }
        .old-accordion-button img { width: 70px !important; height: 70px !important; margin: 0 auto 5px; border-radius: 50%; border: 2px solid #ddd; }
        .old-accordion-item { position: relative; min-width: 115px; border: 1px solid #ddd !important; border-radius: 4px; }
        .old-accordion-item + .old-accordion-item { margin-top: 5px; }

        /* General Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary-green); border-radius: 10px; }
    </style>
</head>

<body class="animated-bg">
    <div class="particles" id="particles"></div>

    <header class="fixed top-0 left-0 right-0 glass-dark z-50">
        <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="text-white text-2xl font-black tracking-wider">شجرة العائلة</div>
            <div class="flex items-center space-x-2 space-x-reverse">
                <button id="modernViewBtn" class="modern-btn text-xs">العرض الحديث</button>
                <button id="oldViewBtn" class="modern-btn text-xs">العرض القديم</button>
            </div>
        </nav>
    </header>

    <main class="pt-20 min-h-screen">
        <div id="traditionalView" class="tree-traditional"></div>
        <div id="oldViewContainer" class="hidden">
             <div id="oldTreeView" class="accordion-group"></div>
        </div>
    </main>

    <div id="personModal" class="fixed inset-0 modal-backdrop z-50 hidden items-center justify-center p-4">
        <div class="modal-content-container max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-green-300 border-opacity-20">
                <h3 class="text-2xl font-bold text-white">تفاصيل العضو</h3>
                <button id="closeModal" class="text-white hover:text-red-400 text-3xl transition-colors">&times;</button>
            </div>
            <div id="modalContent" class="p-6"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- Globals & DOM Elements ---
        const API_BASE_URL = '/api'; // <-- غيّر هذا الرابط إلى رابط الخادم الفعلي الخاص بك
        const modernViewBtn = document.getElementById('modernViewBtn');
        const oldViewBtn = document.getElementById('oldViewBtn');

        const traditionalView = document.getElementById('traditionalView');
        const oldViewContainer = document.getElementById('oldViewContainer');
        const oldTreeView = document.getElementById('oldTreeView');

        const modal = document.getElementById('personModal');
        const modalContent = document.getElementById('modalContent');
        const closeModalBtn = document.getElementById('closeModal');

        // --- Initializer ---
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
            switchToModernView();

            modernViewBtn.addEventListener('click', switchToModernView);
            oldViewBtn.addEventListener('click', switchToOldView);

            closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });

        function switchToModernView() {
            oldViewContainer.classList.add('hidden');
            traditionalView.classList.remove('hidden');
            document.body.classList.add('animated-bg');
            if (!traditionalView.dataset.loaded) {
                loadFamilyTree();
            }
        }

        function switchToOldView() {
            traditionalView.classList.add('hidden');
            oldViewContainer.classList.remove('hidden');
            document.body.classList.remove('animated-bg');
            if (!oldViewContainer.dataset.loaded) {
                loadOldTreeView();
            }
        }

        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;
            const particleCount = 50;
            for (let i = 0; i < particleCount; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                p.style.left = `${Math.random() * 100}%`;
                p.style.top = `${Math.random() * 100}%`;
                p.style.animationDelay = `${Math.random() * 8}s`;
                p.style.animationDuration = `${Math.random() * 5 + 5}s`;
                particlesContainer.appendChild(p);
            }
        }

        async function fetchFromAPI(endpoint) {
            console.log(`Fetching from: ${API_BASE_URL}${endpoint}`);
            try {
                const response = await fetch(`${API_BASE_URL}${endpoint}`);
                if (!response.ok) {
                    const errorBody = await response.text();
                    console.error(`API Error for ${endpoint}:`, errorBody);
                    throw new Error(`Network response was not ok. Status: ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                console.error('API Fetch Error:', error);
                throw error;
            }
        }

        const getLoaderHtml = (text = 'جاري التحميل...') => `<div class="text-center py-12"><div class="loading-spinner mx-auto"></div><p class="mt-4 opacity-75">${text}</p></div>`;
        const getErrorStateHtml = (text) => `<div class="text-center py-12 text-red-300"><i class="fas fa-exclamation-triangle text-4xl mb-4"></i><p>${text}</p></div>`;

        // =======================================================
        // == MODERN VIEW LOGIC ==================================
        // =======================================================
        async function loadFamilyTree() {
            traditionalView.innerHTML = getLoaderHtml();
            traditionalView.querySelector('.loading-spinner').style.borderColor = 'rgba(255,255,255,0.3)';
            traditionalView.querySelector('.loading-spinner').style.borderTopColor = '#fff';
            traditionalView.querySelector('p').classList.add('text-white');

            try {
                const data = await fetchFromAPI('/family-tree');
                traditionalView.innerHTML = '';
                const rootLevel = document.createElement('div');
                rootLevel.className = 'tree-level';
                if (data.tree && data.tree.length > 0) {
                    data.tree.forEach(person => rootLevel.appendChild(createPersonCard(person)));
                    traditionalView.appendChild(rootLevel);
                }
                traditionalView.dataset.loaded = 'true';
            } catch (error) {
                traditionalView.innerHTML = getErrorStateHtml('حدث خطأ أثناء تحميل شجرة العائلة.');
            }
        }

        async function loadModernChildren(personId, button) {
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
                        childrenContainer.innerHTML = `<p class="text-white opacity-70 text-center col-span-full">لا يوجد أبناء مسجلين.</p>`;
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
                    <img src="${person.photo_url || (person.gender === 'male' ? 'https://placehold.co/100x100/cccccc/000000?text=رجل' : 'https://placehold.co/100x100/cccccc/000000?text=امرأة')}" alt="${person.full_name}" class="w-full h-full object-cover rounded-full z-10 relative">
                    ${person.death_date ? '<div class="absolute bottom-1 right-1 bg-black bg-opacity-60 rounded-full p-2 flex items-center justify-center w-8 h-8"><i class="fas fa-dove text-white"></i></div>' : ''}
                </div>
                <h3 class="text-xl font-bold text-white text-center mb-2">${person.full_name}</h3>
                <p class="text-white opacity-75 text-center text-sm mb-4">${person.birth_date || 'تاريخ غير محدد'}</p>
                ${person.children_count > 0 ? `<div class="text-center mb-4"><span class="inline-block bg-white bg-opacity-20 rounded-full px-3 py-1 text-xs text-white"><i class="fas fa-users mr-1"></i> ${person.children_count} أبناء</span></div>` : ''}
                <div class="flex justify-center space-x-2 space-x-reverse">
                    <button class="modern-btn text-xs" onclick="event.stopPropagation(); showPersonDetails(${person.id});">
                        <i class="fas fa-info-circle mr-1"></i> التفاصيل
                    </button>
                    ${person.children_count > 0 ? `<button class="modern-btn text-xs" onclick="event.stopPropagation(); loadModernChildren(${person.id}, this);"><i class="fas fa-plus mr-1"></i> الأبناء</button>` : ''}
                </div>
            `;
            card.addEventListener('click', () => showPersonDetails(person.id));
            cardWrapper.appendChild(card);
            return cardWrapper;
        }

        async function showPersonDetails(personId) {
            modal.classList.remove('hidden');
            modalContent.innerHTML = getLoaderHtml('جاري تحميل البيانات...');
            try {
                const person = await fetchFromAPI(`/person/${personId}`);
                if (person) {
                    const childrenData = person.children_count > 0 ? await fetchFromAPI(`/person/${personId}/children`) : { children: [] };

                    const createInfoItem = (icon, label, value) => !value ? '' : `<div class="glass rounded-xl p-4"><h4 class="text-white font-semibold mb-2"><i class="fas ${icon} ml-2 opacity-70"></i> ${label}</h4><p class="text-white opacity-80">${value}</p></div>`;

                    let spousesHtml = '';
                    if (person.spouses && person.spouses.length > 0) {
                        spousesHtml = `<h4 class="text-lg font-bold text-white mb-4 mt-6 border-t border-white border-opacity-10 pt-4">الزوج/الزوجات</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        ${person.spouses.map(spouse => `<div class="glass p-3 rounded-lg flex items-center space-x-3 space-x-reverse">...</div>`).join('')}
                        </div>`;
                    }

                    let childrenHtml = '';
                    if (childrenData.children && childrenData.children.length > 0) {
                        childrenHtml = `<h4 class="text-lg font-bold text-white mb-4 mt-6 border-t border-white border-opacity-10 pt-4">الأبناء</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        ${childrenData.children.map(child => `...`).join('')}
                        </div>`;
                    }

                    modalContent.innerHTML = `
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-shrink-0 text-center md:w-1/3">...</div>
                            <div class="flex-1">...</div>
                        </div>
                    `;
                } else { throw new Error('Person not found'); }
            } catch (error) {
                modalContent.innerHTML = getErrorStateHtml('فشل تحميل تفاصيل الشخص.');
            }
        }

        // =======================================================
        // == OLD VIEW LOGIC =====================================
        // =======================================================
        async function loadOldTreeView() {
            oldTreeView.innerHTML = getLoaderHtml();
            try {
                const data = await fetchFromAPI('/family-tree');
                oldTreeView.innerHTML = '';
                if (data.tree && data.tree.length > 0) {
                    data.tree.forEach(person => createOldTreeAccordionNode(person, oldTreeView, 0));
                }
                oldViewContainer.dataset.loaded = 'true';
            } catch (error) {
                 oldTreeView.innerHTML = getErrorStateHtml('حدث خطأ أثناء تحميل الشجرة.');
            }
        }

        function createOldTreeAccordionNode(person, parentElement, level) {
            const isMaleWithChildren = person.children_count > 0 && person.gender === 'male';
            const uniqueId = `person_${level}_${person.id}`;

            const itemWrapper = document.createElement('div');
            itemWrapper.className = level === 0 ? 'old-accordion-group-item' : 'old-accordion-item';

            const header = document.createElement('h2');
            header.className = 'accordion-header';

            let button;
            if (isMaleWithChildren) {
                button = document.createElement('button');
                button.className = 'old-accordion-button d-flex flex-column gap-1 collapsed';
                button.type = 'button';
                button.setAttribute('data-bs-toggle', 'collapse');
                button.setAttribute('data-bs-target', `#collapse_${uniqueId}`);
            } else {
                button = document.createElement('span');
                button.className = 'old-accordion-button d-flex flex-column gap-1 collapsed';
            }

            button.innerHTML = `
                <img src="${person.photo_url || (person.gender === 'male' ? 'https://placehold.co/70x70/cccccc/000000?text=رجل' : 'https://placehold.co/70x70/cccccc/000000?text=امرأة')}" class="${person.death_date ? 'grayscale' : ''}">
                <span class="old-profile-name">${person.full_name}</span>
            `;

            header.appendChild(button);
            itemWrapper.appendChild(header);

            if (isMaleWithChildren) {
                const collapseDiv = document.createElement('div');
                collapseDiv.id = `collapse_${uniqueId}`;
                collapseDiv.className = 'accordion-collapse collapse old-accordion-collapse';

                const parentAccordion = parentElement.closest('.accordion, .accordion-group');
                if (parentAccordion) collapseDiv.setAttribute('data-bs-parent', `#${parentAccordion.id}`);

                const body = document.createElement('div');
                body.className = 'accordion-body';
                const nestedAccordion = document.createElement('div');
                nestedAccordion.className = 'accordion';
                nestedAccordion.id = `nested_accordion_${uniqueId}`;
                body.appendChild(nestedAccordion);
                collapseDiv.appendChild(body);
                itemWrapper.appendChild(collapseDiv);

                button.addEventListener('click', async () => {
                    if (nestedAccordion.dataset.loaded !== 'true') {
                        nestedAccordion.innerHTML = getLoaderHtml();
                         try {
                            const data = await fetchFromAPI(`/person/${person.id}/children`);
                            nestedAccordion.innerHTML = '';
                            if (data.children && data.children.length > 0) {
                                data.children.forEach(child => createOldTreeAccordionNode(child, nestedAccordion, level + 1));
                            } else {
                                nestedAccordion.innerHTML = '<p class="text-center p-2">لا يوجد أبناء</p>';
                            }
                            nestedAccordion.dataset.loaded = 'true';
                         } catch(e) {
                             nestedAccordion.innerHTML = getErrorStateHtml('فشل تحميل الأبناء');
                         }
                    }
                });
            }
            parentElement.appendChild(itemWrapper);
        }

        // Make functions globally available for inline onclick attributes
        window.showPersonDetails = showPersonDetails;
        window.loadModernChildren = loadModernChildren;
    </script>
</body>
</html>
