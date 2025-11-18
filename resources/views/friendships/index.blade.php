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
                        <img src="{{ $person->photo_url }}" alt="{{ $person->full_name }}" 
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
                    <div class="friend-card glass-effect rounded-3xl overflow-hidden green-glow fade-in-up" 
                         style="animation-delay: {{ $loop->index * 0.1 }}s;"
                         onclick="showFriendshipDetails({{ $person->id }}, {{ $friendship->friend->id }}, {{ $friendship->id }})"
                         data-friendship-id="{{ $friendship->id }}">
                        <div class="p-6">
                            <!-- Friend Photo -->
                            <div class="text-center mb-4">
                                <div class="inline-block {{ $friendship->friend->death_date ? 'is-deceased' : '' }}">
                                    <div class="person-photo-container" style="width: 100px; height: 100px;">
                                        @if($friendship->friend->photo_url)
                                            <img src="{{ $friendship->friend->photo_url }}" 
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
                            <h3 class="text-xl font-bold text-center mb-3 text-gray-800 line-clamp-2">
                                {{ $friendship->friend->full_name }}
                            </h3>

                            <!-- Description Preview -->
                            @if($friendship->description)
                                <p class="text-gray-600 text-sm mb-4 description-preview text-center">
                                    {{ $friendship->description }}
                                </p>
                            @endif

                            <!-- View Details Button -->
                            <div class="text-center">
                                <span class="inline-flex items-center gap-2 text-green-600 font-medium text-sm">
                                    <i class="fas fa-eye"></i>
                                    عرض التفاصيل
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
            <div class="modal-content glass-effect" style="border-radius: 20px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, #145147 0%, #37a05c 100%); color: white; border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title">
                        <i class="fas fa-heart me-2"></i>تفاصيل الصداقة
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="friendshipModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-success" role="status">
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
        // Add stagger animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.friend-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease-out';
                    card.style.opacity = '1';
                }, index * 100);
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
                    'photo_url' => $f->friend->photo_url,
                    'birth_date' => $f->friend->birth_date ? $f->friend->birth_date->format('Y-m-d') : null,
                    'death_date' => $f->friend->death_date ? $f->friend->death_date->format('Y-m-d') : null,
                    'occupation' => $f->friend->occupation,
                ],
                'description' => $f->description,
                'friendship_story' => $f->friendship_story,
            ];
        })->values()) !!};

        const personData = {
            id: {{ $person->id }},
            full_name: @json($person->full_name),
            photo_url: @json($person->photo_url),
            gender: @json($person->gender),
            birth_date: @json($person->birth_date ? $person->birth_date->format('Y-m-d') : null),
            death_date: @json($person->death_date ? $person->death_date->format('Y-m-d') : null),
            occupation: @json($person->occupation),
        };

        function showFriendshipDetails(personId, friendId, friendshipId) {
            const friendship = friendshipsData.find(f => f.id === friendshipId);
            if (!friendship) return;

            const modalEl = document.getElementById('friendshipDetailModal');
            const modalBody = document.getElementById('friendshipModalBody');
            const modal = new bootstrap.Modal(modalEl);

            const friend = friendship.friend;
            const isDeceased = !!friend.death_date;
            const personIsDeceased = !!personData.death_date;

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
                <div class="row g-4">
                    <!-- الشخص الأول -->
                    <div class="col-md-6">
                        <div class="text-center p-4 rounded-3xl ${personIsDeceased ? 'is-deceased' : ''}" style="background: rgba(255, 255, 255, 0.5); border-radius: 20px;">
                            ${createPhoto(personData, 'lg')}
                            <h3 class="mt-4 mb-2 font-bold text-xl" style="font-family: 'Amiri', serif;">${personData.full_name}</h3>
                            ${personData.birth_date ? `<p class="text-muted text-sm mb-1"><i class="fas fa-birthday-cake me-1"></i>${personData.birth_date}</p>` : ''}
                            ${personData.occupation ? `<p class="text-muted text-sm"><i class="fas fa-briefcase me-1"></i>${personData.occupation}</p>` : ''}
                        </div>
                    </div>

                    <!-- الصديق -->
                    <div class="col-md-6">
                        <div class="text-center p-4 rounded-3xl ${isDeceased ? 'is-deceased' : ''}" style="background: rgba(255, 255, 255, 0.5); border-radius: 20px;">
                            ${createPhoto(friend, 'lg')}
                            <h3 class="mt-4 mb-2 font-bold text-xl" style="font-family: 'Amiri', serif;">${friend.full_name}</h3>
                            ${friend.birth_date ? `<p class="text-muted text-sm mb-1"><i class="fas fa-birthday-cake me-1"></i>${friend.birth_date}</p>` : ''}
                            ${friend.occupation ? `<p class="text-muted text-sm"><i class="fas fa-briefcase me-1"></i>${friend.occupation}</p>` : ''}
                        </div>
                    </div>
                </div>

                ${friendship.description ? `
                    <div class="mt-4 p-4 rounded-2xl" style="background: rgba(220, 252, 231, 0.5); border-right: 4px solid #37a05c; border-radius: 16px;">
                        <h5 class="mb-3 font-bold">
                            <i class="fas fa-info-circle me-2 text-green-600"></i>نبذة عن الصداقة
                        </h5>
                        <p class="mb-0" style="line-height: 1.8;">${friendship.description}</p>
                    </div>
                ` : ''}

                ${friendship.friendship_story ? `
                    <div class="mt-4 p-4 rounded-2xl" style="background: rgba(248, 249, 250, 0.8); border-right: 4px solid #145147; border-radius: 16px;">
                        <h5 class="mb-3 font-bold">
                            <i class="fas fa-book-open me-2" style="color: #145147;"></i>قصة الصداقة
                        </h5>
                        <div class="mb-0" style="line-height: 1.8; white-space: pre-wrap;">${friendship.friendship_story}</div>
                    </div>
                ` : ''}

                ${!friendship.description && !friendship.friendship_story ? `
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-info-circle fa-3x mb-3 opacity-50"></i>
                        <p>لا توجد معلومات إضافية عن هذه الصداقة</p>
                    </div>
                ` : ''}
            `;

            modal.show();
        }
    </script>
</body>

</html>

