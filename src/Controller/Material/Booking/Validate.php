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
use Symfony\Component\Workflow\Registry;

#[AsController]
class Validate extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private Registry $workflowRegistry)
    {
    }

    public function __invoke(MaterialBooking $data, Request $request): MaterialBooking
    {
        $this->workflowRegistry->get($data, 'material_booking')->apply($data, MaterialBooking::TRANSITION_VALIDATE);
        $this->entityManager->flush();

        return $data;
    }
}