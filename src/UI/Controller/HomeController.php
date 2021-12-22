<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Infra\Repository\EquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EquipmentRepository $equipmentRepository): Response
    {
        return $this->render('@Front/home/index.html.twig', [
            'equipments' => $equipmentRepository->findAll(),
        ]);
    }
}
