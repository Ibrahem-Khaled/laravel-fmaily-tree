<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        // ✅ تم تعديل منطق الفرز هنا
        // نستخدم orderByRaw لإنشاء منطق فرز مخصص
        // 1. الشرط الأول: يقوم بإنشاء عمود فرز وهمي. إذا كانت قيمة `display_order` تساوي 0،
        //    فإنه يعطيها أولوية أقل (قيمة 1). وإذا كانت لا تساوي 0، يعطيها أولوية أعلى (قيمة 0).
        //    هذا يضمن أن جميع العناصر التي ترتيبها 0 ستأتي في النهاية.
        // 2. الشرط الثاني: يقوم بفرز العناصر ذات الترتيب الفعلي (غير الصفرية) فيما بينها تصاعدياً.
        // 3. الشروط المتبقية: تعمل كعامل حاسم في حال تشابه الترتيب.

        $builder->orderByRaw('CASE WHEN display_order = 0 THEN 1 ELSE 0 END ASC')
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('birth_date', 'asc');
    }
}
