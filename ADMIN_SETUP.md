# إعداد صلاحيات المدير

## نظرة عامة

تم إنشاء نظام صلاحيات احترافي لإدارة المستخدمين في تطبيق شجرة العائلة. النظام يعمل تلقائياً دون الحاجة لكتابة الصلاحيات يدوياً.

## المميزات

### 🔧 **نظام تلقائي**
- إنشاء الصلاحيات تلقائياً من المسارات
- لا حاجة لكتابة الصلاحيات يدوياً
- تحديث تلقائي عند إضافة مسارات جديدة

### 👥 **أدوار متعددة**
- **المدير العام (super_admin)**: جميع الصلاحيات
- **المدير (admin)**: جميع الصلاحيات
- **المحرر (editor)**: صلاحيات محدودة
- **المشاهد (viewer)**: صلاحيات القراءة فقط
- **المستخدم (user)**: صلاحيات أساسية

### 🛡️ **أمان متقدم**
- حماية من حذف المستخدم الحالي
- حماية من حذف آخر مدير
- حماية من حذف المستخدمين ذوي الصلاحيات

## الاستخدام

### 1. تشغيل السيدر الأساسي

```bash
php artisan db:seed --class=AdminPermissionsSeeder
```

### 2. استخدام الأمر المخصص

```bash
# استخدام الإعدادات الافتراضية
php artisan admin:seed-permissions

# تخصيص بيانات المدير
php artisan admin:seed-permissions --email=admin@example.com --password=secure123 --name="مدير النظام"
```

### 3. تشغيل جميع السيدرات

```bash
php artisan db:seed
```

## الإعدادات

يمكن تخصيص إعدادات المدير في ملف `.env`:

```env
ADMIN_EMAIL=admin@familytree.com
ADMIN_PASSWORD=admin123
ADMIN_NAME=مدير النظام
```

أو في ملف `config/app.php`:

```php
'admin_email' => env('ADMIN_EMAIL', 'admin@familytree.com'),
'admin_password' => env('ADMIN_PASSWORD', 'admin123'),
'admin_name' => env('ADMIN_NAME', 'مدير النظام'),
```

## الصلاحيات المتاحة

### إدارة الأشخاص
- `people.view` - عرض الأشخاص
- `people.create` - إضافة أشخاص
- `people.update` - تعديل الأشخاص
- `people.delete` - حذف الأشخاص

### إدارة الزواج
- `marriages.view` - عرض الزواج
- `marriages.create` - إضافة زواج
- `marriages.update` - تعديل الزواج
- `marriages.delete` - حذف الزواج

### إدارة الرضاعة
- `breastfeeding.view` - عرض الرضاعة
- `breastfeeding.create` - إضافة رضاعة
- `breastfeeding.update` - تعديل الرضاعة
- `breastfeeding.delete` - حذف الرضاعة

### إدارة المقالات
- `articles.view` - عرض المقالات
- `articles.create` - إضافة مقالات
- `articles.update` - تعديل المقالات
- `articles.delete` - حذف المقالات

### إدارة الفئات
- `categories.view` - عرض الفئات
- `categories.create` - إضافة فئات
- `categories.update` - تعديل الفئات
- `categories.delete` - حذف الفئات

### إدارة الشارات
- `padges.view` - عرض الشارات
- `padges.create` - إضافة شارات
- `padges.update` - تعديل الشارات
- `padges.delete` - حذف الشارات

### إدارة الصور
- `images.view` - عرض الصور
- `images.upload` - رفع الصور
- `images.delete` - حذف الصور

### إدارة المستخدمين
- `users.view` - عرض المستخدمين
- `users.create` - إضافة مستخدمين
- `users.update` - تعديل المستخدمين
- `users.delete` - حذف المستخدمين

### إدارة الأدوار
- `roles.manage` - إدارة الأدوار

### النظام
- `dashboard.view` - عرض لوحة التحكم
- `system.settings` - إعدادات النظام
- `system.logs.view` - عرض سجلات النظام
- `audit.view` - عرض سجلات التدقيق

## الأمان

### حماية الحذف
- لا يمكن حذف المستخدم الحالي
- لا يمكن حذف آخر مدير في النظام
- لا يمكن حذف المستخدمين الذين لديهم صلاحية حذف المستخدمين

### التحقق من الصلاحيات
- التحقق من الصلاحيات في الكنترولر
- إخفاء الأزرار في الواجهة حسب الصلاحية
- حماية المسارات بـ middleware

## التطوير

### إضافة صلاحيات جديدة
1. أضف middleware للصلاحية في المسار
2. شغل السيدر لإنشاء الصلاحية تلقائياً
3. أضف الصلاحية للدور المناسب

### إضافة أدوار جديدة
1. عدل ملف `AdminPermissionsSeeder.php`
2. أضف الدور الجديد في دالة `createRoles()`
3. حدد الصلاحيات المناسبة للدور

## استكشاف الأخطاء

### مشاكل شائعة
1. **صلاحية غير موجودة**: تأكد من تشغيل السيدر
2. **مستخدم بدون صلاحيات**: تحقق من تعيين الدور
3. **خطأ في الحذف**: تحقق من قواعد الحماية

### أوامر مفيدة
```bash
# عرض جميع الصلاحيات
php artisan permission:show

# عرض جميع الأدوار
php artisan role:show

# إعادة تعيين صلاحيات المستخدم
php artisan user:reset-permissions {user_id}
```

## الدعم

للمساعدة أو الإبلاغ عن مشاكل، يرجى التواصل مع فريق التطوير.
