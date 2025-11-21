<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أصدقاء {{ $person->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
        }

        h1, h2, h3 {
            font-family: 'Amiri', serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        .green-glow {
            box-shadow: 0 0 20px rgba(55, 160, 92, 0.3);
        }

        .friend-card {
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            cursor: pointer;
        }

        .friend-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(55, 160, 92, 0.25);
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .person-photo-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid white;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .person-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .person-photo-container .icon-placeholder {
            font-size: 3rem;
            color: #37a05c;
        }

        .is-deceased .person-photo-container {
            box-shadow: 0 0 0 3px #1b1b1b, 0 0 0 6px white;
            filter: grayscale(0.3);
        }

        .is-deceased .person-photo-container::after {
            content: "";
            position: absolute;
            inset: auto 8px 8px auto;
            width: 38%;
            height: 6px;
            background: #1b1b1b;
            transform: rotate(-20deg);
            opacity: 0.9;
            border-radius: 4px;
        }

        .mourning-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: linear-gradient(135deg, #000, #2e2e2e);
            color: #fff;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
        }

        .btn-back {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            transition: all 200ms cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 4px 12px rgba(55, 160, 92, 0.3);
        }

        .btn-back:hover {
            transform: translateX(-5px);
            box-shadow: 0 6px 20px rgba(55, 160, 92, 0.4);
            color: white;
        }

        .description-preview {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* تأثيرات من articles.blade.php */
        .friend-card {
            opacity: 0;
            transform: translateY(20px);
        }

        .friend-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .friend-card:hover .person-photo-container {
            transform: scale(1.05);
        }

        .person-photo-container {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* تأثير التوهج الأخضر عند hover */
        .green-glow-hover:hover {
            box-shadow: 0 0 60px rgba(34, 197, 94, 0.5);
        }

        /* شريط التمرير المخصص */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a, #15803d);
        }
    </style>
</head>

<body>
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-8 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-6 fade-in-up">
            <ol class="flex items-center space-x-2 space-x-reverse text-sm">
                <li><a href="{{ route('home') }}" class="text-green-600 hover:text-green-700">الرئيسية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li><a href="{{ route('family-tree') }}" class="text-green-600 hover:text-green-700">تواصل العائلة</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li class="text-gray-600">أصدقاء {{ $person->full_name }}</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="text-center mb-12 fade-in-up">
            <div class="inline-block mb-6 {{ $person->death_date ? 'is-deceased' : '' }}">
                <div class="person-photo-container">
                    @if($person->photo_url)
                        <img src="{{ asset('storage/' . $person->photo_url) }}" alt="{{ $person->full_name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="icon-placeholder" style="display:none;">
                            <i class="fas {{ $person->gender === 'female' ? 'fa-female' : 'fa-male' }}"></i>
                        </div>
                    @else
                        <div class="icon-placeholder">
                            <i class="fas {{ $person->gender === 'female' ? 'fa-female' : 'fa-male' }}"></i>
                        </div>
                    @endif
                    @if($person->death_date)
                        <span class="mourning-badge">
                            في ذمّة الله <i class="fa-solid fa-dove"></i>
                        </span>
                    @endif
                </div>
            </div>
            <h1 class="text-5xl font-black gradient-text mb-4">{{ $person->full_name }}</h1>
            <h2 class="text-3xl font-bold text-gray-700 mb-2">
                <i class="fas fa-user-friends text-green-600 mr-2"></i>
                الأصدقاء
            </h2>
            <p class="text-gray-600 text-lg">عدد الأصدقاء: <span class="font-bold text-green-600">{{ $friendships->count() }}</span></p>
        </div>

        @if($friendships->count() > 0)
            <!-- Friends Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($friendships as $friendship)
                    <div class="friend-card glass-effect rounded-3xl overflow-hidden green-glow-hover fade-in-up"
                         style="animation-delay: {{ $loop->index * 0.1 }}s;"
                         onclick="showFriendshipDetails({{ $friendship->friend->id }}, {{ $friendship->id }})"
                         data-friendship-id="{{ $friendship->id }}">
                        <div class="p-6">
                            <!-- Friend Photo -->
                            <div class="text-center mb-4">
                                <div class="inline-block {{ $friendship->friend->death_date ? 'is-deceased' : '' }}">
                                    <div class="person-photo-container" style="width: 100px; height: 100px;">
                                        @if($friendship->friend->photo_url)
                                            <img src="{{ asset('storage/' . $friendship->friend->photo_url) }}"
                                                 alt="{{ $friendship->friend->full_name }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="icon-placeholder" style="display:none; font-size: 2.5rem;">
                                                <i class="fas {{ $friendship->friend->gender === 'female' ? 'fa-female' : 'fa-male' }}"></i>
                                            </div>
                                        @else
                                            <div class="icon-placeholder" style="font-size: 2.5rem;">
                                                <i class="fas {{ $friendship->friend->gender === 'female' ? 'fa-female' : 'fa-male' }}"></i>
                                            </div>
                                        @endif
                                        @if($friendship->friend->death_date)
                                            <span class="mourning-badge" style="font-size: 0.65rem; padding: 3px 6px;">
                                                <i class="fa-solid fa-dove"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Friend Name -->
                            <h3 class="text-xl font-bold text-center mb-3 text-gray-800 line-clamp-2 hover:text-green-600 transition-colors duration-300">
                                {{ $friendship->friend->full_name }}
                            </h3>

                            <!-- Description Preview -->
                            @if($friendship->description)
                                <p class="text-gray-600 text-sm mb-4 description-preview text-center leading-relaxed">
                                    {{ $friendship->description }}
                                </p>
                            @endif

                            <!-- View Details Button -->
                            <div class="text-center mt-4">
                                <span class="inline-flex items-center gap-2 text-green-600 font-medium text-sm group">
                                    <span>عرض التفاصيل</span>
                                    <i class="fas fa-arrow-left transition-transform duration-300 group-hover:-translate-x-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 fade-in-up">
                <div class="glass-effect rounded-3xl p-12 max-w-md mx-auto">
                    <div class="text-6xl mb-4 opacity-50">
                        <i class="fas fa-user-friends text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">لا يوجد أصدقاء</h3>
                    <p class="text-gray-600 mb-6">لم يتم تسجيل أي أصدقاء لهذا الشخص بعد</p>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="javascript:history.back()" class="btn-back inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                رجوع
            </a>
        </div>
    </div>

        <!-- Modal for Friendship Details -->
        <div class="modal fade" id="friendshipDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content glass-effect" style="border-radius: 24px; border: none; overflow: hidden;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #145147 0%, #37a05c 100%); color: white; border-radius: 24px 24px 0 0; padding: 1.5rem;">
                        <h5 class="modal-title d-flex align-items-center gap-2" style="font-size: 1.5rem; font-weight: bold;">
                            <i class="fas fa-heart"></i>
                            <span>تفاصيل الصداقة</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9;"></button>
                    </div>
                    <div class="modal-body p-4 p-md-5" id="friendshipModalBody" style="background: linear-gradient(135deg, rgba(240, 253, 244, 0.5) 0%, rgba(255, 255, 255, 0.8) 100%);">
                        <div class="text-center py-5">
                            <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="mt-3 text-muted">جاري تحميل التفاصيل...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add stagger animation with Intersection Observer (like articles.blade.php)
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.friend-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '0px 0px -100px 0px'
            });

            cards.forEach(card => {
                observer.observe(card);
            });
        });

        // Friendship data from server
        const friendshipsData = {!! json_encode($friendships->map(function($f) {
            return [
                'id' => $f->id,
                'friend' => [
                    'id' => $f->friend->id,
                    'full_name' => $f->friend->full_name,
                    'first_name' => $f->friend->first_name,
                    'last_name' => $f->friend->last_name,
                    'gender' => $f->friend->gender,
                    'photo_url' => $f->friend->photo_url ? asset('storage/' . $f->friend->photo_url) : null,
                    'birth_date' => $f->friend->birth_date ? $f->friend->birth_date->format('Y-m-d') : null,
                    'death_date' => $f->friend->death_date ? $f->friend->death_date->format('Y-m-d') : null,
                    'occupation' => $f->friend->occupation,
                ],
                'description' => $f->description,
                'friendship_story' => $f->friendship_story,
            ];
        })->values()) !!};

        function showFriendshipDetails(friendId, friendshipId) {
            const friendship = friendshipsData.find(f => f.id === friendshipId);
            if (!friendship) return;

            const modalEl = document.getElementById('friendshipDetailModal');
            const modalBody = document.getElementById('friendshipModalBody');
            const modal = new bootstrap.Modal(modalEl);

            const friend = friendship.friend;
            const isDeceased = !!friend.death_date;

            const createPhoto = (person, size = 'md') => {
                const sizes = {
                    sm: { container: '80px', icon: '2rem' },
                    md: { container: '150px', icon: '4rem' },
                    lg: { container: '180px', icon: '5rem' }
                };
                const currentSize = sizes[size] || sizes['md'];
                const iconClass = person.gender === 'female' ? 'fa-female' : 'fa-male';

                const photoHtml = person.photo_url
                    ? `<img src="${person.photo_url}" alt="${person.full_name}"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                         style="width: ${currentSize.container}; height: ${currentSize.container}; object-fit: cover; border-radius: 50%; border: 4px solid white; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);">`
                    : '';

                const iconHtml = `
                    <div style="display:${person.photo_url ? 'none' : 'flex'}; width: ${currentSize.container}; height: ${currentSize.container}; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 50%; align-items: center; justify-content: center; border: 4px solid white; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);">
                        <i class="fas ${iconClass}" style="font-size: ${currentSize.icon}; color: #37a05c;"></i>
                    </div>`;

                const badgeHtml = person.death_date
                    ? `<span style="position: absolute; top: 8px; right: 8px; background: linear-gradient(135deg, #000, #2e2e2e); color: #fff; font-weight: 700; font-size: 0.75rem; padding: 4px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; z-index: 3; box-shadow: 0 2px 8px rgba(0,0,0,.25);">
                            في ذمّة الله <i class="fa-solid fa-dove"></i>
                       </span>`
                    : '';

                return `
                    <div style="position: relative; display: inline-block; ${person.death_date ? 'filter: grayscale(0.3);' : ''}">
                        ${photoHtml}
                        ${iconHtml}
                        ${badgeHtml}
                    </div>`;
            };

            modalBody.innerHTML = `
                <!-- الصديق فقط -->
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="text-center p-5 rounded-3xl ${isDeceased ? 'is-deceased' : ''}" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(220, 252, 231, 0.5) 100%); border-radius: 24px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);">
                            ${createPhoto(friend, 'lg')}
                            <h3 class="mt-5 mb-3 font-bold text-2xl gradient-text" style="font-family: 'Amiri', serif;">${friend.full_name}</h3>
                            ${friend.birth_date ? `
                                <div class="mb-2">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/70 rounded-full text-sm text-gray-700">
                                        <i class="fas fa-birthday-cake text-green-600"></i>
                                        ${friend.birth_date}
                                    </span>
                                </div>
                            ` : ''}
                            ${friend.occupation ? `
                                <div class="mb-3">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/70 rounded-full text-sm text-gray-700">
                                        <i class="fas fa-briefcase text-green-600"></i>
                                        ${friend.occupation}
                                    </span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>

                ${friendship.description ? `
                    <div class="mt-5 p-5 rounded-2xl" style="background: linear-gradient(135deg, rgba(220, 252, 231, 0.6) 0%, rgba(187, 247, 208, 0.4) 100%); border-right: 4px solid #37a05c; border-radius: 20px; box-shadow: 0 4px 20px rgba(55, 160, 92, 0.15);">
                        <h5 class="mb-4 font-bold text-lg flex items-center gap-2">
                            <i class="fas fa-info-circle text-green-600"></i>
                            <span>نبذة عن الصداقة</span>
                        </h5>
                        <p class="mb-0 text-gray-700" style="line-height: 2; font-size: 1.05rem;">${friendship.description}</p>
                    </div>
                ` : ''}

                ${friendship.friendship_story ? `
                    <div class="mt-4 p-5 rounded-2xl" style="background: linear-gradient(135deg, rgba(248, 249, 250, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%); border-right: 4px solid #145147; border-radius: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                        <h5 class="mb-4 font-bold text-lg flex items-center gap-2">
                            <i class="fas fa-book-open" style="color: #145147;"></i>
                            <span>قصة الصداقة</span>
                        </h5>
                        <div class="mb-0 text-gray-700" style="line-height: 2; white-space: pre-wrap; font-size: 1.05rem;">${friendship.friendship_story}</div>
                    </div>
                ` : ''}

                ${!friendship.description && !friendship.friendship_story ? `
                    <div class="text-center py-8 text-muted">
                        <div class="inline-block p-4 bg-green-50 rounded-full mb-4">
                            <i class="fas fa-info-circle fa-3x text-green-400 opacity-50"></i>
                        </div>
                        <p class="text-lg">لا توجد معلومات إضافية عن هذه الصداقة</p>
                    </div>
                ` : ''}
            `;

            modal.show();
        }
    </script>
</body>

</html>

