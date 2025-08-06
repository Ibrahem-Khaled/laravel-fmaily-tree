<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل عائلة السريع</title>

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
            overflow-x: auto;
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
            width: 200px;
            border: 1px solid var(--border-color) !important;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .accordion-group-item+.accordion-group-item,
        .accordion-item+.accordion-item {
            margin-top: 10px;
        }

        .accordion-group-item:hover,
        .accordion-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .accordion-collapse {
            position: absolute;
            right: 100%;
            width: 200px;
            top: 0;
            padding-right: 25px;
            z-index: 10;
        }

        .accordion-button {
            border-radius: 12px 12px 0 0 !important;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10px;
        }

        .accordion-button::after {
            display: none;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-button.photo-bg {
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 180px;
            padding: 0.75rem;
            color: #fff !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
        }

        .accordion-button.photo-bg::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.1) 60%);
            border-radius: inherit;
            z-index: 1;
            transition: background 0.3s ease;
        }

        .accordion-button.photo-bg .person-name {
            font-weight: 600;
            z-index: 2;
            color: #fff !important;
            margin-top: auto;
        }

        .accordion-button.photo-bg .person-photo-container {
            display: none;
        }

        .accordion-button.photo-bg .deceased-icon {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .accordion-button:not(.photo-bg) {
            flex-direction: column;
            gap: 10px;
            background-color: #fff;
        }
        
        .accordion-button .person-photo-container {
            width: 120px !important;
            height: 120px !important;
            margin-bottom: 10px;
        }
        .accordion-button .person-photo-container .icon-placeholder {
            font-size: 5rem !important;
        }

        .accordion-button:not(.photo-bg) .person-name {
            color: #333;
            font-weight: 600;
        }

        .accordion-button:not(.collapsed) {
             color: white !important;
        }

        .accordion-button.photo-bg:not(.collapsed) {
             box-shadow: inset 0 0 0 3px var(--dark-green);
             background-color: transparent !important;
        }

        .accordion-button:not(.photo-bg):not(.collapsed) {
            background-color: var(--dark-green) !important;
        }

        .accordion-button:not(.photo-bg):not(.collapsed) .person-name {
            color: #fff;
        }

        .person-photo-container {
            background-color: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 50%;
        }

        .person-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .person-photo-container .icon-placeholder {
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

        .actions-bar {
            width: 100%;
            display: flex;
            border-top: 1px solid var(--border-color);
            background: var(--light-gray);
            border-radius: 0 0 12px 12px;
        }

        .actions-bar .btn {
            flex: 1;
            font-size: 13px;
            padding: 8px 4px;
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

        .modal-header {
            background-color: var(--dark-green);
            color: #fff;
        }

        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        #personDetailModal .person-photo-container {
            border-radius: 12px;
            cursor: zoom-in;
            border: 4px solid var(--light-green);
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
        .child-card,
        .parent-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease-in-out;
        }

        .child-card.clickable,
        .parent-card.clickable {
            cursor: pointer;
        }

        .child-card.clickable:hover,
        .parent-card.clickable:hover {
            background-color: var(--light-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-color: var(--primary-color);
        }

        .spouse-card img,
        .child-card img,
        .parent-card img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }

        .spouse-card .icon-placeholder-sm,
        .child-card .icon-placeholder-sm,
        .parent-card .icon-placeholder-sm {
            font-size: 1.5rem;
            color: var(--primary-color);
            width: 45px;
            height: 45px;
            background-color: var(--light-gray);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #imageZoomModal .modal-content {
            background-color: rgba(0,0,0,0.85);
        }

        #imageZoomModal .btn-close {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1060;
            font-size: 1.5rem;
        }

        #zoomableImage {
            transition: transform 0.2s ease;
            max-width: 90vw;
            max-height: 80vh;
        }

        .zoom-controls {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1060;
        }
        
        @media (max-width: 767px) {
            .tree-section { padding-top: 90px; padding-left: 5px; padding-right: 5px; }
            .accordion-group-item, .accordion-item { width: 105px; }
            .accordion-collapse { width: 105px; padding-right: 10px; }
            .accordion-button .person-photo-container { width: 70px !important; height: 70px !important; margin-bottom: 8px; }
            .accordion-button .person-photo-container .icon-placeholder { font-size: 2.8rem !important; }
            .accordion-button.photo-bg { min-height: 140px; }
            .accordion-button { padding: 8px; }
            .accordion-button .person-name { font-size: 12px; line-height: 1.3; white-space: normal; }
            .actions-bar .btn { font-size: 10px; padding: 5px 2px; }
        }
    </style>
