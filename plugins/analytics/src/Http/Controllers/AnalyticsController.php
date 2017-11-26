<?php

namespace Botble\Analytics\Http\Controllers;

use Botble\Analytics\Exceptions\InvalidConfiguration;
use Botble\Analytics\Period;
use Botble\Base\Http\Controllers\BaseController;
use Carbon;
use Analytics;
use Exception;

class AnalyticsController extends BaseController
{

    /**
     * @return \Illuminate\Support\Collection|null|array
     * @author Sang Nguyen
     */
    public static function getGeneral()
    {
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $dimensions = 'hour';

        try {
            $period = Period::create($startDate, $endDate);

            $visitorData = [];

            $answer = Analytics::performQuery($period, 'ga:visits,ga:pageviews', ['dimensions' => 'ga:' . $dimensions]);

            if ($answer->rows == null) {
                return collect([]);
            }

            if ($dimensions === 'hour') {
                foreach ($answer->rows as $dateRow) {
                    $visitorData[] = [
                        'axis' => (int)$dateRow[0] . 'h',
                        'visitors' => $dateRow[1],
                        'pageViews' => $dateRow[2],
                    ];
                }
            } else {
                foreach ($answer->rows as $dateRow) {
                    $visitorData[] = [
                        'axis' => Carbon::parse($dateRow[0])->toDateString(),
                        'visitors' => $dateRow[1],
                        'pageViews' => $dateRow[2],
                    ];
                }
            }

            $stats = collect($visitorData);
            $country_stats = Analytics::performQuery($period, 'ga:sessions', ['dimensions' => 'ga:countryIsoCode'])->rows;
            $total = Analytics::performQuery($period, 'ga:sessions, ga:users, ga:pageviews, ga:percentNewSessions, ga:bounceRate, ga:pageviewsPerVisit, ga:avgSessionDuration, ga:newUsers')->totalsForAllResults;

            return [
                'error' => false,
                'data' => view('analytics::widgets.general.general', compact('stats', 'country_stats', 'total'))->render(),
            ];
        } catch (InvalidConfiguration $ex) {
            return [
                'error' => true,
                'message' => trans('analytics::analytics.wrong_configuration', ['version' => config('cms.version')]),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @return null| array
     * @author Sang Nguyen
     */
    public function getTopVisitPages()
    {
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        try {
            $period = Period::create($startDate, $endDate);
            $pages = Analytics::fetchMostVisitedPages($period, 10);

            return [
                'error' => false,
                'data' => view('analytics::widgets.page.page', compact('pages'))->render(),
            ];
        } catch (InvalidConfiguration $ex) {
            return [
                'error' => true,
                'message' => trans('analytics::analytics.wrong_configuration', ['version' => config('cms.version')]),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @return null| array
     * @author Sang Nguyen
     */
    public function getTopBrowser()
    {
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        try {
            $period = Period::create($startDate, $endDate);
            $browsers = Analytics::fetchTopBrowsers($period, 10);

            return [
                'error' => false,
                'data' => view('analytics::widgets.browser.browser', compact('browsers'))->render(),
            ];
        } catch (InvalidConfiguration $ex) {
            return [
                'error' => true,
                'message' => trans('analytics::analytics.wrong_configuration', ['version' => config('cms.version')]),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @return null| array
     * @author Sang Nguyen
     */
    public function getTopReferrer()
    {
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        try {
            $period = Period::create($startDate, $endDate);
            $referrers = Analytics::fetchTopReferrers($period, 10);

            return [
                'error' => false,
                'data' => view('analytics::widgets.referrer.referrer', compact('referrers'))->render(),
            ];
        } catch (InvalidConfiguration $ex) {
            return [
                'error' => true,
                'message' => trans('analytics::analytics.wrong_configuration', ['version' => config('cms.version')]),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }
}