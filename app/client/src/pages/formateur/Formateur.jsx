import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './Formateur.css'
import Citation from './Citation';
import Avis from './Avis';
import QuestionnaireEnCours from './QuestionnaireEnCours';
import QuestionnaireComplete from './QuestionnaireComplete';

function Formateur() {
    return (
        <>
            <Citation />
            <div>
                <div className='responsive'>
                    <button onClick={() => navigate("/formateurcreation")}>Créer questionnaire</button>
                </div>

                <div className='cree'>
                    <h4>Questionnaire en cours</h4>
                    <button onClick={() => navigate("/formateurcreation")}>Créer questionnaire</button>
                </div>

                <div onClick={() => navigate("/formateurmodification")} className='questionnaires'>
                    <QuestionnaireEnCours />
                    <QuestionnaireEnCours />
                </div>

            </div>

            <div>
                <h4 className='h4margin'>Questionnaire complété</h4>
                <div onClick={() => navigate("/formateurtravailfait")} className='questionnaires'>
                    <QuestionnaireComplete />
                    <QuestionnaireComplete />
                    <QuestionnaireComplete />
                    <QuestionnaireComplete />
                </div>

            </div>
        </>
    );
}

export default Formateur;
