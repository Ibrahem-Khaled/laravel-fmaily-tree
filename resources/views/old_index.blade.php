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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100;200;300;400;500;600;700;800;900&family=IBM+Plex+Sans+Arabic:wght@200;300;400;500;600;700&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Readex+Pro:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* Your original custom CSS */
        body {
            background: #fff;
        }

        .tree-section {
            background: linear-gradient(180deg, #DCF2DD 0%, #FFF 100%);
        }

        #tree {
            width: 100%;
            height: 100%;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .accordion-group-item {
            position: relative;
            max-width: 120px;
        }

        .accordion-group-item+.accordion-group-item {
            margin-top: 5px;
        }

        .accordion-body {
            padding: 0 5px 0 0;
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
            align-items: center !important;
            padding: 10px 10px;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-button::after {
            display: none;
        }

        span.accordion-button>span {
            color: #37a05c;
            font-weight: 400;
        }

        span.accordion-button:not(.collapsed) {
            background-color: #fff !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: lightslategray !important;
            color: white !important;
        }

        .accordion-item {
            position: relative;
            min-width: 115px;
        }

        .profile-link {
            width: 100%;
            display: block;
            border-top: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 13px;
            color: #145147;
            background: #f9f9f9;
            box-shadow: 0 3px 50px 0 rgba(0, 0, 0, 0.08);
            border-radius: 0 0 4px 4px;
        }

        .accordion-group-item,
        .accordion-item {
            border: 1px solid #ddd !important;
            border-radius: 4px;
        }

        .accordion-item+.accordion-item {
            margin-top: 5px;
        }

        .accordion-button {
            border-radius: 4px 4px 0 0;
        }

        .wife>img {
            width: 50px !important;
            height: 50px !important;
        }

        .accordion-body .loader {
            text-align: center;
            padding: 20px;
            color: #145147;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @include('partials.header')

    {{-- This section remains the same --}}
    @if (!empty($achievements))
        <section class="achievement d-none" id="achievement">
            {{-- Your achievements code from the original file --}}
        </section>
    @endif

    <section class="tree-section position-relative" style="padding-top: 140px;">
        <div class="container">
            <div>
                <div class="tree-title-sec">
                    <a href="{{ url()->previous() }}" class="back-btn"><img src="{{ asset('images/back.svg') }}"
                            alt="back"></a>
                    <h3>تواصل العائلة</h3>
                </div>
            </div>

            {{-- This is the main container that JavaScript will populate --}}
            <div class="accordion-group p-3" id="familyTreeContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">جاري تحميل شجرة العائلة...</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 📦 Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const API_BASE_URL = '/api'; // Make sure this is your correct API path
            const treeContainer = document.getElementById('familyTreeContainer');

            /**
             * Fetches data safely from the API
             */
            async function fetchFromAPI(endpoint) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`);
                    if (!response.ok) {
                        throw new Error(`API Error: ${response.status}`);
                    }
                    return await response.json();
                } catch (error) {
                    console.error('Failed to fetch from API:', error);
                    return null; // Return null to handle the error in the calling function
                }
            }

            /**
             * Creates the HTML for a single person in the tree
             * @param {object} person - The person's data
             * @param {number} level - The generation level (0 for root, 1 for children, etc.)
             */
            function createPersonNode(person, level = 0) {
                const hasChildren = person.children_count > 0;
                const isWife = person.gender === 'female'; // Assuming 'female' or similar
                const uniqueId = `person_${person.id}_level_${level}`;
                // Use a fixed parent ID for each level to make the accordion work correctly
                const parentId = `tree_level_${level}`;
                const itemClass = (level === 0) ? 'accordion-group-item' : 'accordion-item';

                const buttonOrSpan = hasChildren ?
                    `<button class="accordion-button accordion-group-button d-flex flex-column gap-1 collapsed"
                             type="button"
                             data-bs-toggle="collapse"
                             data-bs-target="#collapse_${uniqueId}"
                             aria-expanded="false"
                             aria-controls="collapse_${uniqueId}"
                             data-person-id="${person.id}"
                             data-level="${level + 1}"
                             onclick="loadChildren(this)">
                        <img src="${person.photo_url || '/images/male.png'}" width="70" height="70" onerror="this.onerror=null;this.src='/images/male.png';">
                    </button>` :
                    `<span class="accordion-button accordion-group-button d-flex flex-column gap-1 collapsed ${isWife ? 'wife' : ''}">
                        <img src="${person.photo_url || '/images/male.png'}" width="70" height="70" onerror="this.onerror=null;this.src='/images/male.png';">
                    </span>`;

                const nodeHTML = `
                    <div class="${itemClass}">
                        <h2 class="accordion-header">
                            ${buttonOrSpan}
                            <a href="/user-profile?id=${person.id}" class="profile-link">
                                <img src="/images/link-icon.svg" alt="">
                                ${person.first_name}
                            </a>
                        </h2>
                        ${hasChildren ? `
                                <div id="collapse_${uniqueId}" class="accordion-collapse collapse" data-bs-parent="#${parentId}">
                                    <div class="accordion-body">
                                        <div class="accordion" id="tree_level_${level + 1}">
                                            </div>
                                    </div>
                                </div>
                            ` : ''}
                    </div>
                `;
                return nodeHTML;
            }

            /**
             * Loads children when a person's button is clicked
             * @param {HTMLElement} buttonElement - The button that was clicked
             */
            window.loadChildren = async (buttonElement) => {
                // Prevent re-fetching data
                if (buttonElement.dataset.loaded === 'true') {
                    return;
                }

                const personId = buttonElement.dataset.personId;
                const level = parseInt(buttonElement.dataset.level);
                const collapseTargetId = buttonElement.dataset.bsTarget;
                const childrenContainer = document.querySelector(`${collapseTargetId} .accordion`);

                if (!childrenContainer) return;

                // Show a loading indicator
                childrenContainer.innerHTML = `<div class="loader">جاري تحميل الأبناء...</div>`;

                const data = await fetchFromAPI(`/person/${personId}/children`);

                if (data && data.children && data.children.length > 0) {
                    childrenContainer.innerHTML = ''; // Clear loader
                    data.children.forEach(child => {
                        childrenContainer.innerHTML += createPersonNode(child, level);
                    });
                } else {
                    childrenContainer.innerHTML = `<div class="loader">لا يوجد أبناء مسجلين.</div>`;
                }

                // Mark as loaded
                buttonElement.dataset.loaded = 'true';
            };

            /**
             * Loads the initial (root) level of the family tree on page load
             */
            async function loadInitialTree() {
                const data = await fetchFromAPI('/family-tree');

                if (data && data.tree) {
                    treeContainer.innerHTML = ''; // Clear initial loader
                    // Set up the container for the first level
                    treeContainer.innerHTML = `<div id="tree_level_0"></div>`;
                    const firstLevelContainer = treeContainer.querySelector('#tree_level_0');

                    data.tree.forEach(person => {
                        firstLevelContainer.innerHTML += createPersonNode(person, 0);
                    });
                } else {
                    treeContainer.innerHTML =
                        '<div class="alert alert-danger text-center">حدث خطأ أثناء تحميل شجرة العائلة. يرجى المحاولة مرة أخرى.</div>';
                }
            }

            // Start the process
            loadInitialTree();
        });
    </script>

</body>

</html>
