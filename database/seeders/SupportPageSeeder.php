<?php

namespace Database\Seeders;

use App\Models\SupportChannel;
use App\Models\SupportFaq;
use App\Models\SupportSetting;
use Illuminate\Database\Seeder;

class SupportPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedChannelsIfEmpty();
        $this->seedFaqsIfEmpty();
    }

    private function seedSettings(): void
    {
        $settings = SupportSetting::singleton();

        // لا نستبدل محتوى تم إعداده من لوحة التحكم؛ نعبّئ العرض التوضيحي عند غياب المقدمة فقط
        if (filled(trim((string) $settings->intro_html))) {
            return;
        }

        $introHtml = <<<'HTML'
<p class="mb-3">نرحّب بتواصلك معنا. فريق الدعم يعمل على مساعدة أفراد العائلة في استخدام الموقع والتطبيق، والإجابة عن الاستفسارات التقنية بأسرع وقت ممكن.</p>
<p class="mb-3"><strong>أوقات الاستجابة التقريبية:</strong> خلال يومي عمل، مع مراعاة العطل الرسمية.</p>
<ul class="list-disc pr-6 space-y-1 mt-2">
<li>يرجى ذكر نوع الجهاز والمتصفح عند الإبلاغ عن مشكلة تقنية.</li>
<li>للاستعجال يُفضّل التواصل عبر واتساب مع ذكر الاسم.</li>
</ul>
HTML;

        $settings->fill([
            'page_title' => 'الدعم الفني',
            'page_subtitle' => 'نسعد بخدمتك والإجابة عن استفساراتك حول الموقع والخدمات',
            'intro_html' => trim($introHtml),
        ]);
        $settings->save();
    }

    private function seedChannelsIfEmpty(): void
    {
        if (SupportChannel::query()->exists()) {
            return;
        }

        $rows = [
            [
                'label' => 'البريد الإلكتروني',
                'type' => SupportChannel::TYPE_EMAIL,
                'value' => 'support@example.com',
                'icon' => 'fas fa-envelope',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'واتساب الدعم',
                'type' => SupportChannel::TYPE_WHATSAPP,
                'value' => '966501234567',
                'icon' => 'fab fa-whatsapp',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'الهاتف',
                'type' => SupportChannel::TYPE_PHONE,
                'value' => '+966 50 123 4567',
                'icon' => 'fas fa-phone-alt',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'label' => 'مركز المساعدة على الويب',
                'type' => SupportChannel::TYPE_URL,
                'value' => 'https://example.com/help',
                'icon' => 'fas fa-link',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($rows as $row) {
            SupportChannel::create($row);
        }
    }

    private function seedFaqsIfEmpty(): void
    {
        if (SupportFaq::query()->exists()) {
            return;
        }

        $faqs = [
            [
                'question' => 'كيف أستعيد كلمة المرور أو أغيّرها؟',
                'answer' => "من صفحة تسجيل الدخول اختر «نسيت كلمة المرور» واتبع الرابط المرسل إلى بريدك.\nإذا كنت مسجلاً داخل الموقع، يمكن التعديل من إعدادات الحساب أو الملف الشخصي حسب ما يوفّره النظام لديك.",
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'لا أستطيع رفع صورة أو ملف، ما السبب؟',
                'answer' => "تأكد من حجم الملف ضمن الحد المسموح، ومن نوع الصيغة المقبولة.\nجرّب متصفحاً محدّثاً أو مسح ذاكرة التصفح، وإذا استمرّت المشكلة أرسل لنا لقطة شاشة مع نوع الجهاز والمتصفح.",
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'كيف أبلّغ عن خطأ تقني أو اقتراح تحسين؟',
                'answer' => "استخدم إحدى قنوات التواصل أعلاه واذكر:\n1) ما الذي حاولت فعله\n2) ما الذي ظهر لك\n3) الوقت التقريبي للمشكلة\nوسنسعى للرد في أقرب وقت.",
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            SupportFaq::create($faq);
        }
    }
}
