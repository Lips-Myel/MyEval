<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Mettre à jour un utilisateur (y compris le mot de passe)
    #[Route('api/users/{id}', name: 'app_update_user', methods: ['PATCH'])]
    public function updateUser(int $id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['error' => 'Données invalides'], 400);
        }

        // Définition des contraintes de validation
        $constraints = new Assert\Collection([
            'firstName' => [new Assert\Optional([new Assert\NotBlank()])],
            'lastName' => [new Assert\Optional([new Assert\NotBlank()])],
            'email' => [new Assert\Optional([new Assert\NotBlank(), new Assert\Email()])],
            'password' => [new Assert\Optional([new Assert\NotBlank(), new Assert\Length(['min' => 8])])],
            'lastConnection' => [new Assert\Optional([new Assert\DateTime()])],
        ]);

        // Valider les données
        $errors = $validator->validate($data, $constraints);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        // Mise à jour des données si elles sont présentes
        if (isset($data['firstName'])) {
            $user->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }
        if (isset($data['email'])) {
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser && $existingUser->getId() !== $id) {
                return new JsonResponse(['error' => 'Cet email est déjà utilisé.'], 400);
            }
            $user->setEmail($data['email']);
        }

        if (isset($data['lastConnection'])) {
            $user->setLastConnection(new \DateTime($data['lastConnection']));
        }

        // Mise à jour du mot de passe si nécessaire
        if (isset($data['password'])) {
            // Validation du mot de passe
            $password = $data['password'];
            if (!preg_match('/[A-Z]/', $password)) {
                return new JsonResponse(['error' => 'Le mot de passe doit contenir au moins une lettre majuscule.'], 400);
            }
            if (!preg_match('/[0-9]/', $password)) {
                return new JsonResponse(['error' => 'Le mot de passe doit contenir au moins un chiffre.'], 400);
            }
            if (!preg_match('/[\W_]/', $password)) {
                return new JsonResponse(['error' => 'Le mot de passe doit contenir au moins un caractère spécial.'], 400);
            }

            // Hachage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);
        }

        // Sauvegarde des données
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur mis à jour avec succès']);
    }
}
