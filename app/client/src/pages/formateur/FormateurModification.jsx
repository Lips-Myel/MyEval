import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './FormateurModification.css'
import Citation from './Citation';
import Modification from './Modification';

function FormateurModification() {
     return (
          <>
               <Citation />
               <h3>Création de questionnaire</h3>
               <Modification />
               <div className='buttons'>
                    <button onClick={() => navigate ("/formateur")}>Annuler</button>
                    <button onClick={() => navigate ("/formateur")}>Modifier</button>
               </div>
          </>
     );
}

export default FormateurModification;
