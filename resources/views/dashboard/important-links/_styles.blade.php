<style>
    /* CSS Variables for Light / Dark Mode adaptation */
    :root {
        --premium-bg-card: #ffffff;
        --premium-border: #e2e8f0;
        --premium-text-primary: #0f172a;
        --premium-text-secondary: #475569;
        --premium-text-muted: #64748b;
        --premium-bg-hover: #f8fafc;
        --premium-bg-light: #f8fafc;
        --premium-bg-badge-info: #f0f9ff;
        --premium-text-badge-info: #0369a1;
        --premium-border-badge-info: #e0f2fe;
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
        --premium-bg-badge-info: #0c4a6e;
        --premium-text-badge-info: #bae6fd;
        --premium-border-badge-info: #0284c7;
        --premium-input-bg: #15181a;
        --premium-input-border: #3f474e;
    }

    /* Shared Styles */
    .dashboard-container {
        font-family: 'Inter', 'Outfit', 'Cairo', sans-serif;
        align-items: flex-start;
    }

    /* Global Icons Spacing for RTL - ensure they don't stick to text */
    .dashboard-container i.fas,
    .dashboard-container i.fab,
    .dashboard-container i.far {
        margin-left: 8px !important;
        margin-right: 4px !important;
    }

    /* If icon is at the end of text */
    .dashboard-container .btn i.fa-arrow-right,
    .dashboard-container a i.fa-arrow-right {
        margin-left: 0 !important;
        margin-right: 8px !important;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--premium-text-primary);
        letter-spacing: -0.025em;
        text-align: right !important;
    }

    .card-premium {
        border: 1px solid var(--premium-border);
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
        background-color: var(--premium-bg-card);
        overflow: hidden;
        color: var(--premium-text-primary);
    }

    .card-premium .card-header {
        background-color: var(--premium-bg-light);
        border-bottom: 1px solid var(--premium-border);
        padding: 1.25rem 1.5rem;
        color: var(--premium-text-primary);
    }

    .btn-premium-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 0.75rem;
        padding: 0.625rem 1.25rem;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-premium-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }

    .btn-premium-secondary {
        background-color: var(--premium-bg-card);
        color: var(--premium-text-secondary);
        border: 1px solid var(--premium-input-border);
        border-radius: 0.75rem;
        padding: 0.625rem 1.25rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-premium-secondary:hover {
        background-color: var(--premium-bg-hover);
        color: var(--premium-text-primary);
        transform: translateY(-2px);
        border-color: var(--premium-text-muted);
    }

    .btn-premium-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 0.75rem;
        padding: 0.625rem 1.25rem;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-premium-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
    }

    .btn-premium-danger {
        background-color: #fee2e2;
        color: #ef4444;
        border: none;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    body.dark .btn-premium-danger {
        background-color: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .btn-premium-danger:hover {
        background-color: #fecaca;
        color: #dc2626;
        transform: translateY(-2px);
    }

    body.dark .btn-premium-danger:hover {
        background-color: rgba(239, 68, 68, 0.25);
        color: #ef4444;
    }

    .btn-premium-info {
        background-color: #e0f2fe;
        color: #0284c7;
        border: none;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    body.dark .btn-premium-info {
        background-color: rgba(2, 132, 199, 0.15);
        color: #7dd3fc;
    }

    .btn-premium-info:hover {
        background-color: #bae6fd;
        color: #0369a1;
        transform: translateY(-2px);
    }

    body.dark .btn-premium-info:hover {
        background-color: rgba(2, 132, 199, 0.25);
        color: #38bdf8;
    }

    .table-premium {
        margin-bottom: 0;
        background-color: var(--premium-bg-card);
        color: var(--premium-text-primary);
    }

    .table-premium th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: var(--premium-text-muted);
        background-color: var(--premium-bg-light);
        border-top: none;
        border-bottom: 1px solid var(--premium-border);
        padding: 1rem 1.5rem;
        text-align: right !important;
    }

    .table-premium td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--premium-border);
        color: var(--premium-text-secondary);
        text-align: right !important;
    }

    .table-premium tbody tr:last-child td {
        border-bottom: none;
    }

    .table-premium tbody tr {
        transition: all 0.2s ease;
        background-color: var(--premium-bg-card);
    }

    .table-premium tbody tr:hover {
        background-color: var(--premium-bg-hover);
    }

    .sortable-ghost {
        opacity: 0.4;
        background-color: var(--premium-bg-hover) !important;
        border: 2px dashed var(--premium-input-border);
    }

    .sortable-handle {
        cursor: move;
        color: var(--premium-text-muted);
        transition: color 0.2s ease;
    }

    .sortable-handle:hover {
        color: var(--premium-text-primary);
    }

    .custom-switch-premium .custom-control-input:checked~.custom-control-label::before {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
    }

    .custom-switch-premium .custom-control-label {
        padding-right: 2.25rem;
        cursor: pointer;
        color: var(--premium-text-secondary);
    }

    .modal-premium .modal-content {
        border-radius: 1.5rem;
        border: 1px solid var(--premium-border);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.08);
        background-color: var(--premium-bg-card);
        color: var(--premium-text-primary);
    }

    .modal-premium .modal-header {
        border-bottom: 1px solid var(--premium-border);
        padding: 1.5rem;
        background-color: var(--premium-bg-light);
    }

    .modal-premium .modal-body {
        padding: 1.75rem;
    }

    .modal-premium .modal-footer {
        border-top: 1px solid var(--premium-border);
        padding: 1.25rem 1.5rem;
        background-color: var(--premium-bg-light);
    }

    .form-control-premium,
    .form-control {
        border-radius: 0.75rem;
        border: 1px solid var(--premium-input-border);
        padding: 0.75rem 1rem;
        background-color: var(--premium-input-bg) !important;
        color: var(--premium-text-primary) !important;
        transition: all 0.2s ease;
        height: auto;
    }

    .form-control-premium:focus,
    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .preview-box {
        border: 1px solid var(--premium-border);
        border-radius: 1rem;
        background-color: var(--premium-bg-light);
        overflow: hidden;
        transition: all 0.3s ease;
        color: var(--premium-text-primary);
    }

    .preview-box:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
    }

    .form-group label {
        font-weight: 700;
        color: var(--premium-text-secondary);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    /* Stats cards */
    .stat-card-premium {
        border-radius: 1.25rem;
        border: 1px solid var(--premium-border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: var(--premium-bg-card) !important;
    }

    .stat-card-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
    }

    /* Collapsible accordion */
    .accordion-premium .card {
        border: 1px solid var(--premium-border);
        border-radius: 1rem !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        background-color: var(--premium-bg-card);
    }

    .accordion-premium .card-header {
        background-color: var(--premium-bg-card);
        border-bottom: 1px solid var(--premium-border);
        transition: background-color 0.2s ease;
    }

    .accordion-premium .card-header:hover {
        background-color: var(--premium-bg-hover);
    }

    /* Style improvements for better aesthetics */
    .gap-2 {
        gap: 0.5rem !important;
    }

    .gap-3 {
        gap: 1rem !important;
    }

    .rounded-xl {
        border-radius: 1rem !important;
    }

    /* Adapt inputs / selects to dark mode */
    select.form-control option {
        background-color: var(--premium-bg-card);
        color: var(--premium-text-primary);
    }

    .link-item {
        direction: rtl !important;
        text-align: right !important;
    }

    /* Custom elements adapting to dark mode */
    body.dark .link-item,
    body.dark .list-group-item {
        background-color: var(--premium-bg-card) !important;
        border-color: var(--premium-border) !important;
        color: var(--premium-text-primary) !important;
    }

    body.dark .bg-white {
        background-color: var(--premium-bg-card) !important;
    }

    body.dark .bg-light {
        background-color: var(--premium-bg-light) !important;
    }

    body.dark .text-slate-800,
    body.dark .text-slate-900 {
        color: var(--premium-text-primary) !important;
    }

    body.dark .text-slate-500,
    body.dark .text-slate-600,
    body.dark .text-slate-700 {
        color: var(--premium-text-secondary) !important;
    }

    body.dark .text-muted {
        color: var(--premium-text-muted) !important;
    }

    body.dark .border {
        border-color: var(--premium-border) !important;
    }

    /* Ensure icon displays have beautiful glow in dark mode */
    body.dark .bg-emerald-50 {
        background-color: rgba(16, 185, 129, 0.15) !important;
        color: #34d399 !important;
    }

    body.dark .bg-amber-50 {
        background-color: rgba(245, 158, 11, 0.15) !important;
        color: #fbbf24 !important;
    }

    body.dark .bg-sky-50 {
        background-color: rgba(14, 165, 233, 0.15) !important;
        color: #38bdf8 !important;
    }

    body.dark .bg-slate-50 {
        background-color: rgba(148, 163, 184, 0.15) !important;
        color: #cbd5e1 !important;
    }

    /* Custom styles for the form layout reorganization */
    .form-section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #10b981;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(16, 185, 129, 0.15);
        display: flex;
        align-items: center;
        text-align: right !important;
    }

    /* Toggle switch cards inside form */
    .toggle-switch-card {
        background-color: var(--premium-bg-light);
        border: 1px solid var(--premium-border);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        width: 100%;
    }

    .toggle-switch-card:hover {
        border-color: #10b981;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .toggle-switch-card .custom-control-label {
        padding-right: 2.25rem;
        cursor: pointer;
        font-weight: 700;
        color: var(--premium-text-primary);
    }

    /* Custom File Dropzone Area */
    .custom-upload-zone {
        border: 2px dashed var(--premium-input-border);
        border-radius: 1rem;
        padding: 2.5rem 1.5rem;
        text-align: center;
        background-color: var(--premium-bg-light);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--premium-text-secondary);
    }

    .custom-upload-zone:hover {
        border-color: #10b981;
        background-color: var(--premium-bg-hover);
        color: var(--premium-text-primary);
    }

    .custom-upload-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .custom-upload-zone i {
        font-size: 2.5rem;
        color: #10b981;
        margin-bottom: 0.75rem;
    }

    /* Media row item container */
    .media-preview-item {
        background-color: var(--premium-bg-light);
        border: 1px solid var(--premium-border);
        border-radius: 1rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .media-preview-item:hover {
        border-color: #10b981;
    }

    /* Custom RTL Premium Row Styles */
    .link-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        background-color: var(--premium-bg-card) !important;
        border: 1px solid var(--premium-border) !important;
        border-radius: 1.25rem !important;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01) !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        direction: rtl !important;
        text-align: right !important;
    }

    .link-item-row:hover {
        background-color: var(--premium-bg-hover) !important;
        border-color: #10b981 !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
    }

    .link-item-content {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex: 1;
        min-width: 0;
    }

    .link-item-details {
        flex: 1;
        min-width: 0;
    }

    .link-item-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--premium-text-primary);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .link-item-url {
        font-size: 0.85rem;
        color: var(--premium-text-muted);
        margin-bottom: 0.35rem;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        display: block;
        max-width: 100%;
        direction: ltr !important;
        text-align: right !important;
    }

    .link-item-meta {
        font-size: 0.8rem;
        color: var(--premium-text-secondary);
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .link-item-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-right: 1.5rem;
        min-width: max-content;
    }

    /* Override list-group-item spacing for custom rows */
    .links-list-sortable {
        padding: 0.25rem;
    }
    
    .links-list-sortable .link-item {
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        margin-bottom: 0 !important;
    }
</style>