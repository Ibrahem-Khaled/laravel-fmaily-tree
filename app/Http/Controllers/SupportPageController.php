<?php

namespace App\Http\Controllers;

use App\Models\SupportChannel;
use App\Models\SupportFaq;
use App\Models\SupportSetting;

class SupportPageController extends Controller
{
    public function index()
    {
        $settings = SupportSetting::singleton();

        $channels = SupportChannel::query()
            ->active()
            ->ordered()
            ->get();

        $faqs = SupportFaq::query()
            ->active()
            ->ordered()
            ->get();

        return view('web-site.support', compact('settings', 'channels', 'faqs'));
    }
}
