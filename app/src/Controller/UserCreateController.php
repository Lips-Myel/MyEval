<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing\Annotation\Route;

class UserCreateController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create-users', name: 'app_create_users', methods: ['POST'])]
    public function __invoke(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Récupérer les données JSON envoyées
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            throw new BadRequestHttpException('Invalid JSON');
        }

        // Définition des contraintes de validation
        $constraints = new Assert\Collection([
            'email' => [new Assert\NotBlank(), new Assert\Email()],
            'firstName' => [new Assert\NotBlank()],
            'lastName' => [new Assert\NotBlank()],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 8]),
                new Assert\Regex([
                    'pattern' => '/[A-Z]/', 
                    'message' => 'Le mot de passe doit contenir au moins une lettre majuscule.'
                ]),
                new Assert\Regex([
                    'pattern' => '/[0-9]/', 
                    'message' => 'Le mot de passe doit contenir au moins un chiffre.'
                ]),
                new Assert\Regex([
                    'pattern' => '/[\W_]/', 
                    'message' => 'Le mot de passe doit contenir au moins un caractère spécial.'
                ])
            ],
            'hasRole' => [new Assert\Optional()],
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

        // Vérifier si l'email existe déjà
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'Cet email est déjà utilisé.'], 400);
        }

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);

        // Récupérer ou définir un rôle par défaut (4 = Étudiant)
        $roleId = $data['hasRole'] ?? 1;

        // Validation du rôle dans la base de données
        $role = $this->entityManager->getRepository(Role::class)->find($roleId);
        if (!$role) {
            return new JsonResponse(['error' => 'Rôle non trouvé.'], 400);
        }

        // Assigner le rôle à l'utilisateur
        $user->setRole($role);

        // Hachage du mot de passe fourni par l'utilisateur
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);

        // Sauvegarde des données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur créé avec succès.']);
    }
}
