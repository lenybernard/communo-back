<?php

namespace App\Controller\Material\Booking;

use App\Entity\Material\Booking\MaterialBooking;
use App\Entity\Material\Material;
use App\Entity\User;
use App\Handler\Material\Pricing\EstimatePriceHandler;
use App\Repository\MaterialBookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetEstimate extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MaterialBookingRepository $materialBookingRepository)
    {
    }

    public function __invoke(Material $data, Request $request, EstimatePriceHandler $estimatePriceHandle): MaterialBooking
    {
        /** @var User $user */
        $user = $this->getUser();
        $startDate = new \DateTime($request->query->get('startDate'));
        $endDate = new \DateTime($request->query->get('endDate'));

        $booking = $this->materialBookingRepository->findOneBy([
            'material' => $data,
            'user' => $user,
            'status' => MaterialBooking::STATUS_ESTIMATED
        ]);

        if (!$booking instanceof MaterialBooking) {
            $booking = new MaterialBooking($data);
            $booking->setUser($user);
            $data->addBooking($booking);
            $this->entityManager->persist($booking);
        } else {
            foreach ($booking->getPeriods() as $period) {
                $this->entityManager->remove($period);
            }
        }
        $booking->setStartDate($startDate);
        $booking->setEndDate($endDate);
        $booking->setPrice(
            $estimatePriceHandle(
                materialBooking: $booking,
                material: $data,
                startDate: $startDate,
                endDate: $endDate,
                user: $user
            )
        );
        $this->entityManager->flush();

        return $booking;
    }
}