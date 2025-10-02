<?php

namespace App\Support;

class FamilyTreeStatusHelper
{
    /**
     * ترجمة حالات المقالات إلى العربية
     */
    public static function getArticleStatusTranslations(): array
    {
        return [
            'draft' => 'مسودة',
            'published' => 'منشور',
            'archived' => 'مؤرشف'
        ];
    }

    /**
     * ترجمة حالة المقال إلى العربية
     */
    public static function translateArticleStatus(string $status): string
    {
        $translations = self::getArticleStatusTranslations();
        return $translations[$status] ?? $status;
    }

    /**
     * الحصول على جميع حالات المقالات المتاحة
     */
    public static function getAvailableArticleStatuses(): array
    {
        return array_keys(self::getArticleStatusTranslations());
    }

    /**
     * الحصول على حالات المقالات مع ترجماتها
     */
    public static function getArticleStatusesWithTranslations(): array
    {
        $statuses = [];
        foreach (self::getArticleStatusTranslations() as $key => $value) {
            $statuses[] = [
                'key' => $key,
                'value' => $value,
                'label' => $value
            ];
        }
        return $statuses;
    }

    /**
     * الحصول على لون حالة المقال (للاستخدام في الواجهة)
     */
    public static function getArticleStatusColor(string $status): string
    {
        $colors = [
            'draft' => 'warning',
            'published' => 'success',
            'archived' => 'secondary'
        ];
        return $colors[$status] ?? 'secondary';
    }

    /**
     * الحصول على أيقونة حالة المقال
     */
    public static function getArticleStatusIcon(string $status): string
    {
        $icons = [
            'draft' => 'fas fa-edit',
            'published' => 'fas fa-check-circle',
            'archived' => 'fas fa-archive'
        ];
        return $icons[$status] ?? 'fas fa-question-circle';
    }

    /**
     * الحصول على وصف مفصل لحالة المقال
     */
    public static function getArticleStatusDescription(string $status): string
    {
        $descriptions = [
            'draft' => 'المقال في مرحلة المسودة ولم يتم نشره بعد',
            'published' => 'المقال منشور ومتاح للقراءة',
            'archived' => 'المقال مؤرشف وغير متاح للعرض العام'
        ];
        return $descriptions[$status] ?? 'حالة غير محددة';
    }

    /**
     * التحقق من صحة حالة المقال
     */
    public static function isValidArticleStatus(string $status): bool
    {
        return in_array($status, self::getAvailableArticleStatuses());
    }

    /**
     * الحصول على الحالات التالية الممكنة لحالة المقال الحالية
     */
    public static function getNextPossibleArticleStatuses(string $currentStatus): array
    {
        $workflow = [
            'draft' => ['published', 'archived'],
            'published' => ['archived', 'draft'],
            'archived' => ['draft', 'published']
        ];
        return $workflow[$currentStatus] ?? [];
    }

    /**
     * التحقق من إمكانية الانتقال من حالة مقال إلى أخرى
     */
    public static function canTransitionArticleTo(string $fromStatus, string $toStatus): bool
    {
        return in_array($toStatus, self::getNextPossibleArticleStatuses($fromStatus));
    }

    /**
     * ترجمة جنس الشخص إلى العربية
     */
    public static function translatePersonGender(string $gender): string
    {
        $genders = [
            'male' => 'ذكر',
            'female' => 'أنثى'
        ];
        return $genders[$gender] ?? $gender;
    }

    /**
     * الحصول على لون جنس الشخص
     */
    public static function getPersonGenderColor(string $gender): string
    {
        $colors = [
            'male' => 'primary',
            'female' => 'pink'
        ];
        return $colors[$gender] ?? 'secondary';
    }

    /**
     * الحصول على أيقونة جنس الشخص
     */
    public static function getPersonGenderIcon(string $gender): string
    {
        $icons = [
            'male' => 'fas fa-male',
            'female' => 'fas fa-female'
        ];
        return $icons[$gender] ?? 'fas fa-question-circle';
    }

    /**
     * ترجمة حالة الزواج إلى العربية
     */
    public static function translateMarriageStatus(string $status): string
    {
        $statuses = [
            'active' => 'نشط',
            'divorced' => 'منفصل',
            'unknown' => 'غير محدد'
        ];
        return $statuses[$status] ?? $status;
    }

    /**
     * الحصول على لون حالة الزواج
     */
    public static function getMarriageStatusColor(string $status): string
    {
        $colors = [
            'active' => 'success',
            'divorced' => 'danger',
            'unknown' => 'secondary'
        ];
        return $colors[$status] ?? 'secondary';
    }

    /**
     * الحصول على أيقونة حالة الزواج
     */
    public static function getMarriageStatusIcon(string $status): string
    {
        $icons = [
            'active' => 'fas fa-heart',
            'divorced' => 'fas fa-heart-broken',
            'unknown' => 'fas fa-question-circle'
        ];
        return $icons[$status] ?? 'fas fa-question-circle';
    }

    /**
     * ترجمة حالة الرضاعة إلى العربية
     */
    public static function translateBreastfeedingStatus(bool $isActive): string
    {
        return $isActive ? 'نشطة' : 'غير نشطة';
    }

    /**
     * الحصول على لون حالة الرضاعة
     */
    public static function getBreastfeedingStatusColor(bool $isActive): string
    {
        return $isActive ? 'success' : 'secondary';
    }

    /**
     * الحصول على أيقونة حالة الرضاعة
     */
    public static function getBreastfeedingStatusIcon(bool $isActive): string
    {
        return $isActive ? 'fas fa-baby' : 'fas fa-baby-slash';
    }
}
