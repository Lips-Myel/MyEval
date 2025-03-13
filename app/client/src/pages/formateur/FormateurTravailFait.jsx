import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './FormateurTravailFait.css'
import Citation from './Citation';
import TravailFait from './TravailFait'
import MoyenneClasse from './MoyenneClasse'

function FormateurTravailFait() {
    return (
        <>
            <button onClick={() => navigate ("/formateur")}>Retour</button>
            <Citation />
            <h3>Travail fait</h3>
            <TravailFait />
            <MoyenneClasse />
        </>
    );
}

export default FormateurTravailFait;
