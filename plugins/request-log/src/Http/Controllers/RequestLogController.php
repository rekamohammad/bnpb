<?php

namespace Botble\RequestLog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\RequestLog\Repositories\Interfaces\RequestLogInterface;

class RequestLogController extends BaseController
{

    /**
     * @var RequestLogInterface
     */
    protected $requestLogRepository;

    /**
     * RequestLogController constructor.
     * @param RequestLogInterface $requestLogRepository
     */
    public function __construct(RequestLogInterface $requestLogRepository)
    {
        $this->requestLogRepository = $requestLogRepository;
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getWidgetRequestErrors()
    {
        $limit = request()->input('paginate', 10);
        $requests = $this->requestLogRepository->getModel()->paginate($limit);
        return [
            'error' => false,
            'data' => view('request-logs::widgets.request-errors', compact('requests', 'limit'))->render(),
        ];
    }
}