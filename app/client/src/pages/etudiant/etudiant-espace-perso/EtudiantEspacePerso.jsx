import React from "react";
import { Link } from 'react-router-dom';
import "./EtudiantEspacePerso.css";

function EtudiantEspacePerso() {
    return (
        <>
            <h1>
                <span>M</span>y<span>E</span>val
            </h1>
            <h2>S’évaluer, visualiser, progresser.</h2>

            <section className="section_espace_perso">
                <h4>Questionnaires en cours</h4>
                <div className="container_questionnaires_en_cours">
                  <Link className="card_questionnaire_en_cours" to="/etudiant-auto-evaluation"></Link>
                  <Link className="card_questionnaire_en_cours" to="/etudiant-auto-evaluation"></Link>
                  <Link className="card_questionnaire_en_cours" to="/etudiant-auto-evaluation"></Link>
                  <Link className="card_questionnaire_en_cours" to="/etudiant-auto-evaluation"></Link>
                  <Link className="card_questionnaire_en_cours" to="/etudiant-auto-evaluation"></Link>
                  <Link className="card_questionnaire_en_cours" to="/etudiant-auto-evaluation"></Link>
                </div>

                <h4>Questionnaires complétés</h4>
                <div className="container_questionnaires_completes">
                <Link className="card_questionnaire_completes" to=""></Link>
                </div>
            </section>
        </>
    );
}

export default EtudiantEspacePerso;
