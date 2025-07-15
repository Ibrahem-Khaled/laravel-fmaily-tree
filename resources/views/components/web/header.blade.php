 <header class="fixed top-0 left-0 right-0 glass-dark z-50">
     <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
         <div class="flex items-center space-x-4 space-x-reverse">
             <div class="text-white text-2xl font-black tracking-wider">شجرة العائلة</div>
         </div>

         <div class="flex items-center space-x-2 space-x-reverse">
             <span class="text-white text-sm hidden sm:block">هرمي</span>
             <div class="toggle-switch" id="viewToggle"></div>
             <span class="text-white text-sm hidden sm:block">عمودي</span>
         </div>
         <a href="{{ route('old.family-tree') }}" class="text-white text-sm hover:underline">عرض النسخة القديمة</a>
     </nav>
 </header>
