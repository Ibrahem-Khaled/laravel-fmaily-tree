<?php

namespace App\Http\Controllers;

use App\Models\ImportantLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ImportantLinksPublicController extends Controller
{
    /**
     * عرض صفحة الروابط المهمة العامة (تطبيقات ومواقع معتمدة)
     */
    public function index()
    {
        $links = ImportantLink::getApprovedActiveLinks();

        return view('important-links.index', compact('links'));
    }

    /**
     * حفظ اقتراح رابط جديد من الجمهور (مسجّل أو ضيف)
     */
    public function suggest(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'type' => ['required', Rule::in(['app', 'website'])],
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        if (auth()->check()) {
            $userId = auth()->id();
        } else {
            $rules['name'] = 'required|string|max:255';
            $rules['phone'] = 'required|string|max:50';
        }

        $validated = $request->validate($rules);

        $submittedByUserId = null;
        if (auth()->check()) {
            $submittedByUserId = auth()->id();
        } else {
            $user = User::firstOrCreate(
                ['phone' => $validated['phone']],
                [
                    'name' => $validated['name'],
                    'email' => null,
                    'password' => null,
                ]
            );
            $submittedByUserId = $user->id;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('important-links', 'public');
        }

        $lastOrder = ImportantLink::max('order') ?? 0;

        ImportantLink::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'icon' => $validated['type'] === 'app' ? 'fas fa-mobile-alt' : 'fas fa-globe',
            'submitted_by_user_id' => $submittedByUserId,
            'status' => 'pending',
            'order' => $lastOrder + 1,
            'is_active' => false,
            'open_in_new_tab' => true,
        ]);

        return redirect()->route('important-links.index')
            ->with('success', 'تم إرسال اقتراحك بنجاح. سيتم مراجعته من فريق الموقع.');
    }
}
