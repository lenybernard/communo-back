<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private JWTTokenManagerInterface $jwtManager)
    {
    }

    #[Route('/api/login', name: 'api_login')]
    public function index(): Response
    {
        if (null === $this->getUser()) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $token = $this->jwtManager->create($this->getUser());

        return $this->json([
            'user'  => [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
            ],
            'token' => $token,
        ]);
    }
}
