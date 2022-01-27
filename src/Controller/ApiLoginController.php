<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ApiLoginController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private JWTTokenManagerInterface $jwtManager,
        private RefreshTokenGeneratorInterface $refreshTokenGenerator,
        private int $refreshTokenTTL
    )
    {
    }

    #[Route('/api/login', name: 'api_login')]
    public function index(Request $request, UploaderHelper $helper): Response
    {
        if (null === $this->getUser()) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $token = $this->jwtManager->create($this->getUser());
        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($this->getUser(), $this->refreshTokenTTL);
        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();

        return $this->json([
            'user'  => [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'avatarUrl' => $helper->asset($user, 'avatarFile'),
            ],
            'token' => $token,
            'refreshToken' => $refreshToken->getRefreshToken(),
            'tokenExpiresAt' => $this->jwtManager->parse($token)['exp'] * 1000,
        ]);
    }
}
