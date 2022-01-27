<?php

declare(strict_types=1);

namespace App\Handler\Material\Booking;

use App\Entity\Material\Booking\MaterialBookingDatePeriod;
use App\Entity\Material\Booking\MaterialBooking;
use DateTime;

class FindPeriodsHandler
{
    private static function tomorrow(\DateTime $today): \Datetime
    {
        $tomorrow = clone $today;
        $tomorrow->modify('+ 1 day');

        return $tomorrow;
    }

    /**
     * @param array<int, DateTime> $dates
     * @return array<int, MaterialBookingDatePeriod>
     */
    public function __invoke(MaterialBooking $materialBooking, array $dates): array
    {
        $datePeriods = [];
        $streak = 0;
        $firstDayOfPeriod = null;
        foreach ($dates as $date) {
            if ($streak === 0) {
                $firstDayOfPeriod = $date;
            }
            $streak++;
            if (!in_array(self::tomorrow($date), $dates, false)) { //tomorrow is not in array
                $datePeriods[] = new MaterialBookingDatePeriod($materialBooking, $firstDayOfPeriod, $date);
                $streak = 0;
            }
        }

        return $datePeriods;
    }
}