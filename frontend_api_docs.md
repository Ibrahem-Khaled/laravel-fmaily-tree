# توثيق الـ API الخاص بفئات الروابط المهمة (تتطبيقات تهمك)

مرحباً يا بطل الفرونت إند! 👋
تم إجراء تحديث على استجابة الـ API الخاصة بالصفحة الرئيسية (`/api/home`) لدعم ميزة **الفئات (Categories)** للروابط المهمة والتطبيقات.

التعديل تم بشكل **متوافق تماماً مع النسخ السابقة (Backward Compatible)** لتجنب حدوث أي كسر في الكود الحالي، حيث تم الإبقاء على المصفوفة المسطحة القديمة كما هي، وإضافة حقل مهيكل جديد لتسهيل عرض المجموعات.

---

## 1. حقل الاستجابة الجديد `importantLinkCategories`

الآن عند طلب المسار المخصص للصفحة الرئيسية:
`GET /api/home`

ستجد حقلاً جديداً في الاستجابة باسم `importantLinkCategories` يحتوي على قائمة الفئات النشطة مرتبة حسب حقل الفرز (`sort_order`). وتحت كل فئة توجد الروابط الخاصة بها مرتبة أيضاً.

### هيكلية البيانات (JSON Schema)

```json
{
  "success": true,
  "importantLinkCategories": [
    {
      "id": 1,
      "name": "تطبيقات حكومية",
      "description": "منصات وتطبيقات الخدمات الحكومية الرسمية",
      "icon": "fas fa-university",
      "sort_order": 0,
      "is_active": true,
      "links": [
        {
          "id": 5,
          "title": "منصة أبشر",
          "url": "https://www.absher.sa",
          "url_ios": "https://apps.apple.com/sa/app/absher",
          "url_android": "https://play.google.com/store/apps/details?id=sa.gov.nic.absher",
          "type": "app",
          "icon": "fas fa-id-card",
          "description": "تطبيق الخدمات الإلكترونية لوزارة الداخلية",
          "order": 1,
          "is_active": true,
          "open_in_new_tab": true,
          "image_url": "https://example.com/storage/important-links/absher.png",
          "media": []
        }
      ]
    },
    {
      "id": null,
      "name": "روابط عامة",
      "description": "روابط وتطبيقات عامة غير مصنفة",
      "icon": "fas fa-link",
      "sort_order": 999,
      "is_active": true,
      "links": [
        {
          "id": 12,
          "title": "موقع تجريبي",
          "url": "https://example.com",
          "url_ios": null,
          "url_android": null,
          "type": "website",
          "icon": "fas fa-globe",
          "description": "رابط عام غير مصنف تحت أي فئة",
          "order": 5,
          "is_active": true,
          "open_in_new_tab": true,
          "image_url": "https://example.com/storage/important-links/default.png",
          "media": []
        }
      ]
    }
  ],
  "importantLinks": [
    ...
  ]
}
```

> [!TIP]
> **ملاحظة هامة:** 
> الفئة التي تحمل المعرف `id: null` وباسم `"روابط عامة"` هي فئة تلقائية تجمع كافة الروابط النشطة التي **لم يقم المسؤول بإسنادها إلى فئة معينة** (Uncategorized Links). يتم إدراجها دائماً في نهاية المصفوفة.

---

## 2. الإبقاء على حقل `importantLinks` (التوافقية العكسية)
ما زال حقل `importantLinks` القديم متاحاً ويعود بـ **قائمة مسطحة (Flat List)** من جميع الروابط النشطة بدون تقسيم الفئات لمن يعتمد عليها في أجزاء أخرى من الكود أو التطبيقات القديمة.

---

## 3. كيفية البناء والعرض في الفرونت إند (React / Vue / Flutter)

نقترح عليك طريقة العرض التالية لبناء واجهة تفاعلية ممتازة:

### الخيار أ: العرض الرأسي التراكمي (Stacked List) - المطبق حالياً في موقع الويب
عرض عنوان الفئة وتحته شبكة (Grid) من الروابط التابعة لها:

```jsx
// مثال بـ React + TailwindCSS
function ImportantLinksSection({ importantLinkCategories }) {
  return (
    <div className="space-y-12">
      {importantLinkCategories.map(category => (
        <div key={category.id || 'general'} className="bg-white p-6 rounded-2xl shadow-sm">
          {/* رأس الفئة */}
          <h3 className="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <span className="p-2 rounded-xl bg-emerald-50 text-emerald-600">
              <i className={category.icon || 'fas fa-folder'}></i>
            </span>
            {category.name}
          </h3>
          
          {/* شبكة الروابط */}
          <div className="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
            {category.links.map(link => (
              <LinkCard key={link.id} link={link} />
            ))}
          </div>
        </div>
      ))}
    </div>
  );
}
```

### الخيار ب: شريط التبويب الفلتر (Filter Tabs)
توفير شريط تبويبات علوي (Tabs) للتبديل السريع بين الفئات، مما يقلل ارتفاع الصفحة ويمنح إحساساً تفاعلياً عالي الجودة:

1. اعرض التبويب الأول باسم "الكل" (يعرض جميع الروابط من المصفوفة المسطحة `importantLinks`).
2. اعرض بقية التبويبات بأسماء الفئات من مصفوفة `importantLinkCategories`.
3. عند الضغط على تبويب معين، قم بتصفية وعرض الروابط التابعة له فقط.

---

إذا كان لديك أي استفسار أو واجهت مشكلة، لا تتردد في مراجعتي! 🚀
