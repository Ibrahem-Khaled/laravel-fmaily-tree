<?php

namespace App\Support;

class TranslationHelper
{
    /**
     * ترجمة جنس الشخص
     */
    public static function personGender(string $gender): string
    {
        $genders = [
            'male' => 'ذكر',
            'female' => 'أنثى'
        ];
        return $genders[$gender] ?? $gender;
    }

    /**
     * ترجمة حالة المقال
     */
    public static function articleStatus(string $status): string
    {
        $statuses = [
            'draft' => 'مسودة',
            'published' => 'منشور',
            'archived' => 'مؤرشف'
        ];
        return $statuses[$status] ?? $status;
    }

    /**
     * ترجمة حالة الرضاعة
     */
    public static function breastfeedingStatus(bool $isActive): string
    {
        return $isActive ? 'نشطة' : 'غير نشطة';
    }

    /**
     * ترجمة حالة الزواج
     */
    public static function marriageStatus(string $status): string
    {
        $statuses = [
            'active' => 'نشط',
            'divorced' => 'منفصل',
            'unknown' => 'غير محدد'
        ];
        return $statuses[$status] ?? $status;
    }

    /**
     * ترجمة حالة الشارة
     */
    public static function badgeStatus(bool $isActive): string
    {
        return $isActive ? 'نشطة' : 'غير نشطة';
    }

    /**
     * ترجمة أنواع الملفات المرفقة
     */
    public static function attachmentType(string $mimeType): string
    {
        $types = [
            'image/jpeg' => 'صورة JPEG',
            'image/png' => 'صورة PNG',
            'image/gif' => 'صورة GIF',
            'application/pdf' => 'ملف PDF',
            'application/msword' => 'ملف Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'ملف Word',
            'text/plain' => 'ملف نصي',
            'video/mp4' => 'فيديو MP4',
            'video/avi' => 'فيديو AVI',
            'audio/mp3' => 'ملف صوتي MP3'
        ];
        return $types[$mimeType] ?? 'ملف غير معروف';
    }

    /**
     * ترجمة أنواع الفيديو
     */
    public static function videoProvider(string $provider): string
    {
        $providers = [
            'youtube' => 'يوتيوب',
            'vimeo' => 'فيميو',
            'local' => 'محلي'
        ];
        return $providers[$provider] ?? $provider;
    }

    /**
     * ترجمة أدوار المستخدمين
     */
    public static function userRole(string $role): string
    {
        $roles = [
            'admin' => 'مدير',
            'super_admin' => 'مدير عام',
            'editor' => 'محرر',
            'viewer' => 'مشاهد'
        ];
        return $roles[$role] ?? $role;
    }

    /**
     * ترجمة حالة الحساب
     */
    public static function accountStatus(string $status): string
    {
        $statuses = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'suspended' => 'معلق',
            'pending' => 'في الانتظار'
        ];
        return $statuses[$status] ?? $status;
    }

    /**
     * ترجمة حالة المقال مع اللون
     */
    public static function articleStatusWithColor(string $status): array
    {
        $colors = [
            'draft' => 'warning',
            'published' => 'success',
            'archived' => 'secondary'
        ];

        $icons = [
            'draft' => 'fas fa-edit',
            'published' => 'fas fa-check-circle',
            'archived' => 'fas fa-archive'
        ];

        return [
            'text' => self::articleStatus($status),
            'color' => $colors[$status] ?? 'secondary',
            'icon' => $icons[$status] ?? 'fas fa-question-circle'
        ];
    }

    /**
     * ترجمة جنس الشخص مع اللون
     */
    public static function personGenderWithColor(string $gender): array
    {
        $colors = [
            'male' => 'primary',
            'female' => 'pink'
        ];

        $icons = [
            'male' => 'fas fa-male',
            'female' => 'fas fa-female'
        ];

        return [
            'text' => self::personGender($gender),
            'color' => $colors[$gender] ?? 'secondary',
            'icon' => $icons[$gender] ?? 'fas fa-question-circle'
        ];
    }

    /**
     * ترجمة حالة الرضاعة مع اللون
     */
    public static function breastfeedingStatusWithColor(bool $isActive): array
    {
        return [
            'text' => self::breastfeedingStatus($isActive),
            'color' => $isActive ? 'success' : 'secondary',
            'icon' => $isActive ? 'fas fa-baby' : 'fas fa-baby-slash'
        ];
    }

    /**
     * ترجمة حالة الزواج مع اللون
     */
    public static function marriageStatusWithColor(string $status): array
    {
        $colors = [
            'active' => 'success',
            'divorced' => 'danger',
            'unknown' => 'secondary'
        ];

        $icons = [
            'active' => 'fas fa-heart',
            'divorced' => 'fas fa-heart-broken',
            'unknown' => 'fas fa-question-circle'
        ];

        return [
            'text' => self::marriageStatus($status),
            'color' => $colors[$status] ?? 'secondary',
            'icon' => $icons[$status] ?? 'fas fa-question-circle'
        ];
    }

    /**
     * ترجمة حالة الشارة مع اللون
     */
    public static function badgeStatusWithColor(bool $isActive): array
    {
        return [
            'text' => self::badgeStatus($isActive),
            'color' => $isActive ? 'success' : 'secondary',
            'icon' => $isActive ? 'fas fa-award' : 'fas fa-award'
        ];
    }
}
