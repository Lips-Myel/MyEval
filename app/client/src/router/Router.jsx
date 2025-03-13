import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from '../context/AuthContext'; // Import du AuthProvider
import Connexion from '../pages/connexion/Connexion.jsx';
import Formateur from '../pages/formateur/Formateur.jsx';
import FormateurCreation from '../pages/formateur/FormateurCreation.jsx';
import FormateurModification from '../pages/formateur/FormateurModification.jsx';
import FormateurTravailFait from '../pages/formateur/FormateurTravailFait.jsx';
import FormateurAviss from '../pages/formateur/FormateurAviss.jsx';
import FormateurComparaison from '../pages/formateur/FromateurComparaison.jsx';
import Inscription from '../pages/inscription/Inscription.jsx';
import EtudiantEspacePerso from '../pages/etudiant/etudiant-espace-perso/EtudiantEspacePerso.jsx';
import EtudiantAutoEvaluation from '../pages/etudiant/etudiant-auto-evaluation/EtudiantAutoEvaluation.jsx';
import EtudiantComparaison from '../pages/etudiant/etudiant-comparaison/EtudiantComparaison.jsx';
import Layout from '../layouts/layout/Layout.jsx';
import Admin from '../pages/admin/Admin.jsx'


function Router() {
  return (
    <AuthProvider> {/* AuthProvider enveloppe toute l'application */}
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
    </AuthProvider>
  );
}

export default Router;
