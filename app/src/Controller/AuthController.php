<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder, JWTTokenManagerInterface $jwtManager)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtManager = $jwtManager;
    }

    #[Route('api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json(['error' => 'Email ou mot de passe manquant'], 400);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Email ou mot de passe invalide'], 401);
        }

        // Création du token JWT
        $jwt = $this->jwtManager->create($user);

        // Configuration du cookie sécurisé avec JWT
        $response = $this->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'role' => $user->getRoles()[0] ?? 'ROLE_USER', // Prend le premier rôle
            ],
        ]);

        $response->headers->setCookie(
            new Cookie(
                'token',
                $jwt,
                time() + 3600,  // Expiration dans 1 heure
                '/',
                $_SERVER['HTTP_HOST'], // Définit le domaine actuel
                true,  // Secure (HTTPS obligatoire)
                true,  // HttpOnly (non accessible via JS)
                false,
                'None' // Cross-site
            )
        );

        return $response;
    }

    #[Route('api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        $response = $this->json(['success' => true, 'message' => 'Déconnexion réussie']);

        // Suppression du cookie JWT
        $response->headers->setCookie(
            new Cookie('token', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], true, true, false, 'None')
        );

        return $response;
    }
}
