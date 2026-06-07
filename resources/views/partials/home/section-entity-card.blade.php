{{--
    بطاقة كيان (مستخدم / شخص) لعرضها في أقسام الصفحة الرئيسية
    يُستقبل: $entity (User|Person), $sourceType (string), $layoutStyle (string)
--}}
@php
    $isPerson = $sourceType === 'App\\Models\\Person';
    $isUser = $sourceType === 'App\\Models\\User';

    if ($isPerson) {
        $name = $entity->full_name ?? ($entity->first_name . ' ' . ($entity->last_name ?? ''));
        $avatar = $entity->avatar;
        $subtitle = $entity->occupation ?? null;
    } elseif ($isUser) {
        $name = $entity->name ?? '';
        $avatar = $entity->avatar ? asset('storage/' . $entity->avatar) : asset('assets/img/avatar-male.png');
        $subtitle = $entity->email ?? null;
    } else {
        $name = $entity->name ?? $entity->title ?? 'عنصر';
        $avatar = null;
        $subtitle = null;
    }

    $cardMinWidth = $layoutStyle === 'horizontal' ? 'min-w-[180px] md:min-w-[220px]' : '';
@endphp

<div class="group glass-card rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden {{ $cardMinWidth }}">
    <div class="relative overflow-hidden" style="height: 160px;">
        <img src="{{ $avatar }}"
             alt="{{ $name }}"
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
             loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
    </div>
    <div class="p-3 md:p-4 text-right">
        <h4 class="font-bold text-sm md:text-base mb-1 truncate">{{ $name }}</h4>
        @if ($subtitle)
            <p class="text-xs text-gray-500 truncate">{{ $subtitle }}</p>
        @endif
    </div>
</div>
