import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './FormateurAviss.css'
import Citation from './Citation';
import Avis from './Avis'
import MoyenneEleve from './MoyenneEleve'

function FormateurAviss() {
     return (
          <>
               <Citation />
               <button className='download'></button>
               <h3>Création de questionnaire</h3>
               <Avis />
               <MoyenneEleve />
               <div className='buttons'>
                    <button onClick={() => navigate ("/formateurtravailfait")}>Annuler</button>
                    <button onClick={() => navigate ("/formateurtravailfait")}>Modifier</button>
               </div>
          </>
     );
}

export default FormateurAviss;