</head>

<body>
    @include('partials.header')
    <section class="tree-section">
        <div class="container">
            <div class="tree-title-sec"><h3>تواصل عائلة السريع</h3></div>
            <div class="p-3">
                <div class="accordion" id="tree_level_0">
                    <div class="text-center py-5">
                        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div>
                        <p class="mt-3 text-muted">جاري تحميل تواصل العائلة...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="personDetailModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">تفاصيل العضو</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body" id="modalBodyContent"></div></div></div></div>
    <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-fullscreen"><div class="modal-content"><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button><div class="modal-body d-flex align-items-center justify-content-center overflow-hidden"><img src="" id="zoomableImage" class="img-fluid"></div><div class="zoom-controls"><button id="zoomOutBtn" class="btn btn-outline-light rounded-circle mx-1"><i class="fas fa-search-minus"></i></button><button id="zoomInBtn" class="btn btn-outline-light rounded-circle mx-1"><i class="fas fa-search-plus"></i></button></div></div></div></div>

    {{-- 📦 Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
     document.addEventListener('DOMContentLoaded', () => {
         const API_BASE_URL = '/api';
         const personDetailModalEl = document.getElementById('personDetailModal');
         const personModal = new bootstrap.Modal(personDetailModalEl);
         const imageZoomModalEl = document.getElementById('imageZoomModal');
         const imageZoomModal = new bootstrap.Modal(imageZoomModalEl);
         const zoomableImage = document.getElementById('zoomableImage');
         const zoomInBtn = document.getElementById('zoomInBtn');
         const zoomOutBtn = document.getElementById('zoomOutBtn');
         let currentScale = 1;

         async function fetchAPI(endpoint) { try { const response = await fetch(`${API_BASE_URL}${endpoint}`); if (!response.ok) throw new Error(`API Error: ${response.status}`); return await response.json(); } catch (error) { console.error('API Fetch Error:', error); return null; } }
         function createPhoto(person, size = 'md') { const sizes = { sm: { container: '45px', icon: '1.5rem' }, md: { container: '80px', icon: '3rem' }, lg: { container: '150px', icon: '6rem' } }; const currentSize = sizes[size]; const iconClass = person.gender === 'female' ? 'fa-female' : 'fa-male'; const iconContainerClass = size === 'sm' ? 'icon-placeholder-sm' : 'icon-placeholder'; let photoHtml = ''; if (person.photo_url) { photoHtml = `<img src="${person.photo_url}" alt="${person.first_name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`; } const iconHtml = `<div class="${iconContainerClass}" style="font-size:${currentSize.icon}; display:${person.photo_url ? 'none' : 'flex'};"><i class="fas ${iconClass}"></i></div>`; const deceasedIconHtml = person.death_date ? `<div class="deceased-icon"><i class="fas fa-dove"></i></div>` : ''; return `<div class="person-photo-container" style="width:${currentSize.container}; height:${currentSize.container};">${photoHtml}${iconHtml}${deceasedIconHtml}</div>`; }
         
         // ✅ [تعديل 1] تم تعديل هذه الدالة لتغيير الـ onclick وإزالة التحكم التلقائي للبوتستراب
         function createPersonNode(person, level = 0) {
             const hasChildren = person.children_count > 0;
             const uniqueId = `person_${person.id}_level_${level}`;
             const itemClass = (level === 0) ? 'accordion-group-item' : 'accordion-item';
             const parentSelector = `#tree_level_${level}`;
             const hasPhoto = person.photo_url;
             const bgClass = hasPhoto ? 'photo-bg' : '';
             const bgStyle = hasPhoto ? `style="background-image: url('${person.photo_url}')"` : '';
             const buttonContent = `${createPhoto(person, 'md')}<span class="person-name">${person.first_name}</span>`;

             const buttonOrDiv = hasChildren ?
                 `<button class="accordion-button collapsed ${bgClass}" type="button" data-bs-target="#collapse_${uniqueId}" onclick="handleAccordionClick(this)" data-person-id="${person.id}" data-level="${level + 1}" ${bgStyle}>
                     ${buttonContent}
                 </button>` :
                 `<div class="accordion-button collapsed ${bgClass}" ${bgStyle}>
                     ${buttonContent}
                 </div>`;
                 
             return `<div class="${itemClass}"><h2 class="accordion-header">${buttonOrDiv}</h2><div class="actions-bar"><button class="btn" onclick="showPersonDetails(${person.id})"><i class="fas fa-info-circle me-1"></i> التفاصيل</button></div>${hasChildren ? `<div id="collapse_${uniqueId}" class="accordion-collapse collapse" data-bs-parent="${parentSelector}"><div class="accordion-body p-0"><div class="accordion" id="tree_level_${level + 1}"></div></div></div>` : ''}</div>`;
         }

        // ✅ [تعديل 2] إضافة دالة جديدة للتحكم الكامل في عملية الفتح والتحميل
        window.handleAccordionClick = async (buttonElement) => {
            const targetSelector = buttonElement.getAttribute('data-bs-target');
            const collapseTarget = document.querySelector(targetSelector);
            if (!collapseTarget) return;
            
            // نصل لمكون الأكورديون الخاص بالبوتستراب يدوياً
            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseTarget);

            // إذا كان الفرع مفتوحاً بالفعل، قم بإغلاقه فقط
            if (buttonElement.classList.contains('collapsed') === false) {
                bsCollapse.hide();
                return;
            }

            // تحقق إذا تم تحميل الأبناء من قبل
            const isLoaded = buttonElement.dataset.loaded === 'true';

            // إذا لم يتم تحميلهم، قم بالتحميل الآن (أولاً)
            if (!isLoaded) {
                const personId = buttonElement.dataset.personId;
                const level = parseInt(buttonElement.dataset.level);
                const childrenContainer = collapseTarget.querySelector('.accordion');
                if (!childrenContainer) return;

                childrenContainer.innerHTML = `<div class="p-2 text-center text-muted small">جاري التحميل...</div>`;
                
                const data = await fetchAPI(`/person/${personId}/children`);
                childrenContainer.innerHTML = '';
                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        childrenContainer.innerHTML += createPersonNode(child, level);
                    });
                } else {
                    childrenContainer.innerHTML = `<div class="p-2 text-center text-muted small">لا يوجد أبناء.</div>`;
                }
                buttonElement.dataset.loaded = 'true';
            }

            // (ثانياً) الآن بعد التأكد من التحميل، أعط أمراً بالفتح
            bsCollapse.show();
        };
        
         // باقي الدوال تبقى كما هي
         window.showPersonDetails = async (personId) => { const modalBody = document.getElementById('modalBodyContent'); personModal.show(); modalBody.innerHTML = `<div class="text-center p-5"><div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div><p class="mt-3">جاري تحميل التفاصيل...</p></div>`; const data = await fetchAPI(`/person/${personId}`); if (!data || !data.person) { modalBody.innerHTML = `<div class="alert alert-danger">فشل تحميل البيانات.</div>`; return; } const person = data.person; const createDetailRow = (icon, label, value) => !value ? '' : `<div class="detail-row"><i class="fas ${icon} fa-fw mx-2"></i><div><small class="text-muted">${label}</small><p class="mb-0 fw-bold">${value}</p></div></div>`; let parentsHtml = ''; if (person.parent || person.mother) { parentsHtml = '<h5>الوالدين</h5><div class="row g-2">'; if (person.parent) { parentsHtml += `<div class="col-md-6"><div class="parent-card clickable" onclick="showPersonDetails(${person.parent.id})"> ${createPhoto(person.parent, 'sm')} <div><strong>${person.parent.first_name}</strong><small class="d-block text-muted">الأب</small></div></div></div>`; } if (person.mother) { parentsHtml += `<div class="col-md-6"><div class="parent-card clickable" onclick="showPersonDetails(${person.mother.id})"> ${createPhoto(person.mother, 'sm')} <div><strong>${person.mother.first_name}</strong><small class="d-block text-muted">الأم</small></div></div></div>`; } parentsHtml += '</div><hr class="my-4">'; } let spousesHtml = ''; if (person.spouses && person.spouses.length > 0) { spousesHtml = '<h5>الزوج/الزوجات</h5><div class="row g-2">'; person.spouses.forEach(spouse => { spousesHtml += `<div class="col-md-6"><div class="spouse-card">${createPhoto(spouse, 'sm')} <div><strong>${spouse.name}</strong></div></div></div>`; }); spousesHtml += '</div><hr class="my-4">'; } let childrenHtml = ''; if (person.children_count > 0) { childrenHtml = `<h5>الأبناء (${person.children_count})</h5><div id="modalChildrenList" class="row g-2"></div>`; } modalBody.innerHTML = `<div class="row g-4"><div class="col-lg-4 text-center"><div class="d-inline-block" onclick="zoomImage(this)">${createPhoto(person, 'lg')}</div><h4 class="mt-3 mb-1">${person.full_name}</h4><p class="text-muted">${person.parent_name ? `ابن/ابنة: ${person.parent_name}` : 'الجيل الأول'}</p>${person.death_date ? `<p class="text-danger fw-bold"><i class="fas fa-dove"></i> متوفى</p>` : `<p class="text-success fw-bold"><i class="fas fa-heart"></i> على قيد الحياة</p>`}</div><div class="col-lg-8">${createDetailRow('fa-birthday-cake', 'تاريخ الميلاد', person.birth_date)}${person.age ? createDetailRow('fa-calendar-alt', 'العمر', `${person.age} سنة`) : ''}${createDetailRow('fa-briefcase', 'المهنة', person.occupation)}${createDetailRow('fa-map-marker-alt', 'مكان الإقامة', person.location)}<hr class="my-4">${parentsHtml}${spousesHtml}${person.biography ? `<h5>سيرة ذاتية</h5><p style="white-space: pre-wrap;">${person.biography}</p><hr class="my-4">` : ''}${childrenHtml}</div></div>`; if (person.children_count > 0) { loadModalChildren(person.id); } };
         async function loadModalChildren(personId) { const childrenContainer = document.getElementById('modalChildrenList'); if (!childrenContainer) return; childrenContainer.innerHTML = `<div class="col-12 text-center text-muted p-3">جاري تحميل الأبناء...</div>`; const data = await fetchAPI(`/person/${personId}/children`); childrenContainer.innerHTML = ''; if (data && data.children && data.children.length > 0) { data.children.forEach(child => { childrenContainer.innerHTML += `<div class="col-md-6"><div class="child-card clickable" onclick="showPersonDetails(${child.id})">${createPhoto(child, 'sm')}<div><strong>${child.first_name}</strong><small class="d-block text-muted">${child.death_date ? 'متوفى/متوفاة' : (child.gender === 'female' ? 'ابنة' : 'ابن')}</small></div></div></div>`; }); } else { childrenContainer.innerHTML = `<div class="col-12 text-center text-muted p-3">لا يوجد أبناء مسجلين.</div>`; } }
         async function loadInitialTree() { const treeContainer = document.getElementById('tree_level_0'); const data = await fetchAPI('/family-tree'); if (data && data.tree && data.tree.length > 0) { treeContainer.innerHTML = ''; data.tree.forEach(person => { treeContainer.innerHTML += createPersonNode(person, 0); }); } else { treeContainer.innerHTML = '<div class="alert alert-warning text-center">لا توجد بيانات لعرضها في تواصل العائلة.</div>'; } }
         window.zoomImage = (container) => { const img = container.querySelector('img'); if (img && img.src) { zoomableImage.src = img.src; imageZoomModal.show(); } };
         zoomInBtn.addEventListener('click', () => { currentScale += 0.2; zoomableImage.style.transform = `scale(${currentScale})`; });
         zoomOutBtn.addEventListener('click', () => { if (currentScale > 0.4) { currentScale -= 0.2; zoomableImage.style.transform = `scale(${currentScale})`; } });
         imageZoomModalEl.addEventListener('hidden.bs.modal', function () { currentScale = 1; zoomableImage.style.transform = 'scale(1)'; zoomableImage.src = ''; });

        // ✅ [تعديل 3] هذا الكود المساعد لا يزال مهماً، وهو يعمل مع الحل الجديد
        // وظيفته: إغلاق الفرع القديم قبل أن يبدأ الجديد في الفتح
        document.addEventListener('show.bs.collapse', function (event) {
            const collapseElement = event.target;
            const parentSelector = collapseElement.getAttribute('data-bs-parent');
            if (!parentSelector) return;
            const parentAccordion = document.querySelector(parentSelector);
            if (!parentAccordion) return;
            const openCollapses = parentAccordion.querySelectorAll('.accordion-collapse.show');
            openCollapses.forEach(openCollapse => {
                if (openCollapse !== collapseElement) {
                    const bsCollapse = bootstrap.Collapse.getInstance(openCollapse);
                    if (bsCollapse) { bsCollapse.hide(); }
                }
            });
        });

         // هذا الكود مسؤول عن التمرير الأفقي الناعم
         document.addEventListener('shown.bs.collapse', function (event) {
             if (!event.target.closest('.tree-section')) return;
             const scrollContainer = document.querySelector('.tree-section');
             const newColumn = event.target;
             setTimeout(() => {
                 const newScrollLeft = scrollContainer.scrollLeft + newColumn.getBoundingClientRect().left - scrollContainer.getBoundingClientRect().left;
                 scrollContainer.scrollTo({ left: newScrollLeft, behavior: 'smooth' });
             }, 50);
         });

         loadInitialTree();
     });
    </script>
</body>
</html>