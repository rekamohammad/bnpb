<?php

namespace Botble\Analytics;

use Carbon\Carbon;
use DateTime;
use Botble\Analytics\Exceptions\InvalidPeriod;

class Period
{
    /**
     * @var DateTime
     */
    public $startDate;

    /**
     * @var DateTime
     */
    public $endDate;

    /**
     * @param DateTime $startDate
     * @param $endDate
     * @return static
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public static function create(DateTime $startDate, $endDate)
    {
        return new static($startDate, $endDate);
    }

    /**
     * @param $numberOfDays
     * @return static
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public static function days($numberOfDays)
    {
        $endDate = Carbon::today();

        $startDate = Carbon::today()->subDays($numberOfDays)->startOfDay();

        return new static($startDate, $endDate);
    }

    /**
     * Period constructor.
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @throws InvalidPeriod
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public function __construct(DateTime $startDate, DateTime $endDate)
    {
        if ($startDate > $endDate) {
            throw InvalidPeriod::startDateCannotBeAfterEndDate($startDate, $endDate);
        }

        $this->startDate = $startDate;

        $this->endDate = $endDate;
    }
}
