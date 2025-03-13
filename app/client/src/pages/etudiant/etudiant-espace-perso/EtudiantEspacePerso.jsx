import React, { useEffect, useState, } from "react";
import { Link, useParams } from "react-router-dom";
import "./EtudiantEspacePerso.css";
import { useAuth } from '../../../context/AuthContext';

function EtudiantEspacePerso() {
  const { token } = useAuth();
  const { userId } = useAuth(); // Récupérer userId du contexte Auth
  const { etudiantId } = useParams(); // Récupérer le paramètre :etudiantId de l'URL
  const [evaluations, setEvaluations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  

  // Chargement des évaluations depuis l'API
  useEffect(() => {
    fetch("http://localhost/api/evaluations", {
        method: "GET",
        headers: {
          "Content-Type": "application/ld+json",
          "Authorization": `Bearer ${token}` // Remplacez 'token' par votre jeton d'authentification
        },
        credentials: 'include'
      })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur lors de la récupération des évaluations");
        }
        return response.json();
      })
      .then((data) => {
        console.log(data)
        // Si la réponse est au format Hydra, le tableau se trouve dans "hydra:member"
        //const evaluationsArray = data["hydra:member"] || data;
        setEvaluations(data.member);
        setLoading(false);
      })
      .catch((err) => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  if (loading) return <p>Chargement...</p>;
  if (error) return <p>Erreur : {error}</p>;

  // Filtrer les évaluations en cours et complétées
  const evaluationsEnCours = evaluations.filter(
    (evalItem) => evalItem.status === "open"
  );
  const evaluationsCompletes = evaluations.filter(
    (evalItem) => evalItem.status !== "open"
  );

  return (
    <>
      <h1>
        <span>M</span>y<span>E</span>val
      </h1>
      <h2>S’évaluer, visualiser, progresser.</h2>

      <section className="section_espace_perso">
        <h4>Questionnaires en cours</h4>
        <div className="container_questionnaires_en_cours">
          {evaluationsEnCours.map((evaluation) => (
            <Link
              key={evaluation.id}
              className="card_questionnaire_en_cours"
              to={`/etudiant/${etudiantId}/auto-evaluation/${evaluation.id}`}
            >
              <h5>{evaluation.title}</h5>
              <p>{evaluation.comment}</p>
            </Link>
          ))}
        </div>

        <h4>Questionnaires complétés</h4>
        <div className="container_questionnaires_completes">
          {evaluationsCompletes.map((evaluation) => (
            <Link
              key={evaluation.id}
              className="card_questionnaire_completes"
              to={`/etudiant/${etudiantId}/auto-evaluation/${evaluation.id}`}
            >
              <h5>{evaluation.title}</h5>
              <p>{evaluation.comment}</p>
            </Link>
          ))}
        </div>
      </section>
    </>
  );
}

export default EtudiantEspacePerso;
