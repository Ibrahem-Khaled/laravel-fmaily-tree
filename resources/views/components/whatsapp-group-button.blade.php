{{-- مكون زر WhatsApp العائم لإرسال رسائل جماعية --}}
<div class="whatsapp-group-button-wrapper">
    {{-- الزر العائم --}}
    <button class="whatsapp-float-btn" id="whatsappGroupBtn" title="إرسال رسالة جماعية عبر WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="btn-badge" id="selectedCountBadge" style="display: none;">0</span>
    </button>

    {{-- Modal للاختيار --}}
    <div class="modal fade" id="whatsappGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fab fa-whatsapp me-2"></i>
                        إرسال رسالة جماعية عبر WhatsApp
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- خيارات الاختيار --}}
                    <div class="selection-options mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <button type="button" class="option-btn active" data-option="generation">
                                    <i class="fas fa-layer-group"></i>
                                    <span>اختيار جيل</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="option-btn" data-option="person">
                                    <i class="fas fa-user"></i>
                                    <span>اختيار شخص</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="option-btn" data-option="children">
                                    <i class="fas fa-child"></i>
                                    <span>أبناء شخص</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="option-btn" data-option="descendants">
                                    <i class="fas fa-sitemap"></i>
                                    <span>أحفاد شخص</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- محتوى الاختيار الديناميكي --}}
                    <div id="selectionContent">
                        {{-- سيتم ملؤه ديناميكياً --}}
                    </div>

                    {{-- قائمة الأشخاص المختارين --}}
                    <div id="selectedPersonsList" class="selected-persons-list" style="display: none;">
                        <h6 class="mb-3">
                            <i class="fas fa-users me-2"></i>
                            الأشخاص المختارون (<span id="selectedCount">0</span>)
                        </h6>
                        <div id="selectedPersonsContainer" class="selected-persons-container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-success" id="sendWhatsAppBtn" disabled>
                        <i class="fab fa-whatsapp me-2"></i>
                        إرسال عبر WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .whatsapp-group-button-wrapper {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 1070 !important;
    }

    /* التأكد من أن الزر يظهر فوق أي modal */
    body.modal-open .whatsapp-group-button-wrapper {
        z-index: 1070 !important;
    }

    #whatsappGroupModal {
        z-index: 1060 !important;
    }

    #whatsappGroupModal .modal-backdrop {
        z-index: 1059 !important;
    }

    /* التأكد من أن modal WhatsApp يظهر فوق modal الشخص */
    body.modal-open #whatsappGroupModal {
        z-index: 1060 !important;
    }

    body.modal-open #whatsappGroupModal ~ .modal-backdrop {
        z-index: 1059 !important;
    }

    #whatsappGroupModal .modal-dialog {
        pointer-events: auto;
    }

    #whatsappGroupModal .modal-content {
        pointer-events: auto;
    }

    #whatsappGroupModal .modal-body {
        pointer-events: auto;
    }

    #whatsappGroupModal .modal-header {
        pointer-events: auto;
    }

    #whatsappGroupModal .modal-footer {
        pointer-events: auto;
    }

    #whatsappGroupModal button,
    #whatsappGroupModal select,
    #whatsappGroupModal input {
        pointer-events: auto !important;
        cursor: pointer;
    }

    .whatsapp-float-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
        transition: all 300ms cubic-bezier(0.22, 1, 0.36, 1);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .whatsapp-float-btn:hover {
        transform: translateY(-4px) scale(1.1);
        box-shadow: 0 12px 32px rgba(37, 211, 102, 0.5);
    }

    .whatsapp-float-btn:active {
        transform: translateY(-2px) scale(1.05);
    }

    .whatsapp-float-btn .btn-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        border: 2px solid white;
    }

    .selection-options .option-btn {
        width: 100%;
        padding: 1rem;
        border: 2px solid #eef2f1;
        border-radius: 16px;
        background: #fff;
        color: var(--dark-green);
        font-weight: 600;
        cursor: pointer;
        transition: all 200ms cubic-bezier(0.22, 1, 0.36, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        pointer-events: auto;
        position: relative;
        z-index: 1;
    }

    .selection-options .option-btn i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .selection-options .option-btn:hover {
        border-color: var(--primary-color);
        background: var(--light-green);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(55, 160, 92, 0.15);
    }

    .selection-options .option-btn.active {
        border-color: var(--primary-color);
        background: var(--gradient-primary);
        color: white;
    }

    .selection-options .option-btn.active i {
        color: white;
    }

    .selection-content {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    .generation-selector,
    .person-selector {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .generation-selector label,
    .person-selector label {
        font-weight: 600;
        color: var(--dark-green);
        margin-bottom: 0.5rem;
    }

    .generation-selector select,
    .person-selector select {
        padding: 0.75rem;
        border: 2px solid #eef2f1;
        border-radius: 12px;
        font-size: 1rem;
        background: white;
        color: var(--dark-green);
        transition: all 200ms;
        pointer-events: auto;
        cursor: pointer;
        position: relative;
        z-index: 1;
        width: 100%;
    }

    .generation-selector select option,
    .person-selector select option {
        padding: 0.5rem;
        white-space: normal;
        word-wrap: break-word;
    }

    .generation-selector select:focus,
    .person-selector select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(55, 160, 102, 0.1);
    }

    .selected-persons-list {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #eef2f1;
    }

    .selected-persons-container {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .selected-person-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        background: white;
        border: 2px solid #eef2f1;
        border-radius: 12px;
        transition: all 200ms;
    }

    .selected-person-item:hover {
        border-color: var(--primary-color);
        background: var(--light-green);
    }

    .selected-person-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }

    .selected-person-info img,
    .selected-person-info .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .selected-person-info .avatar-placeholder {
        background: var(--light-green);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1rem;
    }

    .selected-person-details {
        flex: 1;
        min-width: 0; /* يسمح للنص بالانحناء */
    }

    .selected-person-details strong {
        display: block;
        color: var(--dark-green);
        font-size: 0.9rem;
        word-wrap: break-word;
        white-space: normal;
        line-height: 1.4;
    }

    .selected-person-details small {
        color: #666;
        font-size: 0.75rem;
    }

    .selected-person-phone {
        color: #25D366;
        font-weight: 600;
        font-size: 0.85rem;
        margin-left: 0.5rem;
    }

    .remove-person-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 8px;
        transition: all 200ms;
        pointer-events: auto !important;
        position: relative;
        z-index: 10;
    }

    .remove-person-btn:hover {
        background: #fee;
        transform: scale(1.1);
    }

    .remove-person-btn:active {
        transform: scale(0.95);
    }

    .modal-footer .btn-success,
    .modal-footer .btn-secondary {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        pointer-events: auto;
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .modal-footer .btn-secondary {
        background: #6c757d;
    }

    .modal-footer .btn-success:hover,
    .modal-footer .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.3);
    }

    .modal-footer .btn-success:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .whatsapp-group-button-wrapper {
            bottom: 20px;
            left: 20px;
        }

        .whatsapp-float-btn {
            width: 55px;
            height: 55px;
            font-size: 24px;
        }

        .selection-options .option-btn {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .selection-options .option-btn i {
            font-size: 1.25rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '/api';
    const whatsappBtn = document.getElementById('whatsappGroupBtn');
    const whatsappModalEl = document.getElementById('whatsappGroupModal');

    if (!whatsappModalEl) {
        console.error('WhatsApp modal element not found');
        return;
    }

    const whatsappModal = new bootstrap.Modal(whatsappModalEl, {
        backdrop: true,
        keyboard: true,
        focus: true
    });

    // متغير لتخزين حالة modal الشخص قبل فتح modal WhatsApp
    let wasPersonModalOpen = false;

    // إغلاق modal الشخص عند فتح modal WhatsApp
    whatsappModalEl.addEventListener('show.bs.modal', function() {
        // البحث عن modal الشخص وإغلاقه
        const personModalEl = document.getElementById('personDetailModal');
        if (personModalEl) {
            wasPersonModalOpen = personModalEl.classList.contains('show');
            const personModalInstance = bootstrap.Modal.getInstance(personModalEl);
            if (personModalInstance && wasPersonModalOpen) {
                personModalInstance.hide();
            }
        }

        // إزالة أي backdrop قديم
        setTimeout(() => {
            const oldBackdrops = document.querySelectorAll('.modal-backdrop');
            oldBackdrops.forEach(backdrop => {
                // الاحتفاظ فقط بـ backdrop الخاص بـ modal WhatsApp
                const backdropZIndex = parseInt(window.getComputedStyle(backdrop).zIndex);
                if (backdropZIndex < 1059) {
                    backdrop.remove();
                }
            });
        }, 100);
    });

    // تنظيف عند إغلاق modal WhatsApp
    whatsappModalEl.addEventListener('hidden.bs.modal', function() {
        // إزالة backdrop الخاص بـ modal WhatsApp
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());

        // تنظيف body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        wasPersonModalOpen = false;
    });

    const selectionContent = document.getElementById('selectionContent');
    const selectedPersonsList = document.getElementById('selectedPersonsList');
    const selectedPersonsContainer = document.getElementById('selectedPersonsContainer');
    const selectedCount = document.getElementById('selectedCount');
    const selectedCountBadge = document.getElementById('selectedCountBadge');
    const sendWhatsAppBtn = document.getElementById('sendWhatsAppBtn');

    let selectedPersons = [];
    let currentOption = 'generation';

    // فتح Modal عند الضغط على الزر
    if (whatsappBtn) {
        whatsappBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            try {
                console.log('Opening WhatsApp modal...');

                // إغلاق أي modal مفتوح أولاً
                const openModals = document.querySelectorAll('.modal.show');
                openModals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance && modal.id !== 'whatsappGroupModal') {
                        modalInstance.hide();
                    }
                });

                // إزالة backdrop القديم إن وجد
                const oldBackdrops = document.querySelectorAll('.modal-backdrop');
                oldBackdrops.forEach(backdrop => backdrop.remove());

                // إزالة class modal-open من body إن وجد
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // انتظار قليل قبل فتح modal WhatsApp
                setTimeout(() => {
                    whatsappModal.show();
                    // انتظار قليل للتأكد من أن الـ modal فتح
                    setTimeout(() => {
                        loadInitialContent();
                    }, 300);
                }, 100);
            } catch (error) {
                console.error('Error showing modal:', error);
                alert('حدث خطأ في فتح النافذة. يرجى المحاولة مرة أخرى.');
            }
        });
    } else {
        console.error('WhatsApp button not found');
    }

    // استخدام event delegation للأزرار داخل الـ modal
    whatsappModalEl.addEventListener('click', function(e) {
        // معالجة أزرار الخيارات
        if (e.target.closest('.option-btn')) {
            const btn = e.target.closest('.option-btn');
            e.preventDefault();
            e.stopPropagation();

            const allBtns = whatsappModalEl.querySelectorAll('.option-btn');
            allBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentOption = btn.dataset.option;
            loadSelectionContent(currentOption);
        }

        // معالجة زر الإغلاق
        if (e.target.closest('[data-bs-dismiss="modal"]')) {
            e.preventDefault();
            e.stopPropagation();
            whatsappModal.hide();
        }
    });

    // تحميل المحتوى الأولي
    function loadInitialContent() {
        loadSelectionContent('generation');
    }

    // تحميل محتوى الاختيار حسب النوع
    async function loadSelectionContent(option) {
        selectionContent.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success" role="status"></div></div>';

        let html = '';

        switch(option) {
            case 'generation':
                html = await loadGenerationSelector();
                break;
            case 'person':
                html = await loadPersonSelector();
                break;
            case 'children':
                html = await loadChildrenSelector();
                break;
            case 'descendants':
                html = await loadDescendantsSelector();
                break;
        }

        selectionContent.innerHTML = html;
        attachEventListeners();
    }

    // تحميل محدد الجيل
    async function loadGenerationSelector() {
        try {
            const response = await fetch(`${API_BASE_URL}/generations`);
            const data = await response.json();

            if (data.success && data.generations) {
                let options = '<option value="">اختر الجيل</option>';
                data.generations.forEach(gen => {
                    options += `<option value="${gen.level}">${gen.label} (${gen.count} شخص)</option>`;
                });

                return `
                    <div class="selection-content">
                        <div class="generation-selector">
                            <label>اختر الجيل:</label>
                            <select id="generationSelect" class="form-select">
                                ${options}
                            </select>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading generations:', error);
        }

        return '<div class="alert alert-warning">فشل تحميل الأجيال</div>';
    }

    // تحميل محدد الشخص (فقط الذين لديهم WhatsApp)
    async function loadPersonSelector() {
        try {
            const response = await fetch(`${API_BASE_URL}/persons/search/whatsapp`);
            const data = await response.json();

            if (data.success && data.persons && data.persons.length > 0) {
                let options = '<option value="">اختر الشخص</option>';
                data.persons.forEach(person => {
                    // استخدام full_name الذي يتضمن سلسلة النسب الكاملة
                    const displayName = person.full_name || person.first_name || 'غير معروف';
                    if (displayName && displayName !== 'غير معروف') {
                        options += `<option value="${person.id}" title="${displayName}">${displayName}</option>`;
                    }
                });

                return `
                    <div class="selection-content">
                        <div class="person-selector">
                            <label>اختر الشخص:</label>
                            <select id="personSelect" class="form-select">
                                ${options}
                            </select>
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        لا يوجد أشخاص لديهم WhatsApp مسجل
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading persons:', error);
            return '<div class="alert alert-danger">فشل تحميل الأشخاص</div>';
        }
    }

    // تحميل محدد أبناء الشخص (فقط الذين لديهم WhatsApp)
    async function loadChildrenSelector() {
        try {
            const response = await fetch(`${API_BASE_URL}/persons/search/whatsapp`);
            const data = await response.json();

            if (data.success && data.persons && data.persons.length > 0) {
                let options = '<option value="">اختر الشخص</option>';
                data.persons.forEach(person => {
                    // استخدام full_name الذي يتضمن سلسلة النسب الكاملة
                    const displayName = person.full_name || person.first_name || 'غير معروف';
                    if (displayName && displayName !== 'غير معروف') {
                        options += `<option value="${person.id}" title="${displayName}">${displayName}</option>`;
                    }
                });

                return `
                    <div class="selection-content">
                        <div class="person-selector">
                            <label>اختر الشخص لعرض أبنائه:</label>
                            <select id="parentSelect" class="form-select">
                                ${options}
                            </select>
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        لا يوجد أشخاص لديهم WhatsApp مسجل
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading persons:', error);
            return '<div class="alert alert-danger">فشل تحميل الأشخاص</div>';
        }
    }

    // تحميل محدد أحفاد الشخص (فقط الذين لديهم WhatsApp)
    async function loadDescendantsSelector() {
        try {
            const response = await fetch(`${API_BASE_URL}/persons/search/whatsapp`);
            const data = await response.json();

            if (data.success && data.persons && data.persons.length > 0) {
                let options = '<option value="">اختر الشخص</option>';
                data.persons.forEach(person => {
                    // استخدام full_name الذي يتضمن سلسلة النسب الكاملة
                    const displayName = person.full_name || person.first_name || 'غير معروف';
                    if (displayName && displayName !== 'غير معروف') {
                        options += `<option value="${person.id}" title="${displayName}">${displayName}</option>`;
                    }
                });

                return `
                    <div class="selection-content">
                        <div class="person-selector">
                            <label>اختر الشخص لعرض أحفاده:</label>
                            <select id="ancestorSelect" class="form-select">
                                ${options}
                            </select>
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        لا يوجد أشخاص لديهم WhatsApp مسجل
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading persons:', error);
            return '<div class="alert alert-danger">فشل تحميل الأشخاص</div>';
        }
    }

    // إرفاق مستمعي الأحداث
    function attachEventListeners() {
        const generationSelect = document.getElementById('generationSelect');
        const personSelect = document.getElementById('personSelect');
        const parentSelect = document.getElementById('parentSelect');
        const ancestorSelect = document.getElementById('ancestorSelect');

        if (generationSelect) {
            generationSelect.addEventListener('change', async function() {
                if (this.value) {
                    await loadPersonsByGeneration(parseInt(this.value));
                }
            });
        }

        if (personSelect) {
            personSelect.addEventListener('change', async function() {
                if (this.value) {
                    await loadPersonWithWhatsApp(parseInt(this.value));
                }
            });
        }

        if (parentSelect) {
            parentSelect.addEventListener('change', async function() {
                if (this.value) {
                    await loadChildrenWithWhatsApp(parseInt(this.value));
                }
            });
        }

        if (ancestorSelect) {
            ancestorSelect.addEventListener('change', async function() {
                if (this.value) {
                    await loadDescendantsWithWhatsApp(parseInt(this.value));
                }
            });
        }
    }

    // تحميل الأشخاص حسب الجيل
    async function loadPersonsByGeneration(level) {
        try {
            const response = await fetch(`${API_BASE_URL}/generation/${level}/whatsapp`);
            const data = await response.json();

            if (data.success && data.persons) {
                selectedPersons = data.persons.filter(p => p.whatsapp_numbers && p.whatsapp_numbers.length > 0);
                // التأكد من أن full_name موجود لكل شخص - إلزامي
                selectedPersons = selectedPersons.map(p => {
                    // التحقق من أن full_name موجود وليس فقط first_name
                    if (!p.full_name || p.full_name === p.first_name) {
                        console.warn(`Person ${p.id} has incomplete full_name. first_name: ${p.first_name}, full_name: ${p.full_name}`);
                    }
                    return {
                        ...p,
                        full_name: p.full_name || p.first_name || 'غير معروف'
                    };
                });
                console.log(`Loaded ${selectedPersons.length} persons with WhatsApp for generation ${level}`);
                updateSelectedPersonsList();
            } else {
                alert('لا يوجد أشخاص في هذا الجيل لديهم WhatsApp');
                selectedPersons = [];
                updateSelectedPersonsList();
            }
        } catch (error) {
            console.error('Error loading persons by generation:', error);
            alert('فشل تحميل الأشخاص');
        }
    }

    // تحميل شخص مع WhatsApp
    async function loadPersonWithWhatsApp(personId) {
        try {
            const response = await fetch(`${API_BASE_URL}/person/${personId}/whatsapp`);
            const data = await response.json();

            if (data.success && data.person && data.person.whatsapp_numbers && data.person.whatsapp_numbers.length > 0) {
                // التأكد من أن full_name موجود - إلزامي
                if (!data.person.full_name && data.person.first_name) {
                    console.warn(`Person ${data.person.id} missing full_name, only has first_name: ${data.person.first_name}`);
                }
                const person = {
                    ...data.person,
                    full_name: data.person.full_name || data.person.first_name || 'غير معروف'
                };
                selectedPersons = [person];
                updateSelectedPersonsList();
            } else {
                alert('هذا الشخص لا يملك رقم WhatsApp مسجل');
                selectedPersons = [];
                updateSelectedPersonsList();
            }
        } catch (error) {
            console.error('Error loading person:', error);
            alert('فشل تحميل الشخص');
        }
    }

    // تحميل أبناء شخص مع WhatsApp
    async function loadChildrenWithWhatsApp(personId) {
        try {
            const response = await fetch(`${API_BASE_URL}/person/${personId}/children/whatsapp`);
            const data = await response.json();

            if (data.success && data.persons) {
                selectedPersons = data.persons.filter(p => p.whatsapp_numbers && p.whatsapp_numbers.length > 0);
                // التأكد من أن full_name موجود لكل شخص - إلزامي
                selectedPersons = selectedPersons.map(p => {
                    if (!p.full_name && p.first_name) {
                        console.warn(`Person ${p.id} missing full_name, only has first_name: ${p.first_name}`);
                    }
                    return {
                        ...p,
                        full_name: p.full_name || p.first_name || 'غير معروف'
                    };
                });
                updateSelectedPersonsList();
            }
        } catch (error) {
            console.error('Error loading children:', error);
            alert('فشل تحميل الأبناء');
        }
    }

    // تحميل أحفاد شخص مع WhatsApp
    async function loadDescendantsWithWhatsApp(personId) {
        try {
            const response = await fetch(`${API_BASE_URL}/person/${personId}/descendants/whatsapp`);
            const data = await response.json();

            if (data.success && data.persons) {
                selectedPersons = data.persons.filter(p => p.whatsapp_numbers && p.whatsapp_numbers.length > 0);
                // التأكد من أن full_name موجود لكل شخص - إلزامي
                selectedPersons = selectedPersons.map(p => {
                    if (!p.full_name && p.first_name) {
                        console.warn(`Person ${p.id} missing full_name, only has first_name: ${p.first_name}`);
                    }
                    return {
                        ...p,
                        full_name: p.full_name || p.first_name || 'غير معروف'
                    };
                });
                updateSelectedPersonsList();
            }
        } catch (error) {
            console.error('Error loading descendants:', error);
            alert('فشل تحميل الأحفاد');
        }
    }

    // تحديث قائمة الأشخاص المختارين
    function updateSelectedPersonsList() {
        selectedPersonsContainer.innerHTML = '';

        if (selectedPersons.length === 0) {
            selectedPersonsList.style.display = 'none';
            selectedCountBadge.style.display = 'none';
            sendWhatsAppBtn.disabled = true;
            return;
        }

        selectedPersonsList.style.display = 'block';
        selectedCount.textContent = selectedPersons.length;
        selectedCountBadge.textContent = selectedPersons.length;
        selectedCountBadge.style.display = 'flex';
        sendWhatsAppBtn.disabled = false;

        selectedPersons.forEach((person, index) => {
            const phoneNumbers = person.whatsapp_numbers || [];
            const primaryPhone = phoneNumbers[0] || '';

            const photoHtml = person.photo_url
                ? `<img src="${person.photo_url}" alt="${person.first_name}">`
                : `<div class="avatar-placeholder"><i class="fas ${person.gender === 'female' ? 'fa-female' : 'fa-male'}"></i></div>`;

            // استخدام full_name الذي يتضمن سلسلة النسب الكاملة - إلزامي
            const displayName = person.full_name || person.first_name || 'غير معروف';

            // التأكد من أن الاسم الكامل موجود وليس فقط first_name
            if (!person.full_name && person.first_name) {
                console.warn(`Person ${person.id} missing full_name, only has first_name: ${person.first_name}`);
            }

            const item = document.createElement('div');
            item.className = 'selected-person-item';
            item.innerHTML = `
                <div class="selected-person-info">
                    ${photoHtml}
                    <div class="selected-person-details">
                        <strong title="${displayName}">${displayName}</strong>
                        <small>${person.gender === 'female' ? 'أنثى' : 'ذكر'}</small>
                    </div>
                    ${primaryPhone ? `<span class="selected-person-phone">${primaryPhone}</span>` : ''}
                </div>
                <button type="button" class="remove-person-btn" onclick="removePerson(${index})" title="إزالة">
                    <i class="fas fa-times"></i>
                </button>
            `;
            selectedPersonsContainer.appendChild(item);
        });
    }

    // إزالة شخص من القائمة
    window.removePerson = function(index) {
        selectedPersons.splice(index, 1);
        updateSelectedPersonsList();
    };

    // إرسال رسالة WhatsApp
    if (sendWhatsAppBtn) {
        sendWhatsAppBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (selectedPersons.length === 0) return;

        // جمع جميع أرقام WhatsApp
        const phoneNumbers = [];
        selectedPersons.forEach(person => {
            if (person.whatsapp_numbers && person.whatsapp_numbers.length > 0) {
                person.whatsapp_numbers.forEach(phone => {
                    // تنظيف الرقم من أي أحرف غير رقمية
                    const cleanPhone = phone.replace(/[^0-9]/g, '');
                    if (cleanPhone && !phoneNumbers.includes(cleanPhone)) {
                        phoneNumbers.push(cleanPhone);
                    }
                });
            }
        });

        if (phoneNumbers.length === 0) {
            alert('لا توجد أرقام WhatsApp صالحة');
            return;
        }

        // إنشاء رابط WhatsApp Web للرسالة الجماعية
        // ملاحظة: WhatsApp Web يدعم إرسال رسالة لعدة أرقام عبر رابط واحد
        // لكن الطريقة الأفضل هي فتح كل رقم في تبويب منفصل أو استخدام WhatsApp Business API

        // سنستخدم طريقة فتح رابط لكل رقم (يمكن للمستخدم نسخ الرسالة)
        const message = encodeURIComponent('مرحباً، هذه رسالة من عائلة السريع');

        if (phoneNumbers.length === 1) {
            // رقم واحد - فتح مباشرة
            window.open(`https://wa.me/${phoneNumbers[0]}?text=${message}`, '_blank');
        } else {
            // عدة أرقام - فتح كل رقم في تبويب جديد
            phoneNumbers.forEach((phone, index) => {
                setTimeout(() => {
                    window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
                }, index * 500); // تأخير 500ms بين كل تبويب
            });

            alert(`سيتم فتح ${phoneNumbers.length} محادثة WhatsApp. يرجى نسخ الرسالة لكل محادثة.`);
        }

        whatsappModal.hide();
        });
    }
});
</script>






