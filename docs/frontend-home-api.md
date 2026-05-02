# API الصفحة الرئيسية — دليل مبرمج الواجهة (Frontend)

## نظرة عامة

- **المسار:** `GET /api/home`
- **المصدر:** نفس البيانات التي تُبنى لصفحة Blade الرئيسية (`HomePageData::build()`)، مع تنسيق JSON عبر `HomePageApiResource`.
- **مجموعة المسارات:** المسار مسجّل في `routes/web.php` تحت البادئة `api` مع وسيط **`web`** (جلسات Laravel، كوكيز، CSRF للـ POST). ليس مسجّلاً في `routes/api.php`.

## طلبات من المتصفح أو SPA على نفس النطاق

إذا احتجت أن تستلم نفس بيانات «المعاينة» للعناصر غير النشطة (مثل روابط معلّقة للمدراء)، أرسل الطلب مع **credentials** حتى تُرسل كوكي الجلسة:

```ts
fetch('/api/home', { credentials: 'same-origin' })
```

بدون جلسة، يُعاد المحتوى العام (مثل الزائر للصفحة الرئيسية).

## شكل الاستجابة (المفاتيح الرئيسية)

| المفتاح | وصف مختصر |
|---------|-----------|
| `success` | `true` عند النجاح |
| `slides` | شرائح الهيرو (يوازي `$latestImages` في Blade) |
| `gallery` | معرض الصور الرئيسي (يوازي `$latestGalleryImages`) |
| `familyBrief` | نص تعريفي قصير أو `null` |
| `whatsNew` | نص قسم «ما الجديد» من إعدادات المحتوى |
| `courses` | الدورات |
| `programs` | صور/عناصر البرامج (مسطّحة) |
| `program_categories` | البرامج مجمّعة حسب التصنيف `[{ title, programs }]` |
| `proudOf` | قسم نفتخر بـ |
| `councils` | المجالس العائلية |
| `events` | الفعاليات القادمة |
| `birthdayPersons` | أعياد الميلاد اليوم |
| `latestGraduates` | آخر الخريجين (مع `degree_type`, `category_id`, …) |
| `degree_category_ids` | `{ bachelor, master, phd }` معرفات أقسام المقالات في المعرض (اختصار رقمي؛ قد يكون `null`) |
| `degree_categories` | نفس المفاتيح الثلاثة؛ كل قيمة `{ id, name }` حيث `name` اسم قسم المعرض من جدول الأقسام، أو `null` إن لا يوجد مقال خريج لذلك النوع |
| `bachelorTotalCount`, `masterTotalCount`, `phdTotalCount` | أعداد الخريجين حسب الدرجة |
| `importantLinks` | روابط/تطبيقات مهمة (يشمل `url`, `url_ios`, `url_android`, `media`, …) |
| `familyNews` | أخبار العائلة؛ كل عنصر يحتوي `url` مطلق لصفحة التفاصيل |
| `dynamicSections` | الأقسام الديناميكية من لوحة التحكم |
| `quiz` | `{ quizCompetitions, nextQuizEvent, activeQuizCompetitions }` يوازي ما يُمرَّر للقالب |

## تطابق أسماء Blade

- `slides` ≈ `$latestImages`
- `gallery` ≈ `$latestGalleryImages`
- `quiz.quizCompetitions` ≈ `$quizCompetitions`
- `quiz.nextQuizEvent` ≈ `$nextQuizEvent`
- `quiz.activeQuizCompetitions` ≈ `$activeQuizCompetitions`

## بناء روابط الويب من JSON

### صفحة خبر عائلي

استخدم الحقل الجاهز:

- `familyNews[i].url` — رابط مطلق لـ `GET /family-news/{id}`.

### مقالات المعرض حسب نوع الشهادة

الصفحة العامة تفلتر بالمعامل `category` (معرف قسم المقال):

```http
GET /gallery/articles?category={id}
```

استخدم **`degree_categories.{bachelor|master|phd}.id`** أو **`degree_category_ids.{…}`** (نفس المعرف). لعرض عنوان القسم في الواجهة استخدم **`degree_categories.{…}.name`** (مرجع من الـ API دون استعلام إضافي).

قد تكون القيمة بالكامل `null` إن لم يوجد مقال خريج لذلك النوع بعد.

### تطبيقات/روابط مهمة — نفس سلوك الصفحة

لكل عنصر في `importantLinks`:

- `url` — رابط عام (موقع أو صفحة)
- `url_ios` / `url_android` — متاجر التطبيقات عند `type === 'app'`
- `media[]` — وسائط إضافية في النافذة المنبثقة؛ كل عنصر يحتوي `kind`, `file_url`, `title`, …

### اقتراح رابط جديد (مثل نموذج الصفحة)

- **المسار:** `POST /important-links/suggest`
- **النوع:** `multipart/form-data` (للملفات)
- **CSRF:** من نفس النطاق أرسل رمز CSRF (مثلاً من وسم `<meta name="csrf-token">` أو كوكي الجلسة مع الترويسة `X-XSRF-TOKEN` حسب إعدادات Laravel).

إذا كان تطبيق الواجهة على **نطاق مختلف**، ستحتاج تكوين CORS وربما مسار API مخصص أو Sanctum — هذا خارج نطاق هذا الملف.

## CORS ونطاقات مختلفة

عند استهلاك `GET /api/home` من نطاق غير نطاق الـ backend، قد يحظر المتصفح الطلب ما لم تُضبط سياسة CORS والمصادقة (مثلاً Laravel Sanctum مع SPA). راجع توثيق Laravel للـ CORS والـ Sanctum حسب بيئتك.

## ملاحظات تقنية

- الروابط المطلقة في JSON تُبنى من `APP_URL` و`request()` حيث ينطبق ذلك.
- تواريخ الأحداث والأخبار غالباً بصيغة ISO 8601 (`toIso8601String()`).
