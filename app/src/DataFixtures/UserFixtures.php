<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Evaluation;
use App\Entity\Export;
use App\Entity\Question;
use App\Entity\Response;
use App\Entity\Statistique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\AnswerValue;
use App\Enum\TypeChoice;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Faker en français

        // Créer 3 rôles possibles
        $roles = [];
        $roleNames = ['Etudiant', 'Formateur', 'Administrateur'];
        foreach ($roleNames as $roleName) {
            $role = new Role();
            $role->setName($roleName);
            $manager->persist($role);
            $roles[] = $role;
        }

        // Créer 3 formations (Front, Back, CDA)
        $formations = [];
        $formationsNames = ['Front', 'Back', 'CDA'];
        foreach ($formationsNames as $formationName) {
            $formation = new Formation();
            $formation->setName($formationName);
            $manager->persist($formation);
            $formations[] = $formation;
        }

        // Créer 20 utilisateurs
        $users = [];
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());
            $user->setLastConnection($faker->dateTimeThisMonth());
            $user->setRole($roles[array_rand($roles)]);
            $user->addFormation($formations[array_rand($formations)]);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'mots2p@sse2025'));

            $manager->persist($user);
            $users[] = $user;
        }

        // Créer des questions posées par les formateurs
        $questions = [];
        foreach ($users as $teacher) {
            if ($teacher->getRole()->getName() === 'Formateur') {
                for ($j = 0; $j < 3; $j++) {
                    $question = new Question();
                    $question->setQuestionText($faker->sentence());
                    $question->setCreateBy($teacher); // Le formateur crée la question
                    $question->setQuestionType($faker->randomElement([TypeChoice::TEXT, TypeChoice::MULTIPLE_CHOICE, TypeChoice::SLIDER]));

                    $manager->persist($question);
                    $questions[] = $question;
                }
            }
        }

        // Créer des réponses des étudiants aux questions
        foreach ($users as $student) {
            if ($student->getRole()->getName() === 'Etudiant') {
                // Créer une évaluation pour chaque étudiant
                $evaluation = new Evaluation();
                $evaluation->setStudentId($student); // Lier l'évaluation à l'étudiant
                $evaluation->setTeacherId($users[array_rand($users)]); // Lier l'évaluation à un formateur aléatoire
                $evaluation->setDate(new \DateTime());
                $evaluation->setFinalScore($faker->randomFloat(1, 0, 10));
                $evaluation->setComment($faker->sentence());

                $manager->persist($evaluation);

                // Créer des réponses pour chaque question
                foreach ($questions as $question) {
                    // Vérifier si une réponse existe déjà pour cet étudiant et cette question
                    $existingResponse = $manager->getRepository(Response::class)
                        ->findOneBy([
                            'evaluationId' => $evaluation,
                            'questionId' => $question
                        ]);

                    if (!$existingResponse) { // Si aucune réponse n'existe déjà pour cette question et cette évaluation
                        $response = new Response();
                        $response->setEvaluationId($evaluation);  // Lier l'évaluation avec 'evaluationId'
                        $response->setQuestionId($question);  // Lier la question

                        // Utilisation de l'énumération AnswerValue
                        $response->setAnswerValue([$faker->randomElement([AnswerValue::TEXT, AnswerValue::NOTE])]);  // Passer un tableau

                        // Sauvegarder la réponse
                        $manager->persist($response);
                    }
                }
            }
        }

        // Créer des exports
        foreach ($users as $user) {
            $export = new Export();
            $export->setExportDate($faker->dateTimeThisMonth());
            $export->setUserId($user); // Relier l'export à l'utilisateur
            $export->setFilePath($faker->filePath());
            $manager->persist($export);
        }

        // Créer des statistiques
        foreach ($users as $student) {
            if ($student->getRole()->getName() === 'Etudiant') {
                $statistique = new Statistique();
                $statistique->setTrend([$faker->randomFloat(1, 0, 10), $faker->randomFloat(1, 0, 10)]);
                $statistique->setStudentId($student); // Relier la statistique à l'étudiant
                $statistique->setAverageScore($faker->randomFloat(1, 0, 10));
                $statistique->setStandardDeviation($faker->randomFloat(2, 0, 5));
                $manager->persist($statistique);
            }
        }

        // Sauvegarder toutes les entités après avoir vérifié les doublons
        $manager->flush();
    }
}
