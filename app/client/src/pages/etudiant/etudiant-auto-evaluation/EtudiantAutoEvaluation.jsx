import React, { useEffect, useState } from "react";
import { useParams, useNavigate, data } from "react-router-dom";
import "./EtudiantAutoEvaluation.css";
import { useAuth } from "../../../context/AuthContext";

function EtudiantAutoEvaluation() {
  const { token, userId } = useAuth();
  const { etudiantId, evaluationId } = useParams();
  const navigate = useNavigate();

  const [evaluation, setEvaluation] = useState(null);
  const [questions, setQuestions] = useState([]);
  const [responses, setResponses] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Chargement des questions depuis l'API
  useEffect(() => {
    fetch("http://localhost/api/questions")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur lors de la récupération des questions");
        }
        return response.json();
      })
      .then((data) => {
        setQuestions(data.member);
        setLoading(false);
      })
      .catch((err) => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  // Fonction pour calculer le score final basé sur les réponses de type "slider"
  const calculateFinalScore = () => {
    const sliderResponses = Object.values(responses).filter(
      (response) => response.note !== undefined
    );
    if (sliderResponses.length === 0) return null;
    const total = sliderResponses.reduce((sum, response) => sum + parseFloat(response.note), 0);
    return (total / sliderResponses.length).toFixed(2);
  };

  // Chargement des informations de l'évaluation et des questions associées
  useEffect(() => {
    const fetchEvaluation = async () => {
      try {
        const response = await fetch(`http://localhost/api/evaluations/${evaluationId}`, {
          headers: {
            "Authorization": `Bearer ${token}`
          },
          credentials: "include"
        });
        if (!response.ok) {
          throw new Error("Erreur lors de la récupération de l'évaluation");
        }
        const data = await response.json();
        console.log(data)
        setEvaluation(data);
        setQuestions(data.questions);
        setLoading(false);
      } catch (err) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchEvaluation();
  }, [evaluationId, token, etudiantId, userId]);

  // Gestion des changements dans les réponses
  const handleChange = (questionId, value) => {
    setResponses((prevResponses) => ({
      ...prevResponses,
      [questionId]: value,
    }));
  };

  // Soumission du formulaire
  const handleSubmit = async (event) => {
    event.preventDefault();

    const finalScore = calculateFinalScore();

    // Préparation des données à envoyer
    const dataToSend = {
      answers: responses,
      date: new Date().toISOString(),
      comment: "",
      finalScore: finalScore ? finalScore.toString() : null,
      teacher: evaluation.teacher,
      student: `/api/users/${etudiantId}`,
      responses: Object.entries(responses).map(([questionId, answer]) => ({
        questionId: questionId,
        answer: answer
      })),
      status: "completed",
      title: evaluation.title,
      questions: questions.map((question) => question.id)
    };

    try {
      const response = await fetch(`http://localhost/api/evaluations/${evaluationId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/ld+json",
          "Authorization": `Bearer ${token}`
        },
        body: JSON.stringify(dataToSend),
        credentials: "include",
      });
      if (response.ok) {
        navigate(`/etudiant/${etudiantId}/comparaison`);
      } else {
        console.error("Erreur lors de la soumission des réponses.");
      }
    } catch (error) {
      console.error("Erreur lors de la soumission des réponses:", error);
    }
  };

  if (loading) return <p>Chargement...</p>;
  if (error) return <p>Erreur : {error}</p>;

  return (
    <div className="etudiant-auto-evaluation">
      <h1>
        <span>M</span>y<span>E</span>val
      </h1>
      <h2>S’évaluer, visualiser, progresser.</h2>
      {evaluation && <h3>{evaluation.title}</h3>}

      <form onSubmit={handleSubmit}>
        <section className="section_auto_evaluation">
          {questions.map((question) => (
            <div key={question.id} className="container_question_auto_evaluation">
              <label htmlFor={`question-${question.id}`}>
                {question.questionText}
              </label>
              {question.questionType.toLowerCase() === "slider" && (
                <input
                  type="number"
                  id={`question-${question.id}`}
                  name={`answers[${question.id}][note]`}
                  min="0"
                  max="10"
                  required
                  onChange={(e) =>
                    handleChange(question.id, { note: e.target.value })
                  }
                />
              )}
              {question.questionType.toLowerCase() === "text" && (
                <textarea
                  id={`question-${question.id}`}
                  name={`answers[${question.id}][text]`}
                  required
                  onChange={(e) =>
                    handleChange(question.id, { text: e.target.value })
                  }
                />
              )}
              {question.questionType.toLowerCase() === "multiple_choice" &&
                question.answers && question.answers.length > 0 && (
                  <div>
                    {question.answers.map((answer, index) => (
                      <div key={index} className="multiple-choice-option">
                        <input
                          type="checkbox"
                          id={`question-${question.id}-answer-${index}`}
                          name={`answers[${question.id}][text][]`}
                          value={answer}
                          onChange={(e) => {
                            const currentAnswers =
                              responses[question.id]?.text || [];
                            const newAnswers = e.target.checked
                              ? [...currentAnswers, answer]
                              : currentAnswers.filter((a) => a !== answer);
                            handleChange(question.id, { text: newAnswers });
                          }}
                        />
                        <label htmlFor={`question-${question.id}-answer-${index}`}>
                          {answer}
                        </label>
                      </div>
                    ))}
                  </div>
                )}
            </div>
          ))}
        </section>
        <button type="submit">Soumettre</button>
      </form>
    </div>
  );
}

export default EtudiantAutoEvaluation;
