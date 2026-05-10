<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SupportChannel;
use App\Models\SupportFaq;
use App\Models\SupportSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupportPageAdminController extends Controller
{
    public function index()
    {
        $settings = SupportSetting::singleton();

        $channels = SupportChannel::query()->ordered()->get();

        $faqs = SupportFaq::query()->ordered()->get();

        return view('dashboard.support.index', compact('settings', 'channels', 'faqs'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'page_title' => 'required|string|max:255',
            'page_subtitle' => 'nullable|string|max:500',
            'intro_html' => 'nullable|string',
        ]);

        $settings = SupportSetting::singleton();
        $settings->update($validated);

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تم حفظ إعدادات صفحة الدعم.');
    }

    public function createChannel()
    {
        $channel = new SupportChannel([
            'type' => SupportChannel::TYPE_EMAIL,
            'sort_order' => (SupportChannel::max('sort_order') ?? 0) + 1,
            'is_active' => true,
        ]);

        return view('dashboard.support.channel-form', [
            'channel' => $channel,
            'mode' => 'create',
        ]);
    }

    public function storeChannel(Request $request)
    {
        $data = $this->validatedChannel($request);

        SupportChannel::create($data);

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تمت إضافة قناة التواصل.');
    }

    public function editChannel(SupportChannel $supportChannel)
    {
        return view('dashboard.support.channel-form', [
            'channel' => $supportChannel,
            'mode' => 'edit',
        ]);
    }

    public function updateChannel(Request $request, SupportChannel $supportChannel)
    {
        $data = $this->validatedChannel($request);

        $supportChannel->update($data);

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تم تحديث قناة التواصل.');
    }

    public function destroyChannel(SupportChannel $supportChannel)
    {
        $supportChannel->delete();

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تم حذف قناة التواصل.');
    }

    public function toggleChannel(SupportChannel $supportChannel)
    {
        $supportChannel->update(['is_active' => ! $supportChannel->is_active]);

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تم تحديث حالة القناة.');
    }

    public function createFaq()
    {
        $faq = new SupportFaq([
            'sort_order' => (SupportFaq::max('sort_order') ?? 0) + 1,
            'is_active' => true,
        ]);

        return view('dashboard.support.faq-form', [
            'faq' => $faq,
            'mode' => 'create',
        ]);
    }

    public function storeFaq(Request $request)
    {
        $data = $this->validatedFaq($request);

        SupportFaq::create($data);

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تمت إضافة السؤال.');
    }

    public function editFaq(SupportFaq $supportFaq)
    {
        return view('dashboard.support.faq-form', [
            'faq' => $supportFaq,
            'mode' => 'edit',
        ]);
    }

    public function updateFaq(Request $request, SupportFaq $supportFaq)
    {
        $data = $this->validatedFaq($request);

        $supportFaq->update($data);

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تم تحديث السؤال.');
    }

    public function destroyFaq(SupportFaq $supportFaq)
    {
        $supportFaq->delete();

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تم حذف السؤال.');
    }

    public function toggleFaq(SupportFaq $supportFaq)
    {
        $supportFaq->update(['is_active' => ! $supportFaq->is_active]);

        return redirect()->route('dashboard.support.index')
            ->with('success', 'تم تحديث حالة السؤال.');
    }

    private function validatedChannel(Request $request): array
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => ['required', Rule::in([
                SupportChannel::TYPE_EMAIL,
                SupportChannel::TYPE_PHONE,
                SupportChannel::TYPE_WHATSAPP,
                SupportChannel::TYPE_URL,
                SupportChannel::TYPE_OTHER,
            ])],
            'value' => 'required|string|max:1024',
            'icon' => 'nullable|string|max:128',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function validatedFaq(Request $request): array
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
