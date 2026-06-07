{{-- ================================================================
FAMILY BRIEF
================================================================ --}}
@if ($familyBrief)
    <section class="py-3 md:py-6 lg:py-8 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-48 h-48 bg-green-100 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">نبذة عن العائلة
                </h2>
            </div>
            <div class="glass-card rounded-2xl p-3 md:p-4 lg:p-6 shadow-lg">
                <div class="text-gray-700 text-sm md:text-base leading-relaxed whitespace-pre-line">
                    {!! nl2br(e($familyBrief)) !!}
                </div>
            </div>
        </div>
    </section>
@endif
