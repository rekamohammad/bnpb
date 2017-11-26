<?php

namespace Botble\LogViewer\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\LogViewer\Exceptions\LogNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use LogViewer;

class LogViewerController extends BaseController
{

    /**
     * @var int|mixed
     */
    protected $perPage = 30;

    /**
     * @var string
     */
    protected $showRoute = 'log-viewer::logs.show';

    /**
     * LogViewerController constructor.
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function __construct()
    {
        $this->perPage = config('log-viewer.per-page', $this->perPage);
        Assets::addJavascript(['moment', 'datetimepicker', 'chart'])
            ->addStylesheets(['datetimepicker']);
    }

    /**
     * Show the dashboard.
     *
     * @return \Illuminate\View\View
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function index()
    {
        page_title()->setTitle(trans('log-viewer::general.name'));

        $stats = LogViewer::statsTable();

        $reports = $stats->totalsJson();
        $percents = $this->calcPercentages($stats->footer(), $stats->header());

        return view('log-viewer::dashboard', compact('reports', 'percents'));
    }

    /**
     * Calculate the percentage.
     *
     * @param  array $total
     * @param  array $names
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    protected function calcPercentages(array $total, array $names)
    {
        $percents = [];
        $all = Arr::get($total, 'all');

        foreach ($total as $level => $count) {
            $percents[$level] = [
                'name' => $names[$level],
                'count' => $count,
                'percent' => $all ? round(($count / $all) * 100, 2) : 0,
            ];
        }

        return $percents;
    }

    /**
     * List all logs.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function listLogs(Request $request)
    {
        page_title()->setTitle(trans('log-viewer::general.name'));

        $stats = LogViewer::statsTable();
        $headers = $stats->header();
        $rows = $this->paginate($stats->rows(), $request);

        return view('log-viewer::logs', compact('headers', 'rows'));
    }

    /**
     * Paginate logs.
     *
     * @param  array $data
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    protected function paginate(array $data, Request $request)
    {
        $page = $request->get('page', 1);
        $offset = ($page * $this->perPage) - $this->perPage;
        $items = array_slice($data, $offset, $this->perPage, true);
        $rows = new LengthAwarePaginator($items, count($data), $this->perPage, $page);

        $rows->setPath($request->url());

        return $rows;
    }

    /**
     * Show the log.
     *
     * @param  string $date
     *
     * @return \Illuminate\View\View
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function show($date)
    {
        page_title()->setTitle(trans('log-viewer::general.name') . ' ' . $date);

        $log = $this->getLogOrFail($date);
        $levels = LogViewer::levelsNames();
        $entries = $log->entries()->paginate($this->perPage);

        return view('log-viewer::show', compact('log', 'levels', 'entries'));
    }

    /**
     * @param $date
     * @return \Botble\LogViewer\Entities\Log|null
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    protected function getLogOrFail($date)
    {
        try {
            return LogViewer::get($date);
        } catch (LogNotFoundException $ex) {
            abort(404, $ex->getMessage());
        }
        return null;
    }

    /**
     * Filter the log entries by level.
     *
     * @param  string $date
     * @param  string $level
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function showByLevel($date, $level)
    {
        page_title()->setTitle(trans('log-viewer::general.name') . ' ' . $date);

        $log = $this->getLogOrFail($date);

        if ($level === 'all') {
            return redirect()->route($this->showRoute, [$date]);
        }

        $levels = LogViewer::levelsNames();
        $entries = LogViewer::entries($date, $level)->paginate($this->perPage);

        return view('log-viewer::show', compact('log', 'levels', 'entries'));
    }

    /**
     * Download the log
     *
     * @param  string $date
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function download($date)
    {
        return LogViewer::download($date);
    }

    /**
     * Delete a log.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function delete(Request $request)
    {
        if (!$request->ajax()) {
            abort(405, 'Method Not Allowed');
        }

        $date = $request->get('date');

        return response()->json([
            'result' => LogViewer::delete($date) ? 'success' : 'error'
        ]);
    }
}
