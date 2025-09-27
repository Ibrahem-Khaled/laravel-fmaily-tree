# تطبيق إدارة تواصل العائلة التفاعلية

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![Livewire](https://img.shields.io/badge/Livewire-2.x-green.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-blue.svg)

تطبيق ويب شامل لإدارة تواصل العائلة التفاعلية مبني باستخدام Laravel مع واجهة إدارية قوية وعرض تفاعلي للشجرة.

## ✨ الميزات الرئيسية

- 🌳 **عرض تواصل العائلة التفاعلية** - واجهة بصرية جذابة لاستكشاف العلاقات العائلية
- 👥 **إدارة شاملة للأشخاص** - إضافة وتعديل وحذف معلومات أفراد العائلة
- 🔄 **السحب والإفلات** - إعادة ترتيب الأشخاص في الشجرة بسهولة
- 📊 **الاستيراد والتصدير** - دعم ملفات CSV, Excel, JSON, PDF
- 🎨 **تصميم متجاوب** - يعمل بشكل مثالي على جميع الأجهزة
- 🌐 **دعم اللغة العربية** - واجهة مستخدم باللغة العربية
- 🔐 **نظام مصادقة آمن** - حماية البيانات والوصول المحدود
- ⚡ **أداء عالي** - استخدام نموذج Nested Set للاستعلامات السريِّع ة

## 🚀 التثبيت السريِّع 

### المتطلبات

- PHP 8.1 أو أحدث
- Composer
- Node.js & npm
- SQLite/MySQL/PostgreSQL

### خطوات التثبيت

1. **استنساخ المشروع**
   ```bash
   git clone <repository-url>
   cd family-tree-app
   ```

2. **تثبيت التبعيات**
   ```bash
   composer install
   npm install
   ```

3. **إعداد البيئة**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **إعداد قاعدة البيانات**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   php artisan db:seed
   ```

5. **بناء الأصول**
   ```bash
   npm run build
   ```

6. **تشغيل التطبيق**
   ```bash
   php artisan serve
   ```

الآن يمكنك الوصول للتطبيق على `http://localhost:8000`

## 📖 دليل الاستخدام

### إنشاء حساب جديد

1. انتقل إلى صفحة التسجيل
2. أدخل المعلومات المطلوبة
3. سجل الدخول إلى لوحة التحكم

### إضافة أشخاص جدد

1. من لوحة التحكم، انقر على "إدارة الأشخاص"
2. انقر على "إضافة شخص جديد"
3. املأ المعلومات المطلوبة
4. حدد موقع الشخص في الشجرة

### عرض الشجرة

1. انقر على "عرض تواصل العائلة"
2. استخدم أدوات التكبير والتصغير للتنقل
3. انقر على أي شخص لعرض تفاصيله

### استيراد البيانات

1. انتقل إلى "الاستيراد والتصدير"
2. اختر نوع الملف (CSV, Excel, JSON)
3. حمّل الملف واتبع التعليمات

## 🛠️ التقنيات المستخدمة

- **Backend**: Laravel 10, PHP 8.1+
- **Frontend**: Livewire, Tailwind CSS, Alpine.js
- **Database**: SQLite (افتراضي), MySQL, PostgreSQL
- **Package Management**: Composer, npm
- **Additional Libraries**: 
  - kalnoy/nestedset (إدارة الهيكل الهرمي)
  - maatwebsite/excel (معالجة ملفات Excel)
  - barryvdh/laravel-dompdf (إنشاء ملفات PDF)
  - SortableJS (السحب والإفلات)

## 📁 هيكل المشروع

```
family-tree-app/
├── app/
│   ├── Livewire/          # مكونات Livewire
│   ├── Models/            # نماذج البيانات
│   ├── Exports/           # فئات التصدير
│   └── Imports/           # فئات الاستيراد
├── database/
│   ├── migrations/        # ملفات الهجرة
│   ├── seeders/          # بيانات تجريبية
│   └── factories/        # مصانع البيانات
├── resources/
│   ├── views/            # قوالب العرض
│   └── css/              # ملفات التصميم
└── routes/               # تعريفات المسارات
```

## 🔧 التطوير

### إضافة ميزات جديدة

1. أنشئ migration جديد إذا لزم الأمر:
   ```bash
   php artisan make:migration create_new_table
   ```

2. أنشئ مكون Livewire:
   ```bash
   php artisan make:livewire NewComponent
   ```

3. أضف المسارات المطلوبة في `routes/web.php`

### تشغيل الاختبارات

```bash
php artisan test
```

### تحديث الأصول

```bash
npm run dev    # للتطوير
npm run build  # للإنتاج
```

## 📊 قاعدة البيانات

يستخدم التطبيق نموذج Nested Set لإدارة العلاقات الهرمية بكفاءة:

- `persons` - الجدول الرئيسي للأشخاص
- `users` - جدول المستخدمين والمصادقة
- حقول `_lft`, `_rgt`, `parent_id` لإدارة التسلسل الهرمي

## 🔐 الأمان

- تشفير كلمات المرور باستخدام bcrypt
- حماية CSRF للنماذج
- تنظيف وتحقق من صحة جميع المدخلات
- استخدام Eloquent ORM لمنع SQL Injection

## 📈 الأداء

- استخدام نموذج Nested Set للاستعلامات السريِّع ة
- Eager Loading لتجنب مشكلة N+1
- تخزين مؤقت للبيانات الثابتة
- ضغط وتحسين ملفات CSS/JS

## 🌍 الدعم والمساهمة

### الإبلاغ عن المشاكل

إذا واجهت أي مشاكل، يرجى:
1. التحقق من سجلات الأخطاء في `storage/logs`
2. التأكد من تحديث التبعيات
3. إنشاء issue جديد مع تفاصيل المشكلة

### المساهمة

نرحب بالمساهمات! يرجى:
1. Fork المشروع
2. إنشاء branch جديد للميزة
3. إجراء التغييرات مع الاختبارات
4. إرسال Pull Request

## 📄 الترخيص

هذا المشروع مرخص تحت رخصة MIT. راجع ملف `LICENSE` للتفاصيل.

## 👨‍💻 المطور

تم تطوير هذا المشروع بواسطة **Manus AI** باستخدام أحدث تقنيات تطوير الويب.

---

**ملاحظة**: هذا المشروع تعليمي ويمكن تخصيصه وتطويره حسب الاحتياجات المحددة.

للحصول على التوثيق الكامل، راجع ملف `project-documentation.pdf`.

