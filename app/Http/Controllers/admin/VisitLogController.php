<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\VisitLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitLogController extends Controller
{
    /**
     * Display visit logs with filtering
     */
    public function index(Request $request)
    {
        $query = VisitLog::query()->with(['user:id,name,email']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        // Filter by IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', $request->string('ip_address'));
        }

        // Filter by route name
        if ($request->filled('route_name')) {
            $query->where('route_name', $request->string('route_name'));
        }

        // Filter by method
        if ($request->filled('method')) {
            $query->where('method', $request->string('method'));
        }

        // Filter by week (default to this week if 'week' parameter is set)
        if ($request->filled('week')) {
            if ($request->string('week') === 'this') {
                $query->thisWeek();
            } else {
                // Parse week number and year
                $weekInput = $request->string('week');
                if (str_contains($weekInput, '-')) {
                    [$year, $week] = explode('-', $weekInput);
                    $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
                    $endDate = $startDate->copy()->endOfWeek();
                    $query->dateRange($startDate, $endDate);
                }
            }
        }

        // Filter by date range
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->filled('date_from') 
                ? Carbon::parse($request->input('date_from'))->startOfDay()
                : Carbon::now()->subMonth()->startOfDay();
            $to = $request->filled('date_to')
                ? Carbon::parse($request->input('date_to'))->endOfDay()
                : Carbon::now()->endOfDay();
            $query->dateRange($from, $to);
        }

        // Search in URL
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhere('route_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Get statistics for the filtered period
        $stats = $this->getStatistics($query->clone());

        // Paginate results
        $visitLogs = $query->latest('created_at')->paginate(30)->withQueryString();

        // Get filter options
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $routeNames = VisitLog::select('route_name')
            ->whereNotNull('route_name')
            ->distinct()
            ->orderBy('route_name')
            ->pluck('route_name');
        $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
        $ipAddresses = VisitLog::select('ip_address')
            ->distinct()
            ->orderBy('ip_address')
            ->pluck('ip_address')
            ->filter();

        // Calculate week options for dropdown - فقط الأسابيع التي تحتوي على بيانات
        $weekOptions = $this->getWeekOptions();
        
        // الحصول على أول وآخر تاريخ فيه بيانات لتحديد نطاق التاريخ
        $firstVisitDate = VisitLog::orderBy('created_at', 'asc')->value('created_at');
        $lastVisitDate = VisitLog::orderBy('created_at', 'desc')->value('created_at');

        // ترجمة جميع أسماء المسارات للعرض
        $translatedRouteNames = $routeNames->mapWithKeys(function($route) {
            return [$route => $this->translateRouteName($route)];
        });

        return view('dashboard.visit-logs.index', [
            'visitLogs' => $visitLogs,
            'stats' => $stats,
            'users' => $users,
            'routeNames' => $routeNames,
            'translatedRouteNames' => $translatedRouteNames,
            'methods' => $methods,
            'ipAddresses' => $ipAddresses,
            'weekOptions' => $weekOptions,
            'firstVisitDate' => $firstVisitDate ? Carbon::parse($firstVisitDate)->format('Y-m-d') : null,
            'lastVisitDate' => $lastVisitDate ? Carbon::parse($lastVisitDate)->format('Y-m-d') : null,
            'translateRoute' => [$this, 'translateRouteName'],
        ]);
    }

    /**
     * Get statistics for the current query
     */
    private function getStatistics($query)
    {
        $baseQuery = clone $query;
        
        // إجمالي الزيارات
        $totalVisits = $baseQuery->count();
        
        // الزيارات الفعلية (الفريدة فقط)
        $actualVisits = (clone $baseQuery)->where('is_unique_visit', true)->count();
        
        // زوار فريدون (حسب IP)
        $uniqueVisitors = $baseQuery->distinct('ip_address')->count('ip_address');
        
        // زوار فريدون (حسب IP) - فقط الزيارات الفعلية
        $uniqueActualVisitors = (clone $baseQuery)
            ->where('is_unique_visit', true)
            ->distinct('ip_address')
            ->count('ip_address');
        
        // مستخدمون مسجلون
        $uniqueUsers = $baseQuery->whereNotNull('user_id')->distinct('user_id')->count('user_id');
        
        // متوسط وقت الاستجابة
        $avgResponseTime = $baseQuery->avg('response_time');
        
        // متوسط مدة الزيارة (بالثواني)
        $avgDuration = (clone $baseQuery)
            ->whereNotNull('duration')
            ->avg('duration');
        
        // أكثر المسارات زيارة
        $topRoutes = (clone $query)
            ->select('route_name', DB::raw('COUNT(*) as count'))
            ->whereNotNull('route_name')
            ->where('is_unique_visit', true)
            ->groupBy('route_name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->pluck('count', 'route_name');
        
        // أكثر العناوين نشاطاً (مع عدد الزيارات الفعلية)
        $topIPs = (clone $query)
            ->select('ip_address', 
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(CASE WHEN is_unique_visit = 1 THEN 1 ELSE 0 END) as unique_count')
            )
            ->groupBy('ip_address')
            ->orderBy('unique_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'ip' => $item->ip_address,
                    'total' => $item->total_count,
                    'unique' => $item->unique_count,
                ];
            });
        
        // تجميع الزيارات حسب IP (كشخص)
        $visitorsByIP = (clone $query)
            ->select('ip_address', 
                DB::raw('COUNT(*) as visit_count'),
                DB::raw('SUM(CASE WHEN is_unique_visit = 1 THEN 1 ELSE 0 END) as unique_visit_count'),
                DB::raw('MIN(created_at) as first_visit'),
                DB::raw('MAX(created_at) as last_visit'),
                DB::raw('AVG(duration) as avg_duration')
            )
            ->groupBy('ip_address')
            ->orderBy('unique_visit_count', 'desc')
            ->limit(20)
            ->get()
            ->map(function($item) use ($query) {
                // جلب الصفحات التي زارها هذا IP
                $routes = (clone $query)
                    ->where('ip_address', $item->ip_address)
                    ->whereNotNull('route_name')
                    ->distinct('route_name')
                    ->pluck('route_name')
                    ->toArray();
                
                // ترجمة أسماء المسارات
                $translatedRoutes = array_map(function($route) {
                    return $this->translateRouteName($route);
                }, $routes);
                
                return [
                    'ip' => $item->ip_address,
                    'visit_count' => $item->visit_count,
                    'unique_visit_count' => $item->unique_visit_count,
                    'routes' => $routes,
                    'routes_translated' => $translatedRoutes,
                    'first_visit' => $item->first_visit ? Carbon::parse($item->first_visit) : null,
                    'last_visit' => $item->last_visit ? Carbon::parse($item->last_visit) : null,
                    'avg_duration' => round($item->avg_duration ?? 0, 0),
                ];
            });
        
        // الصفحات الأكثر زيارة مع المدة المتوسطة
        $pagesWithDuration = (clone $query)
            ->select('route_name', 'url',
                DB::raw('COUNT(*) as visit_count'),
                DB::raw('SUM(CASE WHEN is_unique_visit = 1 THEN 1 ELSE 0 END) as unique_count'),
                DB::raw('AVG(duration) as avg_duration')
            )
            ->whereNotNull('route_name')
            ->groupBy('route_name', 'url')
            ->orderBy('unique_count', 'desc')
            ->limit(10)
            ->get();
        
        // الزيارات حسب الساعة
        $visitsByHour = (clone $query)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->where('is_unique_visit', true)
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour');

        return [
            'total_visits' => $totalVisits,
            'actual_visits' => $actualVisits,
            'unique_visitors' => $uniqueVisitors,
            'unique_actual_visitors' => $uniqueActualVisitors,
            'unique_users' => $uniqueUsers,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
            'avg_duration' => round($avgDuration ?? 0, 0),
            'top_routes' => $topRoutes,
            'top_ips' => $topIPs,
            'visitors_by_ip' => $visitorsByIP,
            'pages_with_duration' => $pagesWithDuration,
            'visits_by_hour' => $visitsByHour,
        ];
    }

    /**
     * Get week options for dropdown - فقط الأسابيع التي تحتوي على بيانات
     */
    private function getWeekOptions(): array
    {
        $options = [];
        
        // الحصول على أول تاريخ فيه بيانات
        $firstVisit = VisitLog::orderBy('created_at', 'asc')->first();
        if (!$firstVisit) {
            return ['this' => 'هذا الأسبوع'];
        }
        
        $firstDate = Carbon::parse($firstVisit->created_at);
        $currentDate = Carbon::now();
        
        // Current week
        if ($currentDate->greaterThanOrEqualTo($firstDate->copy()->startOfWeek())) {
            $options['this'] = 'هذا الأسبوع';
        }
        
        // Generate weeks from first visit date to now
        $startWeek = $firstDate->copy()->startOfWeek();
        $endWeek = $currentDate->copy()->endOfWeek();
        
        $week = $startWeek->copy();
        $weekCount = 0;
        $maxWeeks = 52; // Max 52 weeks (1 year)
        
        while ($week->lessThanOrEqualTo($endWeek) && $weekCount < $maxWeeks) {
            // التحقق من وجود بيانات في هذا الأسبوع
            $hasData = VisitLog::whereBetween('created_at', [
                $week->copy()->startOfWeek(),
                $week->copy()->endOfWeek()
            ])->exists();
            
            if ($hasData) {
                $year = $week->format('o'); // ISO year
                $weekNum = $week->format('W'); // ISO week
                $start = $week->copy()->startOfWeek();
                $end = $week->copy()->endOfWeek();
                
                $key = "{$year}-{$weekNum}";
                $label = "الأسبوع {$weekNum} ({$start->format('Y/m/d')} - {$end->format('Y/m/d')})";
                
                $options[$key] = $label;
            }
            
            $week->addWeek();
            $weekCount++;
        }
        
        return $options;
    }
    
    /**
     * Translate route name to Arabic
     */
    private function translateRouteName(string $routeName): string
    {
        $translations = [
            'home' => 'الصفحة الرئيسية',
            'sila' => 'صلة - شجرة العائلة',
            'family-tree' => 'شجرة العائلة',
            'gallery.index' => 'معرض الصور',
            'gallery.articles' => 'شهادات وأبحاث',
            'gallery.show' => 'عرض المقال',
            'person.gallery' => 'معرض شخص',
            'people.index' => 'قائمة الأشخاص',
            'people.profile.show' => 'ملف شخص',
            'persons.badges' => 'طلاب طموح',
            'breastfeeding.index' => 'الرضاعة',
            'breastfeeding.public.index' => 'الرضاعة',
            'breastfeeding.public.show' => 'تفاصيل الرضاعة',
            'stories.index' => 'القصص',
            'public.stories.person' => 'قصص شخص',
            'public.stories.show' => 'تفاصيل قصة',
            'dashboard' => 'لوحة التحكم',
            'dashboard.visit-logs.index' => 'سجل الزيارات',
            'dashboard.visit-logs.show' => 'تفاصيل الزيارة',
            'dashboard.images.index' => 'إدارة الصور',
            'dashboard.site-content.index' => 'محتوى الموقع',
            'dashboard.site-content.update-family-brief' => 'تحديث نبذة العائلة',
            'dashboard.site-content.update-whats-new' => 'تحديث ما الجديد',
            'articles.index' => 'المقالات',
            'categories.index' => 'الأقسام',
            'categories.quick-store' => 'إضافة قسم سريع',
            'login' => 'تسجيل الدخول',
            'customLogin' => 'تسجيل دخول مخصص',
            'profile.edit' => 'تعديل الملف الشخصي',
            'old.family-tree' => 'شجرة العائلة القديمة',
            'livewire.update' => 'تحديث Livewire',
            'logs.activity' => 'سجل النشاطات',
            'logs.audits' => 'سجل المراجعات',
            'marriages.index' => 'الزواجات',
        ];
        
        return $translations[$routeName] ?? $routeName;
    }

    /**
     * Show single visit log details
     */
    public function show(VisitLog $visitLog)
    {
        $visitLog->load('user:id,name,email');
        
        return view('dashboard.visit-logs.show', [
            'visitLog' => $visitLog,
        ]);
    }
}

