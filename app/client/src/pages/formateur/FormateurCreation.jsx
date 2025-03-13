import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './FormateurCreation.css'
import Citation from './Citation';
import Creation from './Creation';

function FormateurCreation() {
     return (
          <>
               <Citation />
               <h3>Création de questionnaire</h3>
               <Creation />
               <div className='buttons'>
                    <button onClick={() => navigate ("/formateur")}>Annuler</button>
                    <button onClick={() => navigate ("/formateur")}>Valider</button>
               </div>
          </>
     );
}

export default FormateurCreation;
