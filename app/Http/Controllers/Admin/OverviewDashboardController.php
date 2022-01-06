<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OverviewDashboardController extends Controller
{
    public function index()
    {
        $orders_overview = Invoice::paid()->selectRaw('sum(gross_cost) as gross_cost, created_at')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at), id'))
            ->orderBy('created_at', 'DESC')
            ->get();

        $overview_chart = [];
        if($orders_overview && $orders_overview->count())
        {
            foreach ($orders_overview as $item)
            {
                $date = new \DateTime($item->created_at);
                $overview_chart[$date->format('dS M Y')] = $item->gross_cost;
            }
        }

        $orders_overview_last_year = Invoice::paid()->selectRaw('sum(gross_cost) as gross_cost, created_at')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(395))
            ->whereDate('created_at', '<=', Carbon::now()->subDays(365))
            ->groupBy(DB::raw('DATE(created_at), id'))
            ->orderBy('created_at', 'DESC')
            ->get();

        $overview_chart_last_year = [];
        if($orders_overview_last_year && $orders_overview_last_year->count())
        {
            foreach ($orders_overview_last_year as $item)
            {
                $date = new \DateTime($item->created_at);
                $overview_chart_last_year[$date->format('dS M Y')] = $item->gross_cost;
            }
        }

        $sales_orders = SalesOrder::whereDate('created_at', '>=', Carbon::now()->subDays(30))->get();

        set_page_title('Overview Dashboard');
        return view('admin.dashboard', compact(['overview_chart','overview_chart_last_year','sales_orders']));
    }
}
