<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- 🎨 Stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=0.6">
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}?v=0.6">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">

    <style>
        /* Your original custom CSS remains the same */
        body {
            background: #fff;
        }

        .tree-section {
            background: linear-gradient(180deg, #DCF2DD 0%, #FFF 100%);
        }

        .accordion-group-item,
        .accordion-item {
            position: relative;
            max-width: 120px;
            border: 1px solid #ddd !important;
            border-radius: 4px;
        }

        .accordion-group-item+.accordion-group-item,
        .accordion-item+.accordion-item {
            margin-top: 5px;
        }

        .accordion-collapse {
            position: absolute;
            right: 100%;
            width: 100%;
            top: 0;
        }

        .accordion-button {
            box-shadow: none !important;
            outline: 0 !important;
            padding: 10px;
        }

        .accordion-button:not(.collapsed) {
            background-color: lightslategray !important;
            color: white !important;
        }

        .accordion-button::after {
            display: none;
        }

        .actions-bar {
            display: flex;
            justify-content: space-around;
            padding: 4px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 0 0 4px 4px;
        }

        .actions-bar .btn {
            font-size: 12px;
            padding: 2px 8px;
            color: #145147;
        }

        .accordion-body .loader {
            text-align: center;
            padding: 20px;
            color: #145147;
            font-size: 14px;
        }

        /* Modal Styles */
        .modal-body .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid #DCF2DD;
            object-fit: cover;
        }

        .detail-row {
            display: flex;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.03);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .detail-row i {
            color: #37a05c;
            width: 30px;
            text-align: center;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    @include('partials.header')

    <section class="tree-section position-relative" style="padding-top: 140px;">
        <div class="container">
            <div>
                <div class="tree-title-sec">
                    <h3>تواصل العائلة</h3>
                </div>
            </div>
            <div class="accordion-group p-3" id="familyTreeContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-success" role="status"><span
                            class="visually-hidden">Loading...</span></div>
                    <p class="mt-2">جاري تحميل شجرة العائلة...</p>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="personDetailModal" tabindex="-1" aria-labelledby="personDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="personDetailModalLabel">تفاصيل العضو</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBodyContent">
                    {{-- Person details will be loaded here --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 📦 Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const API_BASE_URL = '/api';
            const treeContainer = document.getElementById('familyTreeContainer');

            // --- API Fetcher ---
            async function fetchFromAPI(endpoint) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`);
                    if (!response.ok) throw new Error(`API Error: ${response.status}`);
                    return await response.json();
                } catch (error) {
                    console.error('Failed to fetch from API:', error);
                    return null;
                }
            }

            // --- Node Creation ---
            function createPersonNode(person, level = 0) {
                const hasChildren = person.children_count > 0;
                const uniqueId = `person_${person.id}_level_${level}`;
                const parentId = `tree_level_${level}`;
                const itemClass = (level === 0) ? 'accordion-group-item' : 'accordion-item';

                const buttonOrSpan = hasChildren ?
                    `<button class="accordion-button accordion-group-button d-flex flex-column gap-1 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_${uniqueId}" aria-expanded="false" aria-controls="collapse_${uniqueId}" data-person-id="${person.id}" data-level="${level + 1}" onclick="loadChildren(this)">
                        <img src="${person.photo_url || '/images/male.png'}" width="70" height="70" onerror="this.onerror=null;this.src='/images/male.png';">
                    </button>` :
                    `<span class="accordion-button accordion-group-button d-flex flex-column gap-1 collapsed">
                        <img src="${person.photo_url || '/images/male.png'}" width="70" height="70" onerror="this.onerror=null;this.src='/images/male.png';">
                    </span>`;

                const nodeHTML = `
                    <div class="${itemClass}">
                        <h2 class="accordion-header">
                            ${buttonOrSpan}
                            <div class="actions-bar">
                                <button class="btn btn-link" onclick="showPersonDetails(${person.id})">التفاصيل</button>
                                <a href="/user-profile?id=${person.id}" class="btn btn-link">ملف</a>
                            </div>
                        </h2>
                        ${hasChildren ? `
                                <div id="collapse_${uniqueId}" class="accordion-collapse collapse" data-bs-parent="#${parentId}">
                                    <div class="accordion-body">
                                        <div class="accordion" id="tree_level_${level + 1}"></div>
                                    </div>
                                </div>` : ''}
                    </div>`;
                return nodeHTML;
            }

            // --- Load Children Logic ---
            window.loadChildren = async (buttonElement) => {
                if (buttonElement.dataset.loaded === 'true') return;
                const personId = buttonElement.dataset.personId;
                const level = parseInt(buttonElement.dataset.level);
                const collapseTargetId = buttonElement.dataset.bsTarget;
                const childrenContainer = document.querySelector(`${collapseTargetId} .accordion`);
                if (!childrenContainer) return;

                childrenContainer.innerHTML = `<div class="loader">جاري التحميل...</div>`;
                const data = await fetchFromAPI(`/person/${personId}/children`);

                if (data && data.children && data.children.length > 0) {
                    childrenContainer.innerHTML = '';
                    data.children.forEach(child => {
                        childrenContainer.innerHTML += createPersonNode(child, level);
                    });
                } else {
                    childrenContainer.innerHTML = `<div class="loader">لا يوجد أبناء.</div>`;
                }
                buttonElement.dataset.loaded = 'true';
            };

            // --- Modal Details Logic ---
            window.showPersonDetails = async (personId) => {
                const modalElement = document.getElementById('personDetailModal');
                const modalBody = document.getElementById('modalBodyContent');
                const personModal = new bootstrap.Modal(modalElement);
                personModal.show();

                modalBody.innerHTML =
                    `<div class="text-center p-5"><div class="spinner-border text-success"></div><p class="mt-2">جاري تحميل التفاصيل...</p></div>`;

                const data = await fetchFromAPI(`/person/${personId}`);
                if (!data || !data.person) {
                    modalBody.innerHTML =
                    `<div class="alert alert-danger">فشل في تحميل بيانات الشخص.</div>`;
                    return;
                }

                const person = data.person;
                const age = person.birth_date && !person.death_date ? new Date().getFullYear() - new Date(
                    person.birth_date).getFullYear() : null;

                const createDetailRow = (icon, label, value) => !value ? '' : `
                    <div class="detail-row">
                        <i class="fas ${icon} fa-fw mx-2"></i>
                        <div>
                            <small class="text-muted">${label}</small>
                            <p class="mb-0 fw-bold">${value}</p>
                        </div>
                    </div>`;

                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${person.photo_url || '/images/male.png'}" class="profile-pic" onerror="this.onerror=null;this.src='/images/male.png';">
                            <h4 class="mt-3">${person.full_name}</h4>
                            <p class="text-muted">${person.parent_name ? `ابن: ${person.parent_name}` : 'الجيل الأول'}</p>
                        </div>
                        <div class="col-md-8">
                            ${createDetailRow('fa-birthday-cake', 'تاريخ الميلاد', person.birth_date)}
                            ${age ? createDetailRow('fa-calendar-alt', 'العمر', `${age} سنة`) : ''}
                            ${createDetailRow('fa-briefcase', 'المهنة', person.occupation)}
                            ${person.death_date ? createDetailRow('fa-dove', 'تاريخ الوفاة', person.death_date) : ''}
                        </div>
                    </div>
                `;
            };

            // --- Initial Load ---
            async function loadInitialTree() {
                const data = await fetchFromAPI('/family-tree');
                if (data && data.tree) {
                    treeContainer.innerHTML = `<div id="tree_level_0"></div>`;
                    const firstLevelContainer = treeContainer.querySelector('#tree_level_0');
                    data.tree.forEach(person => {
                        firstLevelContainer.innerHTML += createPersonNode(person, 0);
                    });
                } else {
                    treeContainer.innerHTML =
                        '<div class="alert alert-danger text-center">خطأ في تحميل شجرة العائلة.</div>';
                }
            }

            loadInitialTree();
        });
    </script>
</body>

</html>
