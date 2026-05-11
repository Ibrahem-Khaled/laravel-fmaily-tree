<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Events\StoreFamilyEventRequest;
use App\Http\Requests\Admin\Events\UpdateFamilyEventRequest;
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
     * عرض صفحة إضافة مناسبة جديدة
     */
    public function create()
    {
        return view('dashboard.events.create');
    }

    /**
     * عرض صفحة تعديل مناسبة
     */
    public function edit(FamilyEvent $event)
    {
        return view('dashboard.events.edit', compact('event'));
    }

    /**
     * إضافة مناسبة جديدة
     */
    public function store(StoreFamilyEventRequest $request)
    {
        $data = $request->validated();
        $data['display_order'] = (int) (FamilyEvent::max('display_order') ?? 0) + 1;

        FamilyEvent::create($data);

        return redirect()->route('dashboard.events.index')
            ->with('success', 'تم إضافة المناسبة بنجاح');
    }

    /**
     * تحديث مناسبة
     */
    public function update(UpdateFamilyEventRequest $request, FamilyEvent $event)
    {
        $event->update($request->validated());

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
