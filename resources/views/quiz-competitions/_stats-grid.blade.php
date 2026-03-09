<div class="grid grid-cols-3 gap-3 md:gap-4">
    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
            <i class="fas fa-users text-blue-500 text-lg"></i>
        </div>
        <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        <p class="text-gray-500 text-xs mt-1">إجمالي المشاركين</p>
    </div>
    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center" style="box-shadow:0 0 20px rgba(34,197,94,0.15);">
        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
        </div>
        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['correct'] }}</p>
        <p class="text-gray-500 text-xs mt-1">إجابة صحيحة</p>
    </div>
    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 border border-red-200">
            <i class="fas fa-times-circle text-red-500 text-lg"></i>
        </div>
        <p class="text-2xl md:text-3xl font-bold text-red-500">{{ $stats['wrong'] }}</p>
        <p class="text-gray-500 text-xs mt-1">إجابة خاطئة</p>
    </div>
</div>
