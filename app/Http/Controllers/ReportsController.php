<?php

namespace App\Http\Controllers;

use App\Services\ReportsPageData;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function __construct(
        protected ReportsPageData $reportsPageData
    ) {}

    /**
     * عرض صفحة التقارير والإحصائيات.
     */
    public function index(): View
    {
        return view('reports', $this->reportsPageData->buildIndex());
    }

    /**
     * جلب الأشخاص التابعين لمنطقة معينة (الأحياء فقط)
     */
    public function getLocationPersons(int $locationId)
    {
        return response()->json($this->reportsPageData->locationPersonsPayload($locationId));
    }

    /**
     * جلب إحصائيات شخص معين (للعرض عند النقر على اسم من القائمة)
     */
    public function getPersonStatistics(int $personId)
    {
        return response()->json($this->reportsPageData->personStatisticsPayload($personId));
    }

    /**
     * جلب الأشخاص الذين يحملون اسم معين
     */
    public function getPersonsByName(string $name)
    {
        return response()->json($this->reportsPageData->personsByNamePayload($name));
    }
}
