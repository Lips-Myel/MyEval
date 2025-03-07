<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\Question;
use App\Entity\Responses;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function generateQuestions(): Response
    {
        // Questions en dur avec les bonnes réponses
        $multipleChoiceQuestions = [
            "Quel langage est principalement utilisé pour le développement web côté serveur ?" => [
                'answers' => ["PHP", "JavaScript", "CSS", "HTML"],
                'correct' => "PHP" // Bonne réponse
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
        
                // Affectation de la bonne réponse dans l'entité Question
                if (isset($questionData['correct'])) {
                    $question->setCorrectAnswer($questionData['correct']);
                }
        
                // Utilisation de TypeChoice::tryFrom pour obtenir l'instance de l'enum
                $questionType = TypeChoice::tryFrom($type);
                if (!$questionType) {
                    throw new \InvalidArgumentException("Type de question invalide : " . $type);
                }
        
                $question->setQuestionType($questionType);
                $this->entityManager->persist($question);
        
                // Traitement des réponses pour le type MULTIPLE_CHOICE
                if ($type === TypeChoice::MULTIPLE_CHOICE->value) {
                    $answers = $questionData['answers'];
                    // On suppose que $questionData['correct'] contient la bonne réponse
                    foreach ($answers as $answerText) {
                        $response = new Responses();
                        $response->setQuestion($question);
        
                        // Construction de la structure answerValue
                        $answerValue = [];
                        if ($answerText) {
                            $answerValue[AnswerValue::TEXT->value] = $answerText;
                        }
                        if (isset($questionData['note'])) {
                            $noteValue = (int)$questionData['note'];
                            $answerValue[AnswerValue::NOTE->value] = $noteValue;
                        }
                        if (!empty($answerValue)) {
                            $response->setAnswerValue($answerValue);
                        }
        
                        $response->setIsCorrect($answerText === $questionData['correct']);
                        $this->entityManager->persist($response);
                    }
                }
            }
        }
        
        $this->entityManager->flush();
        
        return new Response('Questions générées avec succès.');
    }        

    #[Route('/api/evaluation/{evaluationId}/submit', name: 'submit_answers', methods: ['POST'])]
    public function submitAnswers(int $evaluationId, Request $request): Response
    {
        $evaluation = $this->entityManager->getRepository(Evaluation::class)->find($evaluationId);
        if (!$evaluation) {
            return new Response('Évaluation introuvable.', 404);
        }

        $user = $this->getUser();
        if (!$user) {
            return new Response('Utilisateur non connecté.', 401);
        }

        $userAnswers = $request->request->get('answers');
        if (!is_array($userAnswers)) {
            return new Response('Les réponses doivent être un tableau.', 400);
        }

        foreach ($userAnswers as $questionId => $answer) {
            $question = $this->entityManager->getRepository(Question::class)->find($questionId);
            if ($question && !empty($answer)) {
                $response = new Responses();
                $response->setQuestion($question);

                // Construction de la structure answerValue pour l'énum AnswerValue
                $answerText = $answer['text'] ?? '';
                $noteValue = isset($answer['note']) ? (int)$answer['note'] : null;

                $answerValue = [];

                if ($answerText) {
                    $answerValue[AnswerValue::TEXT->value] = $answerText;
                }

                if ($noteValue !== null) {
                    $answerValue[AnswerValue::NOTE->value] = $noteValue;
                }

                if (!empty($answerValue)) {
                    $response->setAnswerValue($answerValue);
                }

                // Vérification de la réponse correcte
                $correctAnswer = $question->getCorrectAnswer();

                if ($answerText) {
                    $response->setIsCorrect($correctAnswer === $answerText);
                } elseif ($noteValue !== null) {
                    $response->setIsCorrect($correctAnswer === $noteValue);
                }

                $this->entityManager->persist($response);
            }
        }

        $evaluation->setStatus('completed');
        $this->entityManager->persist($evaluation);
        $this->entityManager->flush();

        return new Response('Réponses soumises avec succès.');
    }
}