<?php

/**
 * إزالة contenteditable وكلاس html-preview-editable من HTML (لعناصر القسم من نوع html).
 * يمنع ظهور التعديل المباشر للزوار على الصفحة الرئيسية.
 */
if (!function_exists('strip_contenteditable_from_html')) {
    function strip_contenteditable_from_html($html)
    {
        if (!is_string($html) || $html === '') {
            return $html;
        }
        // إزالة السمة contenteditable بأشكالها
        $html = preg_replace('/\s+contenteditable\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\s+contenteditable(?=\s|>)/i', '', $html);
        // إزالة كلاس html-preview-editable من سمات class
        $html = preg_replace_callback('/class\s*=\s*["\']([^"\']*)["\']/i', function ($m) {
            $classes = trim(preg_replace('/\s*html-preview-editable\s*/i', ' ', $m[1]));
            $classes = preg_replace('/\s+/', ' ', $classes);
            return 'class="' . $classes . '"';
        }, $html);
        return $html;
    }
}
