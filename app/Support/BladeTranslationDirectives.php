<?php

namespace App\Support;

use Illuminate\Support\Facades\Blade;

class BladeTranslationDirectives
{
    /**
     * تسجيل التوجيهات المخصصة
     */
    public static function register()
    {
        // توجيه لترجمة جنس الشخص
        Blade::directive('personGender', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::personGender($expression); ?>";
        });

        // توجيه لترجمة حالة المقال
        Blade::directive('articleStatus', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::articleStatus($expression); ?>";
        });

        // توجيه لترجمة حالة الرضاعة
        Blade::directive('breastfeedingStatus', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::breastfeedingStatus($expression); ?>";
        });

        // توجيه لترجمة حالة الزواج
        Blade::directive('marriageStatus', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::marriageStatus($expression); ?>";
        });

        // توجيه لترجمة حالة الشارة
        Blade::directive('badgeStatus', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::badgeStatus($expression); ?>";
        });

        // توجيه لترجمة نوع الملف المرفق
        Blade::directive('attachmentType', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::attachmentType($expression); ?>";
        });

        // توجيه لترجمة نوع الفيديو
        Blade::directive('videoProvider', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::videoProvider($expression); ?>";
        });

        // توجيه لترجمة دور المستخدم
        Blade::directive('userRole', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::userRole($expression); ?>";
        });

        // توجيه لترجمة حالة الحساب
        Blade::directive('accountStatus', function ($expression) {
            return "<?php echo App\Support\TranslationHelper::accountStatus($expression); ?>";
        });

        // توجيه لعرض حالة المقال مع اللون
        Blade::directive('articleStatusBadge', function ($expression) {
            return "<?php
                \$statusData = App\Support\TranslationHelper::articleStatusWithColor($expression);
                echo '<span class=\"badge badge-' . \$statusData['color'] . '\"><i class=\"' . \$statusData['icon'] . '\"></i> ' . \$statusData['text'] . '</span>';
            ?>";
        });

        // توجيه لعرض جنس الشخص مع اللون
        Blade::directive('personGenderBadge', function ($expression) {
            return "<?php
                \$genderData = App\Support\TranslationHelper::personGenderWithColor($expression);
                echo '<span class=\"badge badge-' . \$genderData['color'] . '\"><i class=\"' . \$genderData['icon'] . '\"></i> ' . \$genderData['text'] . '</span>';
            ?>";
        });

        // توجيه لعرض حالة الرضاعة مع اللون
        Blade::directive('breastfeedingStatusBadge', function ($expression) {
            return "<?php
                \$statusData = App\Support\TranslationHelper::breastfeedingStatusWithColor($expression);
                echo '<span class=\"badge badge-' . \$statusData['color'] . '\"><i class=\"' . \$statusData['icon'] . '\"></i> ' . \$statusData['text'] . '</span>';
            ?>";
        });

        // توجيه لعرض حالة الزواج مع اللون
        Blade::directive('marriageStatusBadge', function ($expression) {
            return "<?php
                \$statusData = App\Support\TranslationHelper::marriageStatusWithColor($expression);
                echo '<span class=\"badge badge-' . \$statusData['color'] . '\"><i class=\"' . \$statusData['icon'] . '\"></i> ' . \$statusData['text'] . '</span>';
            ?>";
        });

        // توجيه لعرض حالة الشارة مع اللون
        Blade::directive('badgeStatusBadge', function ($expression) {
            return "<?php
                \$statusData = App\Support\TranslationHelper::badgeStatusWithColor($expression);
                echo '<span class=\"badge badge-' . \$statusData['color'] . '\"><i class=\"' . \$statusData['icon'] . '\"></i> ' . \$statusData['text'] . '</span>';
            ?>";
        });

        // توجيه لعرض العمر
        Blade::directive('personAge', function ($expression) {
            return "<?php
                \$person = $expression;
                if (\$person && \$person->birth_date) {
                    \$endDate = \$person->death_date ?? now();
                    \$age = \$person->birth_date->diffInYears(\$endDate);
                    echo \$age . ' سنة';
                } else {
                    echo 'غير محدد';
                }
            ?>";
        });

        // توجيه لعرض الاسم الكامل
        Blade::directive('personFullName', function ($expression) {
            return "<?php
                \$person = $expression;
                echo \$person ? \$person->full_name : 'غير محدد';
            ?>";
        });
    }
}
