<header class="fixed top-0 left-0 right-0 glass-dark z-50">
    <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-4 space-x-reverse">
            <div class="text-white text-2xl font-black tracking-wider">تواصل العائلة</div>
        </div>

        <div class="flex items-center space-x-4 space-x-reverse">
            <a href="{{ route('gallery.index') }}" class="view-btn" title="معرض الصور">
                <i class="fas fa-images fa-fw"></i>
            </a>
            <a href="{{ route('breastfeeding.public.index') }}" class="view-btn" title="الرضاعة">
                <i class="fas fa-baby fa-fw"></i>
            </a>
            <a href="{{ route('dashboard') }}" class="view-btn" title="لوحة التحكم">
                <i class="fas fa-tachometer-alt fa-fw"></i>
            </a>
        </div>

        <div class="glass rounded-full p-1 flex items-center space-x-1 space-x-reverse">
            <button id="btnTraditionalView" class="view-btn active" title="عرض هرمي">
                <i class="fas fa-sitemap fa-fw"></i>
            </button>
            <button id="btnVerticalView" class="view-btn" title="عرض عمودي">
                <i class="fas fa-bars fa-fw"></i>
            </button>
            <a href="{{ route('old.family-tree') }}" class="view-btn" title="عرض النسخة القديمة">
                <i class="fas fa-history fa-fw"></i>
            </a>
        </div>
    </nav>
</header>
