<?php

declare(strict_types=1);

namespace App\Handler\Material\Booking;

use App\Entity\Material\Material;
use App\Repository\MaterialBookingRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindAvailableDaysHandler implements MessageHandlerInterface
{
    public function __construct(private MaterialBookingRepository $bookingRepository) {}

    /**
     * @return array<int, \DateTime>
     */
    public function __invoke(Material $material, \DateTime $startDate, \DateTime $endDate): array
    {
        $existingBookings = $this->bookingRepository->findByMaterialBetweenDates(
            $material->getId(),
            $startDate,
            $endDate,
        );

        $availableDates = [];
        $from = clone $startDate;
        while ($from <= $endDate) {
            $availableDates[$from->format('Y-m-d')] = clone $from;
            $from->modify('+1day');
        }
        $availableDates[$from->format('Y-m-d')] = clone $from;

        foreach ($existingBookings as $existingBooking) {
            $from = new \DateTime($existingBooking['start_date']);
            $to = new \DateTime($existingBooking['end_date']);
            while ($from <= $to) {
                if (array_key_exists($from->format('Y-m-d'), $availableDates)) {
                    unset($availableDates[$from->format('Y-m-d')]);
                }
                $from->modify('+1day');
            }

        }

        return $availableDates;
    }
}