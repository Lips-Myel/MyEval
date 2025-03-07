import React from "react";
import { Link } from 'react-router-dom';
import "./EtudiantAutoEvaluation.css";

function EtudiantAutoEvaluation() {
    return (
        <>
            <h1>
                <span>M</span>y<span>E</span>val
            </h1>
            <h2>S’évaluer, visualiser, progresser.</h2>

            <h3>Questionnaire (nom du questionnaire)</h3>

            <section className="section_auto_evaluation">
                <div className="container_question_auto_evaluation">
                    <label htmlFor="">Question</label>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Laudantium alias cupiditate necessitatibus ?
                    </p>
                    <input type="number" min="0" max="10" />
                </div>

                <div className="container_question_auto_evaluation">
                    <label htmlFor="">Question</label>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Laudantium alias cupiditate necessitatibus ?
                    </p>
                    <input type="number" min="0" max="10" />
                </div>

                <div className="container_question_auto_evaluation">
                    <label htmlFor="">Question</label>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Laudantium alias cupiditate necessitatibus ?
                    </p>
                    <input type="number" min="0" max="10" />
                </div>

                <div className="container_question_auto_evaluation">
                    <label htmlFor="">Question</label>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Laudantium alias cupiditate necessitatibus ?
                    </p>
                    <input type="number" min="0" max="10" />
                </div>

                <div className="container_question_auto_evaluation">
                    <label htmlFor="">Question</label>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Laudantium alias cupiditate necessitatibus ?
                    </p>
                    <input type="number" min="0" max="10" />
                </div>

                <div className="container_question_auto_evaluation">
                    <label htmlFor="">Question</label>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Laudantium alias cupiditate necessitatibus ?
                    </p>
                    <input type="number" min="0" max="10" />
                </div>
            </section>

            <section className="section_statistique_auto_evaluation">
                <Link className="link_statistique_auto_evaluation" to="/etudiant-comparaison"></Link>
            </section>
        </>
    );
}

export default EtudiantAutoEvaluation;
