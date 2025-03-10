import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Connexion from '../pages/connexion/Connexion.jsx';
import Formateur from '../pages/formateur/Formateur.jsx';
import FormateurCreation from '../pages/formateur/FormateurCreation.jsx';
import FormateurModification from '../pages/formateur/FormateurModification.jsx';
import FormateurTravailFait from '../pages/formateur/FormateurTravailFait.jsx';
import FormateurAviss from '../pages/formateur/FormateurAviss.jsx';
import FormateurComparaison from '../pages/formateur/FromateurComparaison.jsx';

function Router() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/connexion" element={<Connexion />} />
        <Route path="/formateur" element={<Formateur />} />
        <Route path='/formateurcreation' element={<FormateurCreation />} />
        <Route path='/formateurmodification' element={<FormateurModification />} />
        <Route path='/formateurtravailfait' element={<FormateurTravailFait />} />
        <Route path='/formateuraviss' element={<FormateurAviss />} />
        <Route path='/formateurcomparaison' element={<FormateurComparaison />} />
      </Routes>
    </BrowserRouter>
  );
}

export default Router;
