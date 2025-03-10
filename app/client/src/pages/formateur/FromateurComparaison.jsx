import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './FormateurComparaison.css'
import Citation from './Citation';

function FormateurComparaison() {
    return (
        <>
            <Citation />
            <button className='download'></button>
            <h3>Comparer les graphiques</h3>
            <div className='etudiant'></div>
            <div className='formateur'></div>
        </>
    );
}

export default FormateurComparaison;
