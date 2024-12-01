<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Website;
use App\Models\Setting;
use App\Models\Review;
use App\Models\WebsiteTrends;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;
    public function getWebsiteRankings(): JsonResponse
    {
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();

        // Retrieve website trends for the last 6 months
        $websiteTrends = WebsiteTrends::with('websites')->where('created_at', '>=', $startDate)
            ->select(DB::raw('website_id, COUNT(DISTINCT user_id) as count, MONTHNAME(created_at) as month, YEAR(created_at) as year'))
            ->groupBy('website_id', 'month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->distinct('user_id')
            ->get();

        $topWebsitesByMonth = [];

        foreach ($websiteTrends as $trend) {
            $monthYearKey = $trend->year . '-' . $trend->month;

            if (!isset($topWebsitesByMonth[$monthYearKey])) {
                $topWebsitesByMonth[$monthYearKey] = $trend;
            } else {
                if ($trend->count > $topWebsitesByMonth[$monthYearKey]->count) {
                    $topWebsitesByMonth[$monthYearKey] = $trend;
                }
            }
        }

        uksort($topWebsitesByMonth, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        // $topWebsites = [];
        foreach ($topWebsitesByMonth as $monthYearKey => $trend) {
            $topWebsites[] = [
                'website_id' => $trend->website_id,
                'websites' => $trend->websites,
                'count' => $trend->count,
                'month' => $trend->month,
                'year' => $trend->year,
            ];
        }

        return $this->json(
            200,
            'Websites rankings data retrieved successfully',
            $topWebsites
        );
    }

    public function getStats(): JsonResponse
    {
        $totalWebsites = Website::count();
        $activeRankings = Website::count();
        $categoriesCount = DB::table('categories')->count();
        $monthlyVisitors = 25400;

        $stats = [
            ['title' => 'Total Websites', 'value' => $totalWebsites],
            ['title' => 'Active Rankings', 'value' => $activeRankings],
            ['title' => 'Categories', 'value' => $categoriesCount],
            ['title' => 'Monthly Visitors', 'value' => number_format($monthlyVisitors)],
        ];

        return $this->json(
            200,
            'Stats fetched successfully',
            $stats
    );
    }
}
