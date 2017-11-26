<?php

namespace Botble\Analytics;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class Analytics
{
    use Macroable;

    /**
     * @var AnalyticsClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $viewId;

    /**
     * @param AnalyticsClient $client
     * @param string $viewId
     * @author Freek Van der Herten <freek@spatie.be>
     * @modified Sang Nguyen
     */
    public function __construct(AnalyticsClient $client, $viewId)
    {
        $this->client = $client;

        $this->viewId = $viewId;
    }

    /**
     * @param string $viewId
     *
     * @return $this
     * @author Freek Van der Herten <freek@spatie.be>
     * @modified Sang Nguyen
     */
    public function setViewId($viewId)
    {
        $this->viewId = $viewId;

        return $this;
    }

    /**
     * @param Period $period
     * @return mixed
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function fetchVisitorsAndPageViews(Period $period)
    {
        $response = $this->performQuery(
            $period,
            'ga:users,ga:pageviews',
            ['dimensions' => 'ga:date,ga:pageTitle']
        );

        $data = isset($response['rows']) ? $response['rows'] : [];

        return collect($data)->map(function (array $dateRow) {
            return [
                'date' => Carbon::createFromFormat('Ymd', $dateRow[0]),
                'pageTitle' => $dateRow[1],
                'visitors' => (int)$dateRow[2],
                'pageViews' => (int)$dateRow[3],
            ];
        });
    }

    /**
     * @param Period $period
     * @return mixed
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function fetchTotalVisitorsAndPageViews(Period $period)
    {
        $response = $this->performQuery(
            $period,
            'ga:users,ga:pageviews',
            ['dimensions' => 'ga:date']
        );
        return collect($response['rows'] ? $response['rows'] : [])->map(function (array $dateRow) {
            return [
                'date' => Carbon::createFromFormat('Ymd', $dateRow[0]),
                'visitors' => (int)$dateRow[1],
                'pageViews' => (int)$dateRow[2],
            ];
        });
    }

    /**
     * @param Period $period
     * @param int $maxResults
     * @return mixed
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function fetchMostVisitedPages(Period $period, $maxResults = 20)
    {
        $response = $this->performQuery(
            $period,
            'ga:pageviews',
            [
                'dimensions' => 'ga:pagePath,ga:pageTitle',
                'sort' => '-ga:pageviews',
                'max-results' => $maxResults,
            ]
        );

        $data = isset($response['rows']) ? $response['rows'] : [];

        return collect($data)->map(function (array $pageRow) {
            return [
                'url' => $pageRow[0],
                'pageTitle' => $pageRow[1],
                'pageViews' => (int)$pageRow[2],
            ];
        });
    }

    /**
     * @param Period $period
     * @param int $maxResults
     * @return mixed
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function fetchTopReferrers(Period $period, $maxResults = 20)
    {
        $response = $this->performQuery($period,
            'ga:pageviews',
            [
                'dimensions' => 'ga:fullReferrer',
                'sort' => '-ga:pageviews',
                'max-results' => $maxResults,
            ]
        );

        $data = isset($response['rows']) ? $response['rows'] : [];

        return collect($data)->map(function (array $pageRow) {
            return [
                'url' => $pageRow[0],
                'pageViews' => (int)$pageRow[1],
            ];
        });
    }

    /**
     * @param Period $period
     * @param int $maxResults
     * @return mixed
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function fetchTopBrowsers(Period $period, $maxResults = 10)
    {
        $response = $this->performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:browser',
                'sort' => '-ga:sessions',
            ]
        );
        $data = isset($response['rows']) ? $response['rows'] : [];

        $topBrowsers = collect($data)->map(function (array $browserRow) {
            return [
                'browser' => $browserRow[0],
                'sessions' => (int)$browserRow[1],
            ];
        });

        if ($topBrowsers->count() <= $maxResults) {
            return $topBrowsers;
        }

        return $this->summarizeTopBrowsers($topBrowsers, $maxResults);
    }

    /**
     * @param Collection $topBrowsers
     * @param $maxResults
     * @return mixed
     * @author Freek Van der Herten <freek@spatie.be>
     */
    protected function summarizeTopBrowsers(Collection $topBrowsers, $maxResults)
    {
        return $topBrowsers
            ->take($maxResults - 1)
            ->push([
                'browser' => 'Others',
                'sessions' => $topBrowsers->splice($maxResults - 1)->sum('sessions'),
            ]);
    }

    /**
     * Call the query method on the authenticated client.
     *
     * @param Period $period
     * @param string $metrics
     * @param array $others
     *
     * @return Period|array|null
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function performQuery(Period $period, $metrics, array $others = [])
    {
        return $this->client->performQuery(
            $this->viewId,
            $period->startDate,
            $period->endDate,
            $metrics,
            $others
        );
    }

    /**
     * Get the underlying Google_Service_Analytics object. You can use this
     * to basically call anything on the Google Analytics API.
     *
     * @return \Google_Service_Analytics
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function getAnalyticsService()
    {
        return $this->client->getAnalyticsService();
    }
}
