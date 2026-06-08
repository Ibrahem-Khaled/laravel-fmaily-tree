<style>
    /* CSS Variables for Light / Dark Mode adaptation in Quiz/Poll Dashboard */
    :root {
        --primary-gradient: linear-gradient(135deg, #0d9488, #0f766e);
        --secondary-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --hero-gradient: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #115e59 100%);
        --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        --hover-shadow: 0 20px 30px -5px rgba(13, 148, 136, 0.15), 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        --premium-bg-card: #ffffff;
        --premium-border: #e2e8f0;
        --premium-text-primary: #0f172a;
        --premium-text-secondary: #475569;
        --premium-text-muted: #64748b;
        --premium-bg-hover: #f8fafc;
        --premium-bg-light: #f8fafc;
        --premium-input-bg: #ffffff;
        --premium-input-border: #cbd5e1;
    }

    body.dark {
        --premium-bg-card: #1e2124;
        --premium-border: #2d3238;
        --premium-text-primary: #f8fafc;
        --premium-text-secondary: #cbd5e1;
        --premium-text-muted: #94a3b8;
        --premium-bg-hover: #2b3035;
        --premium-bg-light: #25282c;
        --premium-input-bg: #15181a;
        --premium-input-border: #3f474e;
    }

    .modern-title {
        font-weight: 800;
        background: linear-gradient(120deg, #0f766e, #0d9488);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .modern-card {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: var(--card-shadow) !important;
        background: var(--premium-bg-card) !important;
        border: 1px solid var(--premium-border) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow) !important;
    }

    .modern-btn {
        border-radius: 12px !important;
        font-weight: 750 !important;
        padding: 10px 18px !important;
        transition: all 0.2s ease-in-out !important;
        border: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        text-decoration: none !important;
    }

    .modern-btn-primary {
        background: var(--primary-gradient) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.15) !important;
    }
    .modern-btn-primary:hover {
        box-shadow: 0 6px 18px rgba(13, 148, 136, 0.3) !important;
        transform: translateY(-1px);
        color: #fff !important;
    }

    .modern-btn-success {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15) !important;
    }
    .modern-btn-success:hover {
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.3) !important;
        transform: translateY(-1px);
        color: #fff !important;
    }

    .modern-btn-info {
        background: linear-gradient(135deg, #0284c7, #0369a1) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.15) !important;
    }
    .modern-btn-info:hover {
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.3) !important;
        transform: translateY(-1px);
        color: #fff !important;
    }

    .modern-btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15) !important;
    }
    .modern-btn-warning:hover {
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.3) !important;
        transform: translateY(-1px);
        color: #fff !important;
    }

    .modern-btn-dark {
        background: linear-gradient(135deg, #334155, #1e293b) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(51, 65, 85, 0.15) !important;
    }
    .modern-btn-dark:hover {
        box-shadow: 0 6px 18px rgba(51, 65, 85, 0.3) !important;
        transform: translateY(-1px);
        color: #fff !important;
    }

    .modern-btn-secondary {
        background: var(--premium-bg-light) !important;
        color: var(--premium-text-secondary) !important;
        border: 1px solid var(--premium-border) !important;
    }
    .modern-btn-secondary:hover {
        background: var(--premium-bg-hover) !important;
        color: var(--premium-text-primary) !important;
    }

    .modern-label {
        font-weight: 700;
        color: var(--premium-text-primary);
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .modern-input {
        border-radius: 12px !important;
        border: 1.5px solid var(--premium-input-border) !important;
        padding: 10px 16px !important;
        font-size: 0.95rem !important;
        background-color: var(--premium-input-bg) !important;
        color: var(--premium-text-primary) !important;
        transition: all 0.2s ease-in-out !important;
        height: auto !important;
    }
    .modern-input:focus {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1) !important;
        background-color: var(--premium-input-bg) !important;
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 20px !important;
        border: none !important;
        box-shadow: var(--card-shadow) !important;
        background-color: var(--premium-bg-card) !important;
        border: 1px solid var(--premium-border) !important;
        overflow: hidden;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--hover-shadow) !important;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 6px;
        height: 100%;
    }
    .stat-card-primary::before { background: linear-gradient(to bottom, #0f766e, #0d9488); }
    .stat-card-success::before { background: linear-gradient(to bottom, #10b981, #059669); }
    .stat-card-info::before { background: linear-gradient(to bottom, #3b82f6, #2563eb); }

    .stat-icon-wrap, .stat-circle-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stat-icon-primary, .stat-circle-teal { background-color: #e6f7f6; color: #0d9488; }
    .stat-icon-success, .stat-circle-success { background-color: #ecfdf5; color: #10b981; }
    .stat-icon-info, .stat-circle-blue { background-color: #eff6ff; color: #3b82f6; }
    .stat-circle-danger { background-color: #fef2f2; color: #ef4444; }

    /* Tables */
    .modern-table {
        border-collapse: separate !important;
        border-spacing: 0 10px !important;
        width: 100% !important;
        background-color: transparent !important;
    }
    .modern-table thead th {
        border: none !important;
        font-weight: 700 !important;
        font-size: 0.85rem;
        color: var(--premium-text-muted) !important;
        padding: 12px 20px !important;
    }
    .modern-table tbody tr {
        background-color: var(--premium-bg-card) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        border-radius: 14px !important;
        transition: all 0.2s ease !important;
    }
    .modern-table tbody tr:hover {
        background-color: var(--premium-bg-hover) !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04) !important;
    }
    .modern-table tbody td {
        padding: 16px 20px !important;
        border: none !important;
        vertical-align: middle !important;
        color: var(--premium-text-secondary) !important;
    }
    .modern-table tbody td:first-child { border-top-right-radius: 14px !important; border-bottom-right-radius: 14px !important; }
    .modern-table tbody td:last-child { border-top-left-radius: 14px !important; border-bottom-left-radius: 14px !important; }

    /* Badges */
    .badge-modern {
        padding: 6px 12px !important;
        border-radius: 9999px !important;
        font-weight: 700 !important;
        font-size: 0.8rem !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-modern-primary { background-color: #eff6ff !important; color: #1e40af !important; }
    .badge-modern-success { background-color: #ecfdf5 !important; color: #065f46 !important; }
    .badge-modern-info { background-color: #e0f2fe !important; color: #0369a1 !important; }
    .badge-modern-warning { background-color: #fffbeb !important; color: #92400e !important; }
    .badge-modern-teal { background-color: #e6f7f6 !important; color: #0f766e !important; }
    .badge-modern-secondary { background-color: #f1f5f9 !important; color: #475569 !important; }

    /* Action Buttons */
    .btn-action {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px !important;
        margin: 0 4px;
        transition: all 0.2s !important;
        border: none !important;
        font-size: 0.95rem;
    }
    .btn-action-view { background-color: #e0f2fe !important; color: #0284c7 !important; }
    .btn-action-view:hover { background-color: #0284c7 !important; color: #fff !important; transform: scale(1.08); }
    .btn-action-edit { background-color: #fef3c7 !important; color: #d97706 !important; }
    .btn-action-edit:hover { background-color: #d97706 !important; color: #fff !important; transform: scale(1.08); }
    .btn-action-delete { background-color: #fee2e2 !important; color: #dc2626 !important; }
    .btn-action-delete:hover { background-color: #dc2626 !important; color: #fff !important; transform: scale(1.08); }

    .action-btn-sm {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px !important;
        margin: 0 2px;
        transition: all 0.2s !important;
        border: none !important;
        font-size: 0.85rem;
    }
    .action-btn-sm-edit { background-color: #fef3c7 !important; color: #d97706 !important; }
    .action-btn-sm-edit:hover { background-color: #d97706 !important; color: #fff !important; }
    .action-btn-sm-delete { background-color: #fee2e2 !important; color: #dc2626 !important; }
    .action-btn-sm-delete:hover { background-color: #dc2626 !important; color: #fff !important; }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-dot-active { background-color: #10b981; box-shadow: 0 0 8px #10b981; }
    .status-dot-inactive { background-color: #94a3b8; }

    /* Selectable Radio Cards for Answer Type */
    .answer-type-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 12px;
        width: 100%;
    }
    .answer-type-container .custom-radio {
        margin: 0 !important;
        padding: 0 !important;
        position: relative;
    }
    .answer-type-container .custom-control-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }
    .answer-type-container .custom-control-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 16px 12px !important;
        border: 1.5px solid var(--premium-input-border) !important;
        border-radius: 16px !important;
        background-color: var(--premium-bg-card) !important;
        color: var(--premium-text-secondary) !important;
        font-weight: 700 !important;
        width: 100%;
        transition: all 0.2s ease !important;
        cursor: pointer;
        text-align: center;
    }
    .answer-type-container .custom-control-label::before,
    .answer-type-container .custom-control-label::after {
        display: none !important;
    }
    .answer-type-container .custom-control-input:checked + .custom-control-label {
        border-color: #0d9488 !important;
        background-color: #e6f7f6 !important;
        color: #0f766e !important;
        box-shadow: 0 4px 15px rgba(13, 148, 136, 0.12) !important;
    }
    .answer-type-container .custom-control-input:hover + .custom-control-label {
        background-color: var(--premium-bg-hover);
        border-color: var(--premium-input-border);
    }

    /* Custom Switch Overrides */
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #0d9488 !important;
        border-color: #0d9488 !important;
    }
    .custom-control-label {
        padding-right: 12px;
        cursor: pointer;
        font-weight: 700;
        color: var(--premium-text-secondary);
    }
    .custom-switch {
        padding-right: 2.25rem !important;
        padding-left: 0 !important;
    }

    /* Select2 and Summernote styling overrides */
    .select2-container--default .select2-selection--multiple {
        border-radius: 12px !important;
        border: 1.5px solid var(--premium-input-border) !important;
        padding: 6px 12px !important;
        background-color: var(--premium-input-bg) !important;
        min-height: 46px !important;
        transition: all 0.2s !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1) !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e6f7f6 !important;
        color: #0d9488 !important;
        border: 1px solid #a7f3d0 !important;
        border-radius: 8px !important;
        padding: 2px 10px !important;
        font-weight: 600;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #0d9488 !important;
        margin-left: 5px !important;
    }
    
    .note-editor.note-frame {
        border-radius: 16px !important;
        border: 1.5px solid var(--premium-input-border) !important;
        overflow: hidden;
    }
    .note-toolbar {
        background-color: var(--premium-bg-light) !important;
        border-bottom: 1.5px solid var(--premium-border) !important;
    }

    /* Choices List Design */
    .choice-row {
        background-color: var(--premium-bg-light);
        border-radius: 16px;
        padding: 12px;
        border: 1px solid var(--premium-input-border);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    .choice-row:hover {
        border-color: var(--premium-text-muted);
        background-color: var(--premium-bg-hover);
    }
    .choice-row.is-ordering {
        border-right: 4px solid #3b82f6;
    }
    .correct-choice-container {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .media-btn-label {
        border-radius: 10px !important;
        margin-bottom: 0 !important;
        padding: 8px 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-remove-choice {
        width: 38px;
        height: 38px;
        border-radius: 10px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: none !important;
        transition: all 0.2s;
    }
    .btn-remove-choice:hover {
        background-color: #dc2626 !important;
        color: #fff !important;
    }

    /* Winners positions */
    .winner-rank {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.8rem;
    }
    .winner-rank-1 { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .winner-rank-2 { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .winner-rank-3 { background-color: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .winner-rank-other { background-color: var(--premium-bg-light); color: var(--premium-text-muted); border: 1px solid var(--premium-border); }
    
    .sortable-row {
        cursor: grab;
    }
    .sortable-row:active {
        cursor: grabbing;
    }
    .drag-handle {
        color: var(--premium-text-muted);
        font-size: 1.1rem;
        padding-left: 10px;
        cursor: move;
    }
    .sortable-row:hover .drag-handle {
        color: var(--premium-text-primary);
    }

    /* Additional helper classes */
    .action-header-btns {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .info-badge-flat {
        padding: 4px 10px;
        border-radius: 8px;
        background: var(--premium-bg-light);
        border: 1px solid var(--premium-border);
        font-size: 0.85rem;
        color: var(--premium-text-secondary);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .modal-content-modern {
        border: none !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        overflow: hidden;
        background-color: var(--premium-bg-card) !important;
        color: var(--premium-text-primary) !important;
    }
    
    .modal-header-modern {
        border-bottom: 1px solid var(--premium-border) !important;
        background: var(--hero-gradient) !important;
        color: white !important;
        padding: 20px 24px !important;
    }
    
    .modal-body-modern {
        padding: 24px !important;
    }
    
    .modal-close-modern {
        color: white !important;
        opacity: 0.8;
        transition: all 0.2s;
    }
    
    .modal-close-modern:hover {
        opacity: 1;
        transform: scale(1.1);
        color: white !important;
    }

    /* Responses Page Styles */
    .quiz-responses-page {
        direction: rtl;
        text-align: right;
    }
    
    .quiz-responses-hero {
        background: var(--hero-gradient);
        border-radius: 20px;
        padding: 24px 28px;
        box-shadow: 0 10px 30px rgba(13, 148, 136, 0.2);
    }

    .modern-btn-light {
        background-color: #ffffff !important;
        color: #0f766e !important;
    }
    .modern-btn-light:hover {
        background-color: var(--premium-bg-hover) !important;
        transform: translateY(-1px);
    }
    .modern-btn-outline {
        background-color: transparent !important;
        color: #ffffff !important;
        border: 1.5px solid rgba(255, 255, 255, 0.6) !important;
    }
    .modern-btn-outline:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        border-color: #ffffff !important;
    }
    
    .quiz-responses-sidebar {
        border-radius: 20px !important;
        overflow: hidden;
    }
    
    .quiz-question-nav .list-group-item {
        border-bottom: 1px solid var(--premium-border) !important;
        padding: 16px 20px !important;
        transition: all 0.25s ease !important;
        cursor: pointer;
        background-color: var(--premium-bg-card);
        color: var(--premium-text-secondary);
    }
    .quiz-question-nav .list-group-item:last-child {
        border-bottom: none !important;
    }
    .quiz-question-nav .list-group-item:hover {
        background-color: var(--premium-bg-hover) !important;
        padding-right: 24px !important;
    }
    .quiz-question-nav .list-group-item.active {
        background: linear-gradient(to left, #e6f7f6, var(--premium-bg-card)) !important;
        border-right: 4px solid #0d9488 !important;
        color: #0f766e !important;
    }

    body.dark .quiz-question-nav .list-group-item.active {
        background: linear-gradient(to left, rgba(13, 148, 136, 0.15), var(--premium-bg-card)) !important;
    }
    
    .quiz-stat-card {
        border-radius: 20px !important;
        border: 1px solid var(--premium-border) !important;
        box-shadow: var(--card-shadow) !important;
        transition: all 0.2s ease;
        background-color: var(--premium-bg-card) !important;
    }
    .quiz-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow) !important;
    }
    
    .bg-gradient-vote {
        background: linear-gradient(90deg, #0d9488, #10b981) !important;
    }
    
    .survey-item-card {
        border-radius: 16px !important;
        overflow: hidden;
        border: 1px solid var(--premium-border) !important;
        box-shadow: var(--card-shadow) !important;
        background-color: var(--premium-bg-card) !important;
    }
    .survey-item-header {
        background-color: var(--premium-bg-light) !important;
        border-bottom: 1px solid var(--premium-border) !important;
        padding: 16px 20px !important;
    }
    
    .pagination {
        justify-content: center !important;
        gap: 4px;
    }
    .page-item .page-link {
        border-radius: 8px !important;
        border: 1px solid var(--premium-input-border) !important;
        color: var(--premium-text-secondary) !important;
        background-color: var(--premium-bg-card) !important;
        padding: 8px 14px !important;
    }
    .page-item.active .page-link {
        background-color: #0d9488 !important;
        border-color: #0d9488 !important;
        color: white !important;
    }
</style>
