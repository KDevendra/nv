<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user) {
            if (in_array($user->role, ['supply_head', 'field_officer'])) {
                return redirect()->route('field.dashboard');
            }

            if ($user->role === 'user') {
                return redirect()->route('user.dashboard');
            }
        }

        // Admin, Super Admin and other roles
        return view('admin.dashboard');
    }

    /**
     * Get visitor analytics data
     */
    public function getVisitorAnalytics(Request $request): JsonResponse
    {
        $period = $request->get('period', 'all');

        // Get stats based on period
        $stats = [
            'visits' => $this->getVisitsByPeriod($period),
            'unique' => PageVisit::getUniqueVisitors($period),
        ];

        // Get device stats
        $deviceStats = PageVisit::getVisitsByDevice();
        $totalDeviceVisits = $deviceStats->sum('count');

        $devices = [
            'desktop' => [
                'count' => $deviceStats->where('device_type', 'desktop')->first()->count ?? 0,
                'percentage' => 0
            ],
            'mobile' => [
                'count' => $deviceStats->where('device_type', 'mobile')->first()->count ?? 0,
                'percentage' => 0
            ],
            'tablet' => [
                'count' => $deviceStats->where('device_type', 'tablet')->first()->count ?? 0,
                'percentage' => 0
            ]
        ];

        // Calculate percentages
        if ($totalDeviceVisits > 0) {
            $devices['desktop']['percentage'] = round(($devices['desktop']['count'] / $totalDeviceVisits) * 100);
            $devices['mobile']['percentage'] = round(($devices['mobile']['count'] / $totalDeviceVisits) * 100);
            $devices['tablet']['percentage'] = round(($devices['tablet']['count'] / $totalDeviceVisits) * 100);
        }

        // Get chart data
        $dailyVisits = PageVisit::getDailyVisits(7);
        $dailyInquiries = \App\Models\PropertyInquiry::getDailySubmissions(7);

        // Merge visits and inquiries data by date
        $dates = $dailyVisits->pluck('date')->merge($dailyInquiries->pluck('date'))->unique()->sort()->values();

        $visitsData = [];
        $inquiriesData = [];

        foreach ($dates as $date) {
            $visitsData[] = $dailyVisits->where('date', $date)->first()->visits ?? 0;
            $inquiriesData[] = $dailyInquiries->where('date', $date)->first()->submissions ?? 0;
        }

        $chartData = [
            'labels' => $dates->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('M d');
            })->toArray(),
            'visits' => $visitsData,
            'inquiries' => $inquiriesData
        ];

        // Get device chart data
        $deviceChartData = [
            'labels' => $deviceStats->pluck('device_type')->map(function ($type) {
                return ucfirst($type);
            })->toArray(),
            'data' => $deviceStats->pluck('count')->toArray()
        ];

        // Get most visited pages
        $mostVisitedPages = PageVisit::getMostVisitedPages(5)->map(function ($page) {
            return [
                'name' => $page->page_name,
                'visits' => $page->visits
            ];
        });

        // Get top browsers
        $topBrowsers = PageVisit::getVisitsByBrowser(5)->map(function ($browser) {
            return [
                'name' => $browser->browser,
                'count' => $browser->count
            ];
        });

        // Get recent activity
        $recentActivity = PageVisit::getRecentVisits(5)->map(function ($visit) {
            return [
                'page_name' => $visit->page_name,
                'device_type' => $visit->device_type,
                'time_ago' => $visit->visited_at->diffForHumans()
            ];
        });

        return response()->json([
            'stats' => $stats,
            'devices' => $devices,
            'charts' => [
                'daily' => $chartData,
                'device' => $deviceChartData
            ],
            'details' => [
                'most_visited' => $mostVisitedPages,
                'top_browsers' => $topBrowsers,
                'recent_activity' => $recentActivity
            ]
        ]);
    }

    /**
     * Get visits count by period
     */
    private function getVisitsByPeriod(string $period): int
    {
        return match ($period) {
            'today' => PageVisit::getTodayVisits(),
            'week' => PageVisit::getWeekVisits(),
            'month' => PageVisit::getMonthVisits(),
            default => PageVisit::getTotalVisits(),
        };
    }
}
