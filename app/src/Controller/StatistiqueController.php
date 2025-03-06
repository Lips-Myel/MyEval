<?php
namespace App\Controller;

use App\Entity\Statistique;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/statistiques/{studentId}', name: 'generate_statistiques', methods: ['POST'])]
    public function generateStatistiques(int $studentId, Request $request): Response
    {
        // Récupérer l'utilisateur (étudiant) en fonction de l'ID
        $student = $this->entityManager->getRepository(User::class)->find($studentId);

        if (!$student) {
            return new Response('Étudiant non trouvé.', 404);
        }

        // Calculer la tendance des réponses, la moyenne et l'écart-type (exemple)
        $trend = $this->calculateTrend($student);
        $averageScore = $this->calculateAverageScore($student);
        $standardDeviation = $this->calculateStandardDeviation($student);

        // Créer un objet Statistique
        $statistique = new Statistique();
        $statistique->setStudentId($student);
        $statistique->setTrend($trend);
        $statistique->setAverageScore($averageScore);
        $statistique->setStandardDeviation($standardDeviation);

        // Persister la nouvelle statistique
        $this->entityManager->persist($statistique);
        $this->entityManager->flush();

        return new Response('Statistiques générées avec succès.');
    }

    #[Route('/statistiques/{studentId}/teacher_score', name: 'add_teacher_score', methods: ['POST'])]
    public function addTeacherScore(int $studentId, Request $request): Response
    {
        // Récupérer l'utilisateur (étudiant) en fonction de l'ID
        $student = $this->entityManager->getRepository(User::class)->find($studentId);

        if (!$student) {
            return new Response('Étudiant non trouvé.', 404);
        }

        // Vérifier que l'utilisateur est bien un enseignant
        $user = $this->getUser();
        if (!$user || !in_array('ROLE_TEACHER', $user->getRoles())) {
            return new Response('Accès non autorisé. Vous devez être un enseignant.', 403);
        }

        // Récupérer la note de l'enseignant depuis la requête
        $teacherScore = $request->request->get('teacher_score');
        $teacherScore = floatval($teacherScore);  // Convertir en flottant

        // Vérifier que la note est valide : entre 0 et 10, avec une seule décimale
        if (!is_numeric($teacherScore) || $teacherScore < 0 || $teacherScore > 10 || round($teacherScore, 1) != $teacherScore) {
            return new Response('La note de l\'enseignant doit être un nombre entre 0 et 10 avec une seule décimale.', 400);
        }

        // Récupérer ou créer les statistiques de l'étudiant
        $statistique = $this->entityManager->getRepository(Statistique::class)->findOneBy(['studentId' => $student]);

        if (!$statistique) {
            return new Response('Statistiques de l\'étudiant non trouvées.', 404);
        }

        // Ajouter la note de l'enseignant
        $statistique->setTeacherScore((string)$teacherScore);
        $this->entityManager->persist($statistique);
        $this->entityManager->flush();

        // Comparer les notes
        $studentAverage = (float) $statistique->getAverageScore();
        $teacherScore = (float) $statistique->getTeacherScore();

        $comparison = $this->compareScores($studentAverage, $teacherScore);

        return new Response("Note de l'enseignant enregistrée. " . $comparison);
    }

    // Méthode pour comparer les notes
    private function compareScores(float $studentScore, float $teacherScore): string
    {
        if ($studentScore > $teacherScore) {
            return "La note de l'étudiant est supérieure à celle de l'enseignant.";
        } elseif ($studentScore < $teacherScore) {
            return "La note de l'étudiant est inférieure à celle de l'enseignant.";
        } else {
            return "La note de l'étudiant est égale à celle de l'enseignant.";
        }
    }

    // Exemple de méthode pour calculer la tendance
    private function calculateTrend(User $student): array
    {
        return ['positive', 'negative', 'neutral']; // Exemple de tendance
    }

    // Méthode pour calculer la moyenne des scores, avec contrainte entre 0 et 10 et une décimale
    private function calculateAverageScore(User $student): string
    {
        // Exemple d'une moyenne qui sera toujours entre 0 et 10 avec une décimale
        $averageScore = 8.4; // Exemple, cette valeur serait calculée dynamiquement
        return number_format($averageScore, 1);  // Assurer qu'on a une décimale
    }

    // Exemple de méthode pour calculer l'écart-type des réponses
    private function calculateStandardDeviation(User $student): float
    {
        return 2.5;  // Exemple d'écart-type
    }
}
