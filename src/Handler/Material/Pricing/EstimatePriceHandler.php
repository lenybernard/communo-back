<?php

declare(strict_types=1);

namespace App\Handler\Material\Pricing;

use App\Entity\Material\Booking\MaterialBooking;
use App\Entity\Material\Material;
use App\Entity\Material\Pricing;
use App\Entity\User;
use App\Handler\Material\Booking\FindAvailableDaysHandler;
use App\Handler\Material\Booking\FindPeriodsHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EstimatePriceHandler implements MessageHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FindAvailableDaysHandler $availableDaysHandler,
        private FindPeriodsHandler $findPeriodHandler,
        private FindPricingHandler $findPricingHandler
    ) {}

    public function __invoke(MaterialBooking $materialBooking, Material $material, \DateTime $startDate, \DateTime $endDate, User $user): float
    {
        $price = 0.0;
        $availableDays = ($this->availableDaysHandler)($material, $startDate, $endDate);
        dump($availableDays);
        $periods = ($this->findPeriodHandler)($materialBooking, array_values($availableDays));
        $pricing = ($this->findPricingHandler)($material, $user);
        foreach ($periods as $period) {
            $materialBooking->addPeriod($period);
            $numberOfDays = $period->getStartDate()->diff($period->getEndDate())->days + 1;
            $period->setPrice($numberOfDays * $pricing?->getValue());
            $price += $period->getPrice();
        }

        return $price;
    }
}