<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(MaterialRepository $materialRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'materials' => $materialRepository->findAll(),
        ]);
    }
}
