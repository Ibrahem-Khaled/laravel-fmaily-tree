<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- 🎨 Stylesheets --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #37a05c;
            --light-green: #DCF2DD;
            --dark-green: #145147;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
        }

        body {
            background: var(--light-gray);
            font-family: 'Alexandria', sans-serif;
        }

        .tree-section {
            background: linear-gradient(180deg, var(--light-green) 0%, #FFF 100%);
            padding-top: 120px;
            padding-bottom: 50px;
            min-height: 100vh;
        }

        .tree-title-sec {
            margin-bottom: 2rem;
            text-align: center;
        }

        .tree-title-sec h3 {
            color: var(--dark-green);
            font-weight: 700;
        }

        .accordion-group-item,
        .accordion-item {
            position: relative;
            width: 130px;
            border: 1px solid var(--border-color) !important;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .accordion-group-item:hover,
        .accordion-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .accordion-collapse {
            position: absolute;
            right: 100%;
            width: 130px;
            top: 0;
            padding-right: 20px;
        }

        .accordion-button {
            flex-direction: column;
            gap: 8px;
            padding: 10px;
            background-color: #fff;
            border-radius: 12px 12px 0 0 !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--dark-green) !important;
            color: white !important;
        }

        .accordion-button::after {
            display: none;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .person-photo-container {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .person-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .person-photo-container .icon-placeholder {
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .deceased-icon {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 24px;
            height: 24px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 1px solid #fff;
        }

        .person-name {
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .accordion-button:not(.collapsed) .person-name {
            color: #fff;
        }

        .actions-bar {
            width: 100%;
            display: flex;
            border-top: 1px solid var(--border-color);
            background: var(--light-gray);
            border-radius: 0 0 12px 12px;
        }

        .actions-bar .btn {
            flex: 1;
            font-size: 12px;
            padding: 6px 4px;
            color: var(--dark-green);
            border-radius: 0;
        }

        .actions-bar .btn:hover {
            background-color: #e9ecef;
        }

        .actions-bar .btn:first-child {
            border-radius: 0 0 11px 0;
        }

        .actions-bar .btn:last-child {
            border-radius: 0 0 0 11px;
        }

        /* Modal Enhancements */
        .modal-header {
            background-color: var(--dark-green);
            color: #fff;
        }

        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .modal-body .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--light-green);
            object-fit: cover;
        }

        .modal-body .icon-placeholder-lg {
            font-size: 5rem;
            color: var(--primary-color);
        }

        .detail-row {
            display: flex;
            align-items: center;
            background-color: var(--light-gray);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .detail-row i {
            color: var(--primary-color);
            width: 30px;
            text-align: center;
            font-size: 1.2rem;
        }

        .spouse-card,
        .child-card {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .spouse-card img,
        .child-card img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .spouse-card .icon-placeholder-sm,
        .child-card .icon-placeholder-sm {
            font-size: 1.5rem;
            color: var(--primary-color);
            width: 40px;
            height: 40px;
        }
    </style>
</head>

<body>
    @include('partials.header')

    <section class="tree-section">
        <div class="container">
            <div class="tree-title-sec">
                <h3>شجرة عائلة السريع</h3>
            </div>
            <div class="d-flex justify-content-center">
                <div class="accordion-group p-3" id="familyTreeContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-success" role="status"><span
                                class="visually-hidden">Loading...</span></div>
                        <p class="mt-2">جاري تحميل شجرة العائلة...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Structure -->
    <div class="modal fade" id="personDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفاصيل العضو</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBodyContent"></div>
            </div>
        </div>
    </div>

    {{-- 📦 Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const API_BASE_URL = '/api';
            const treeContainer = document.getElementById('familyTreeContainer');
            const personModal = new bootstrap.Modal(document.getElementById('personDetailModal'));

            // --- API Fetcher ---
            async function fetchAPI(endpoint) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`);
                    if (!response.ok) throw new Error(`API Error: ${response.status}`);
                    return await response.json();
                } catch (error) {
                    console.error('API Fetch Error:', error);
                    return null;
                }
            }

            // --- Helper to create Photo/Icon HTML ---
            function createPhoto(person, size = 'md') {
                const sizes = {
                    sm: {
                        container: '40px',
                        icon: '1.5rem'
                    },
                    md: {
                        container: '70px',
                        icon: '2.5rem'
                    },
                    lg: {
                        container: '120px',
                        icon: '5rem'
                    }
                };
                const currentSize = sizes[size];
                const iconClass = person.gender === 'female' ? 'fa-female' : 'fa-male';

                let photoHtml = '';
                if (person.photo_url) {
                    photoHtml =
                        `<img src="${person.photo_url}" alt="${person.first_name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`;
                }

                return `
                    <div class="person-photo-container" style="width:${currentSize.container}; height:${currentSize.container};">
                        ${photoHtml}
                        <div class="icon-placeholder" style="font-size:${currentSize.icon}; display:${person.photo_url ? 'none' : 'flex'};">
                            <i class="fas ${iconClass}"></i>
                        </div>
                        ${person.death_date ? '<div class="deceased-icon"><i class="fas fa-dove"></i></div>' : ''}
                    </div>`;
            }

            // --- Node Creation ---
            function createPersonNode(person, level = 0) {
                const hasChildren = person.children_count > 0;
                const uniqueId = `person_${person.id}_level_${level}`;
                const parentId = `tree_level_${level}`;
                const itemClass = (level === 0) ? 'accordion-group-item' : 'accordion-item';

                const buttonOrSpan = hasChildren ?
                    `<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_${uniqueId}" onclick="loadChildren(this)" data-person-id="${person.id}" data-level="${level + 1}">
                        ${createPhoto(person, 'md')}
                        <span class="person-name">${person.first_name}</span>
                    </button>` :
                    `<div class="accordion-button collapsed">
                        ${createPhoto(person, 'md')}
                        <span class="person-name">${person.first_name}</span>
                    </div>`;

                return `
                    <div class="${itemClass}">
                        <h2 class="accordion-header">${buttonOrSpan}</h2>
                        <div class="actions-bar">
                            <button class="btn btn-sm" onclick="showPersonDetails(${person.id})">التفاصيل</button>
                            <a href="/user-profile?id=${person.id}" class="btn btn-sm">ملف</a>
                        </div>
                        ${hasChildren ? `<div id="collapse_${uniqueId}" class="accordion-collapse collapse" data-bs-parent="#${parentId}"><div class="accordion-body p-0"><div class="accordion" id="tree_level_${level + 1}"></div></div></div>` : ''}
                    </div>`;
            }

            // --- Load Children Logic ---
            window.loadChildren = async (buttonElement) => {
                if (buttonElement.dataset.loaded === 'true') return;
                const personId = buttonElement.dataset.personId;
                const level = parseInt(buttonElement.dataset.level);
                const childrenContainer = document.querySelector(
                    `${buttonElement.dataset.bsTarget} .accordion`);
                if (!childrenContainer) return;

                childrenContainer.innerHTML =
                    `<div class="p-2 text-center text-muted small">جاري التحميل...</div>`;
                const data = await fetchAPI(`/person/${personId}/children`);

                childrenContainer.innerHTML = '';
                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        childrenContainer.innerHTML += createPersonNode(child, level);
                    });
                } else {
                    childrenContainer.innerHTML =
                        `<div class="p-2 text-center text-muted small">لا يوجد أبناء.</div>`;
                }
                buttonElement.dataset.loaded = 'true';
            };

            // --- Modal Details Logic ---
            window.showPersonDetails = async (personId) => {
                const modalBody = document.getElementById('modalBodyContent');
                personModal.show();
                modalBody.innerHTML =
                    `<div class="text-center p-5"><div class="spinner-border text-success"></div><p class="mt-2">جاري تحميل التفاصيل...</p></div>`;

                const data = await fetchAPI(`/person/${personId}`);
                if (!data || !data.person) {
                    modalBody.innerHTML = `<div class="alert alert-danger">فشل تحميل البيانات.</div>`;
                    return;
                }

                const person = data.person;
                const createDetailRow = (icon, label, value) => !value ? '' :
                    `<div class="detail-row"><i class="fas ${icon} fa-fw mx-2"></i><div><small class="text-muted">${label}</small><p class="mb-0 fw-bold">${value}</p></div></div>`;

                let spousesHtml = '';
                if (person.spouses && person.spouses.length > 0) {
                    spousesHtml = '<h5>الزوج/الزوجات</h5><div class="row g-2">';
                    person.spouses.forEach(spouse => {
                        spousesHtml +=
                            `<div class="col-md-6"><div class="spouse-card">${createPhoto(spouse, 'sm')} <strong>${spouse.name}</strong></div></div>`;
                    });
                    spousesHtml += '</div><hr>';
                }

                let childrenHtml = '';
                if (person.children_count > 0) {
                    childrenHtml =
                        `<h5>الأبناء (${person.children_count})</h5><div id="modalChildrenList" class="row g-2"></div>`;
                }

                modalBody.innerHTML = `
                    <div class="row g-4">
                        <div class="col-lg-4 text-center">
                            ${createPhoto(person, 'lg')}
                            <h4 class="mt-3">${person.full_name}</h4>
                            <p class="text-muted">${person.parent_name ? `ابن/ابنة: ${person.parent_name}` : 'الجيل الأول'}</p>
                            ${person.death_date ? `<p class="text-danger fw-bold"><i class="fas fa-dove"></i> متوفى</p>` : `<p class="text-success fw-bold"><i class="fas fa-heart"></i> على قيد الحياة</p>`}
                        </div>
                        <div class="col-lg-8">
                            ${createDetailRow('fa-birthday-cake', 'تاريخ الميلاد', person.birth_date)}
                            ${person.age ? createDetailRow('fa-calendar-alt', 'العمر', `${person.age} سنة`) : ''}
                            ${createDetailRow('fa-user', 'الأم', person.mother_name)}
                            ${createDetailRow('fa-briefcase', 'المهنة', person.occupation)}
                            ${createDetailRow('fa-map-marker-alt', 'مكان الإقامة', person.location)}
                            <hr>
                            ${spousesHtml}
                            ${person.biography ? `<h5>سيرة ذاتية</h5><p class="text-muted">${person.biography}</p><hr>` : ''}
                            ${childrenHtml}
                        </div>
                    </div>`;

                if (person.children_count > 0) {
                    loadModalChildren(person.id);
                }
            };

            async function loadModalChildren(personId) {
                const childrenContainer = document.getElementById('modalChildrenList');
                if (!childrenContainer) return;
                childrenContainer.innerHTML =
                    `<div class="text-center text-muted p-3">جاري تحميل الأبناء...</div>`;
                const data = await fetchAPI(`/person/${personId}/children`);
                childrenContainer.innerHTML = '';
                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        childrenContainer.innerHTML +=
                            `<div class="col-md-6"><div class="child-card">${createPhoto(child, 'sm')} <strong>${child.first_name}</strong></div></div>`;
                    });
                } else {
                    childrenContainer.innerHTML =
                        `<div class="text-center text-muted p-3">لا يوجد أبناء مسجلين.</div>`;
                }
            }

            // --- Initial Load ---
            async function loadInitialTree() {
                const data = await fetchAPI('/family-tree');
                if (data && data.tree && data.tree.length > 0) {
                    treeContainer.innerHTML =
                        `<div id="tree_level_0" class="d-flex flex-wrap justify-content-center gap-4"></div>`;
                    const firstLevelContainer = treeContainer.querySelector('#tree_level_0');
                    data.tree.forEach(person => {
                        firstLevelContainer.innerHTML += createPersonNode(person, 0);
                    });
                } else {
                    treeContainer.innerHTML =
                        '<div class="alert alert-warning text-center">لا توجد بيانات لعرضها في شجرة العائلة.</div>';
                }
            }

            loadInitialTree();
        });
    </script>
</body>

</html>
