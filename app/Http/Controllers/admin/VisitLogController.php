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

        // Calculate week options for dropdown
        $weekOptions = $this->getWeekOptions();

        return view('dashboard.visit-logs.index', [
            'visitLogs' => $visitLogs,
            'stats' => $stats,
            'users' => $users,
            'routeNames' => $routeNames,
            'methods' => $methods,
            'ipAddresses' => $ipAddresses,
            'weekOptions' => $weekOptions,
        ]);
    }

    /**
     * Get statistics for the current query
     */
    private function getStatistics($query)
    {
        $baseQuery = clone $query;
        
        $totalVisits = $baseQuery->count();
        $uniqueVisitors = $baseQuery->distinct('ip_address')->count('ip_address');
        $uniqueUsers = $baseQuery->whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $avgResponseTime = $baseQuery->avg('response_time');
        
        // Most visited routes
        $topRoutes = (clone $query)
            ->select('route_name', DB::raw('COUNT(*) as count'))
            ->whereNotNull('route_name')
            ->groupBy('route_name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->pluck('count', 'route_name');
        
        // Most active IPs
        $topIPs = (clone $query)
            ->select('ip_address', DB::raw('COUNT(*) as count'))
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->pluck('count', 'ip_address');

        // Visits by hour
        $visitsByHour = (clone $query)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour');

        return [
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'unique_users' => $uniqueUsers,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
            'top_routes' => $topRoutes,
            'top_ips' => $topIPs,
            'visits_by_hour' => $visitsByHour,
        ];
    }

    /**
     * Get week options for dropdown
     */
    private function getWeekOptions(): array
    {
        $options = [];
        
        // Current week
        $options['this'] = 'هذا الأسبوع';
        
        // Last 12 weeks
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subWeeks($i);
            $year = $date->format('o'); // ISO year
            $week = $date->format('W'); // ISO week
            $start = $date->copy()->startOfWeek();
            $end = $date->copy()->endOfWeek();
            
            $key = "{$year}-{$week}";
            $label = "الأسبوع {$week} ({$start->format('Y/m/d')} - {$end->format('Y/m/d')})";
            
            $options[$key] = $label;
        }
        
        return $options;
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

