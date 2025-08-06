// --- Globals & DOM Elements ---
const API_BASE_URL = '/api'; // <-- غيّر هذا الرابط إلى رابط الخادم الفعلي الخاص بك
const traditionalView = document.getElementById('traditionalView');
const verticalView = document.getElementById('verticalView');
const viewToggle = document.getElementById('viewToggle'); // This ID is preserved
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

    // --- New View Switcher Logic ---
    const btnTraditionalView = document.getElementById('btnTraditionalView');
    const btnVerticalView = document.getElementById('btnVerticalView');

    function switchView(isVertical) {
        // Update button active states
        btnTraditionalView.classList.toggle('active', !isVertical);
        btnVerticalView.classList.toggle('active', isVertical);

        // Use the original viewToggle element to maintain state, even though it's hidden
        viewToggle.classList.toggle('active', isVertical);

        // Show/hide the main view containers based on state
        traditionalView.classList.toggle('hidden', isVertical);
        verticalView.classList.toggle('hidden', !isVertical);

        // Load vertical tree data if switching to it for the first time
        if (isVertical && !verticalTreeContainer.dataset.loaded) {
            loadVerticalTree();
        }
    }

    // Add click listeners to new buttons
    btnTraditionalView.addEventListener('click', () => switchView(false));
    btnVerticalView.addEventListener('click', () => switchView(true));
    // --- End of New Logic ---

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
async function fetchFromAPI(endpoint) {
    console.log(`Fetching from: ${API_BASE_URL}${endpoint}`);
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`);
        if (!response.ok) {
            const errorBody = await response.text();
            console.error(`API Error Response for ${endpoint}:`, errorBody);
            throw new Error(`Network response was not ok. Status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('API Fetch Error:', error);
        // Re-throw the error so the calling function can handle it and display an error message to the user.
        throw error;
    }
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
        traditionalTreeContainer.innerHTML = getErrorStateHtml('حدث خطأ أثناء تحميل تواصل العائلة.');
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