<?php

namespace App\Http\Controllers;

use App\Models\FamilyNews;
use Illuminate\Http\Request;

class FamilyNewsController extends Controller
{
    /**
     * عرض صفحة الخبر الكاملة
     */
    public function show($id)
    {
        try {
            // جلب الخبر - إذا كان المستخدم مسجل دخول، يمكنه رؤية الأخبار غير المفعلة
            $query = FamilyNews::with('images')
                ->where('id', $id);

            // إذا لم يكن المستخدم مسجل دخول، اجلب فقط الأخبار النشطة
            if (!auth()->check()) {
                $query->where('is_active', true)
                      ->where(function($q) {
                          $q->whereNull('published_at')
                            ->orWhere('published_at', '<=', now());
                      });
            }

            $news = $query->first();

            // إذا لم يتم العثور على الخبر
            if (!$news) {
                // إذا كان المستخدم مسجل دخول، حاول البحث بدون شروط الحالة
                if (auth()->check()) {
                    $news = FamilyNews::with('images')->where('id', $id)->first();
                    if (!$news) {
                        abort(404, 'الخبر غير موجود');
                    }
                } else {
                    abort(404, 'الخبر غير موجود أو غير متاح للعرض');
                }
            }

            // زيادة عدد المشاهدات
            $news->incrementViews();

            // جلب أخبار أخرى (للاقتراحات)
            $relatedQuery = FamilyNews::where('id', '!=', $news->id);

            if (!auth()->check()) {
                $relatedQuery->where('is_active', true)
                             ->where(function($q) {
                                 $q->whereNull('published_at')
                                   ->orWhere('published_at', '<=', now());
                             });
            }

            $relatedNews = $relatedQuery->orderBy('display_order')
                ->orderBy('published_at', 'desc')
                ->take(4)
                ->get();

            return view('family-news.show', compact('news', 'relatedNews'));
        } catch (\Exception $e) {
            abort(404, 'الخبر غير موجود');
        }
    }
}
