<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FamilyEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilyEventController extends Controller
{
    /**
     * عرض صفحة إدارة مناسبات العائلة
     */
    public function index()
    {
        $events = FamilyEvent::orderBy('display_order')->orderBy('event_date')->get();

        // إحصائيات المناسبات
        $stats = [
            'total' => FamilyEvent::count(),
            'active' => FamilyEvent::where('is_active', true)->count(),
            'inactive' => FamilyEvent::where('is_active', false)->count(),
            'upcoming' => FamilyEvent::where('is_active', true)
                ->where('event_date', '>=', now())
                ->count(),
            'with_countdown' => FamilyEvent::where('show_countdown', true)->count(),
        ];

        return view('dashboard.events.index', compact('events', 'stats'));
    }

    /**
     * إضافة مناسبة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|url|max:500',
            'location_name' => 'nullable|string|max:255',
            'event_date' => 'required|date',
        ]);

        $lastOrder = FamilyEvent::max('display_order') ?? 0;

        FamilyEvent::create([
            'title' => $request->title,
            'description' => $request->description,
            'city' => $request->city,
            'location' => $request->location,
            'location_name' => $request->location_name,
            'event_date' => $request->event_date,
            'show_countdown' => $request->has('show_countdown') ? true : false,
            'display_order' => $lastOrder + 1,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('dashboard.events.index')
            ->with('success', 'تم إضافة المناسبة بنجاح');
    }

    /**
     * تحديث مناسبة
     */
    public function update(Request $request, FamilyEvent $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|url|max:500',
            'location_name' => 'nullable|string|max:255',
            'event_date' => 'required|date',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'city' => $request->city,
            'location' => $request->location,
            'location_name' => $request->location_name,
            'event_date' => $request->event_date,
            'show_countdown' => $request->has('show_countdown') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('dashboard.events.index')
            ->with('success', 'تم تحديث المناسبة بنجاح');
    }

    /**
     * جلب بيانات مناسبة (للتعديل)
     */
    public function show(FamilyEvent $event)
    {
        return response()->json([
            'title' => $event->title,
            'description' => $event->description,
            'city' => $event->city,
            'location' => $event->location,
            'location_name' => $event->location_name,
            'event_date' => $event->event_date->format('Y-m-d\TH:i'),
            'show_countdown' => $event->show_countdown,
            'is_active' => $event->is_active,
        ]);
    }

    /**
     * حذف مناسبة
     */
    public function destroy(FamilyEvent $event)
    {
        $event->delete();

        return redirect()->route('dashboard.events.index')
            ->with('success', 'تم حذف المناسبة بنجاح');
    }

    /**
     * إعادة ترتيب المناسبات
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:family_events,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $eventId) {
                FamilyEvent::where('id', $eventId)
                    ->update(['display_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }
}

