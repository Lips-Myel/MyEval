<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\Question;
use App\Entity\Responses;
use App\Entity\User;
use App\Entity\Statistique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\AnswerValue;
use App\Enum\TypeChoice;

class QuestionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route("/api/question/generate", name: "generate_question")]
    public function generateQuestions(): JsonResponse
    {
        // Récupérer un formateur (role = 2)
        $teacher = $this->entityManager->getRepository(User::class)->findOneBy(['role' => 2]);
        if (!$teacher) {
            return new JsonResponse(['error' => 'Aucun formateur trouvé.'], 404);
        }
    
        // Création d'une nouvelle évaluation liée à ce formateur
        $evaluation = new Evaluation();
        $evaluation->setDate(new \DateTime());
        $evaluation->setComment('Test');
        $evaluation->setStatus('open');
        $evaluation->setFinalScore(0.0);
        $evaluation->setTitle('questionaire test');
        $evaluation->setTeacher($teacher); // Assurez-vous que la méthode setTeacherId() existe et est correctement mappée
        $this->entityManager->persist($evaluation);
    
        // Définition des questions en dur
        $multipleChoiceQuestions = [
            "Quel langage est principalement utilisé pour le développement web côté serveur ?" => [
                'answers' => ["PHP", "JavaScript", "CSS", "HTML"],
                'correct' => "PHP"
            ],
            "Quelle base de données est relationnelle ?" => [
                'answers' => ["MySQL", "MongoDB", "Firebase", "Redis"],
                'correct' => "MySQL"
            ],
            "Quels sont des principes fondamentaux du développement orienté objet ?" => [
                'answers' => ["Encapsulation", "Héritage", "Polymorphisme", "Compilation"],
                'correct' => "Encapsulation"
            ],
            "Quels outils sont couramment utilisés pour la gestion de version en développement ?" => [
                'answers' => ["Git", "SVN", "Docker", "Jenkins"],
                'correct' => "Git"
            ],
            "Quelles sont des bonnes pratiques en sécurité web ?" => [
                'answers' => ["Hashage des mots de passe", "Stocker les mots de passe en clair", "Utiliser des requêtes SQL sans validation", "Échapper les entrées utilisateur"],
                'correct' => "Hashage des mots de passe"
            ],
            "Quels frameworks sont utilisés pour le développement en PHP ?" => [
                'answers' => ["Symfony", "Laravel", "Django", "Vue.js"],
                'correct' => "Symfony"
            ],
            "Quelles sont des étapes clés dans un cycle de développement logiciel ?" => [
                'answers' => ["Analyse des besoins", "Déploiement", "Codage", "Vérification des emails"],
                'correct' => "Analyse des besoins"
            ],
            "Quels sont des outils d'intégration continue ?" => [
                'answers' => ["Jenkins", "Travis CI", "Postman", "Webpack"],
                'correct' => "Jenkins"
            ],
            "Quels éléments permettent d’optimiser les performances d’une application web ?" => [
                'answers' => ["Cache", "Compression des images", "Écriture de code non optimisé", "Minification du CSS"],
                'correct' => "Cache"
            ],
            "Quels statuts HTTP indiquent une erreur côté serveur ?" => [
                'answers' => ["500", "404", "200", "403"],
                'correct' => "500"
            ]
        ];
    
        $textQuestions = [
            "Expliquez le fonctionnement d'un serveur web." => "Un serveur web est un logiciel qui reçoit des requêtes HTTP des clients et renvoie des réponses sous forme de pages HTML, JSON, etc.",
            "Décrivez les avantages du développement orienté objet." => "Réutilisabilité, modularité, facilité de maintenance et scalabilité.",
            "Quels sont les principaux défis du développement web ?" => "Performance, sécurité, compatibilité entre navigateurs, gestion des bases de données.",
            "Comment garantir la sécurité des données des utilisateurs ?" => "Utilisation du chiffrement, validation des entrées, protection contre les attaques XSS et SQL Injection.",
            "Pourquoi est-il important d'écrire du code propre et documenté ?" => "Facilite la maintenance, améliore la collaboration, réduit les erreurs et le temps de développement.",
            "Quelles sont les différences entre SQL et NoSQL ?" => "SQL est relationnel avec des tables et des relations, NoSQL est non relationnel et basé sur des documents, colonnes, graphes ou clés-valeurs.",
            "Comment fonctionne un algorithme de tri ?" => "Il organise une liste d'éléments dans un ordre spécifique en utilisant des comparaisons et des permutations.",
            "Quels sont les avantages de l’architecture microservices ?" => "Scalabilité, modularité, indépendance des services, facilité de maintenance.",
            "Comment optimiser une base de données pour de meilleures performances ?" => "Indexation, normalisation, requêtes optimisées, partitionnement.",
            "Expliquez le concept de CI/CD dans le développement logiciel." => "CI/CD automatise le test et le déploiement du code pour améliorer la qualité et réduire les erreurs humaines."
        ];
    
        $sliderQuestions = [
            "Sur une échelle de 1 à 10, évaluez votre maîtrise de PHP." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre compréhension de Symfony." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre expérience avec les bases de données relationnelles." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre capacité à résoudre des problèmes complexes en programmation." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre niveau de connaissance en sécurité informatique." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre compréhension du modèle MVC." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre aisance avec Git et la gestion de versions." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre maîtrise des API RESTful." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre capacité à optimiser les performances d’une application." => rand(1, 10),
            "Sur une échelle de 1 à 10, évaluez votre confort avec le travail en équipe dans un projet logiciel." => rand(1, 10)
        ];
    
        $questionsData = [
            TypeChoice::MULTIPLE_CHOICE->value => $multipleChoiceQuestions,
            TypeChoice::TEXT->value            => $textQuestions,
            TypeChoice::SLIDER->value          => $sliderQuestions
        ];
        
        foreach ($questionsData as $type => $questions) {
            foreach ($questions as $questionText => $questionData) {
                $question = new Question();
                $question->setQuestionText($questionText);
                
                // Pour les questions à choix multiples, stocker le tableau des réponses dans la colonne "answer"
                if ($type === TypeChoice::MULTIPLE_CHOICE->value && isset($questionData['answers'])) {
                    $question->setAnswers($questionData['answers']); // Vérifiez que cette méthode existe dans l'entité Question
                }
                
                if (isset($questionData['correct'])) {
                    $question->setCorrectAnswer($questionData['correct']);
                }
                
                $questionType = TypeChoice::tryFrom($type);
                if (!$questionType) {
                    throw new \InvalidArgumentException("Type de question invalide : " . $type);
                }
                
                $question->setQuestionType($questionType);
                $this->entityManager->persist($question);
                
                // Pour les questions à choix multiples, générer des enregistrements dans la table Responses
                if ($type === TypeChoice::MULTIPLE_CHOICE->value) {
                    $answers = $questionData['answers'];
                    foreach ($answers as $answerText) {
                        $response = new Responses();
                        $response->setQuestion($question); // L'id de la question sera ainsi associé à la réponse
                        $answerValue = [];
                        if ($answerText) {
                            $answerValue[AnswerValue::TEXT->value] = $answerText;
                        }
                        // Optionnel : si vous souhaitez ajouter une note à chaque option, vérifiez si 'note' est défini
                        if (isset($questionData['note'])) {
                            $noteValue = (int)$questionData['note'];
                            $answerValue[AnswerValue::NOTE->value] = $noteValue;
                        }
                        if (!empty($answerValue)) {
                            $response->setAnswerValue($answerValue);
                        }
                        // Définir la réponse comme correcte si elle correspond à la bonne réponse
                        $response->setIsCorrect($answerText === $questionData['correct']);
                        $this->entityManager->persist($response);
                    }
                }
            }
        }
        
        $this->entityManager->flush();
        
        return new JsonResponse([
            'message' => 'Questions générées avec succès.',
            'evaluation_id' => $evaluation->getId()
        ]);
    }
    

    #[Route('/api/evaluation/create-test', name: 'test_evaluation_creation', methods: ['GET'])]
    public function testEvaluationCreation(): JsonResponse
    {
        // Récupération d'un formateur (role = 2) parmi les utilisateurs
        $teacher = $this->entityManager->getRepository(User::class)->findOneBy(['role' => 2]);
        if (!$teacher) {
            return new JsonResponse(['error' => 'Aucun formateur trouvé.'], 404);
        }
    
        // Création d'une nouvelle évaluation avec ce formateur
        $evaluation = new Evaluation();
        $evaluation->setDate(new \DateTime());
        $evaluation->setComment('Évaluation de test');
        $evaluation->setStatus('open');
        $evaluation->setFinalScore(0.0);
        $evaluation->setTeacher($teacher);
        $this->entityManager->persist($evaluation);
    
        // Sélection de 3 étudiants (role = 1)
        $students = $this->entityManager->getRepository(User::class)->findBy(['role' => 1], null, 3);
        if (count($students) < 3) {
            return new JsonResponse(['error' => 'Moins de 3 étudiants trouvés.'], 404);
        }
    
        // Récupération de toutes les questions existantes
        $questions = $this->entityManager->getRepository(Question::class)->findAll();
    
        $statistics = [];
        // Pour chaque étudiant, simuler des réponses et calculer des statistiques sur les questions de type slider
        foreach ($students as $student) {
            $totalScore = 0;
            $countScore = 0;
            $scores = [];
    
            foreach ($questions as $question) {
                $response = new Responses();
                $response->setQuestion($question);
                // Affecter l'étudiant à la réponse
                $response->setStudent($student);
    
                // Simulation en fonction du type de question
                if ($question->getQuestionType()->value === 'slider') {
                    $note = rand(0, 10);
                    $response->setAnswerValue([AnswerValue::NOTE->value => $note]);
                    $response->setIsCorrect(false);
                    $scores[] = $note;
                    $totalScore += $note;
                    $countScore++;
                } elseif ($question->getQuestionType()->value === 'text') {
                    $response->setAnswerValue([AnswerValue::TEXT->value => 'Réponse de test']);
                    // Pour les questions de type texte, nous considérons par défaut la réponse comme non correcte
                    $response->setIsCorrect(false);
                } elseif ($question->getQuestionType()->value === 'multiple_choice') {
                    // Pour simplifier, on sélectionne la bonne réponse
                    $response->setAnswerValue([AnswerValue::TEXT->value => $question->getCorrectAnswer()]);
                    $response->setIsCorrect(true);
                }
                $this->entityManager->persist($response);
            }
    
            // Calcul des statistiques sur les réponses de type slider
            if ($countScore > 0) {
                $average = $totalScore / $countScore;
                $variance = 0;
                foreach ($scores as $score) {
                    $variance += pow($score - $average, 2);
                }
                $variance = $variance / $countScore;
                $stdDev = sqrt($variance);
                // Définir la tendance par exemple comme [min, max]
                $trend = [min($scores), max($scores)];
            } else {
                $average = 0;
                $stdDev = 0;
                $trend = [0, 0];
            }
    
            // Création d'une entrée dans la table statistique pour cet étudiant
            $statistic = new Statistique();
            $statistic->setStudent($student);
            $statistic->setTrend($trend); // à stocker en JSON ou tableau selon votre mapping
            $statistic->setAverageScore($average);
            $statistic->setStandardDeviation($stdDev);
            $statistic->setTeacherScore(0.0); // initialement à 0.0
            $this->entityManager->persist($statistic);
    
            $statistics[] = [
                'student' => $student->getId(),
                'trend' => $trend,
                'average_score' => $average,
                'standard_deviation' => $stdDev,
                'teacher_score' => 0.0
            ];
        }
    
        $this->entityManager->flush();
    
        return new JsonResponse([
            'message' => 'Évaluation de test créée avec succès.',
            'evaluation_id' => $evaluation->getId(),
            'statistics' => $statistics
        ]);
    }
    

         #[Route('/api/evaluations/{id}', name: 'submit_answers', methods: ['POST'])]
        public function submitAnswers(int $id, Request $request): JsonResponse
        {
            $evaluation = $this->entityManager->getRepository(Evaluation::class)->find($id);
            if (!$evaluation) {
                return new JsonResponse(['error' => 'Évaluation introuvable.'], 404);
            }

            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['error' => 'Utilisateur non connecté.'], 401);
            }

            $userAnswers = json_decode($request->getContent(), true)['answers'] ?? [];
            if (!is_array($userAnswers) || empty($userAnswers)) {
                return new JsonResponse(['error' => 'Les réponses doivent être un tableau non vide.'], 400);
            }

            // Démarrer une transaction pour garantir l'intégrité des données
            $this->entityManager->beginTransaction();
            try {
                foreach ($userAnswers as $questionId => $answer) {
                    $question = $this->entityManager->getRepository(Question::class)->find($questionId);
                    if (!$question) {
                        return new JsonResponse(['error' => "Question ID $questionId introuvable."], 400);
                    }

                    $response = new Responses();
                    $response->setQuestion($question);
                    $response->setStudent($user);

                    $answerText = $answer['text'] ?? null;
                    $noteValue = isset($answer['note']) ? (int)$answer['note'] : null;
                    $answerValue = [];

                    if ($answerText !== null && $answerText !== '') {
                        $answerValue[AnswerValue::TEXT->value] = $answerText;
                    }
                    if ($noteValue !== null) {
                        $answerValue[AnswerValue::NOTE->value] = $noteValue;
                    }

                    if (!empty($answerValue)) {
                        $response->setAnswerValue($answerValue);
                    } else {
                        return new JsonResponse(['error' => "Réponse invalide pour la question ID $questionId."], 400);
                    }

                    $correctAnswer = $question->getCorrectAnswer();
                    if ($question->getQuestionType()->value === 'text') {
                        $response->setIsCorrect($answerText === $correctAnswer);
                    } elseif ($question->getQuestionType()->value === 'slider') {
                        $response->setIsCorrect(false); // Les sliders ne peuvent pas être corrigés automatiquement
                    } elseif ($question->getQuestionType()->value === 'multiple_choice') {
                        $response->setIsCorrect($answerText === $correctAnswer);
                    } else {
                        $response->setIsCorrect(false);
                    }

                    $this->entityManager->persist($response);
                }

                // Marquer l'évaluation comme complétée
                $evaluation->setStatus('completed');
                $this->entityManager->persist($evaluation);

                // Valider la transaction
                $this->entityManager->flush();
                $this->entityManager->commit();

                return new JsonResponse(['message' => 'Réponses soumises avec succès.'], 201);
            } catch (\Exception $e) {
                $this->entityManager->rollback();
                return new JsonResponse(['error' => 'Erreur lors de l\'enregistrement des réponses.'], 500);
            }
        }
}