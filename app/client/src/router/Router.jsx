import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Connexion from '../pages/connexion/Connexion.jsx';
import Inscription from '../pages/inscription/Inscription.jsx';
import EtudiantEspacePerso from '../pages/etudiant/etudiant-espace-perso/EtudiantEspacePerso.jsx';
import EtudiantAutoEvaluation from '../pages/etudiant/etudiant-auto-evaluation/EtudiantAutoEvaluation.jsx';
import EtudiantComparaison from '../pages/etudiant/etudiant-comparaison/EtudiantComparaison.jsx';
import Layout from '../layouts/layout/Layout.jsx';

function Router() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Connexion />} />
        <Route path="/inscription" element={<Inscription />} />
        <Route path="/" element={<Layout />} >
        <Route path="/etudiant-espace-perso" element={<EtudiantEspacePerso />} />
        <Route path="/etudiant-auto-evaluation" element={<EtudiantAutoEvaluation />} />
        <Route path="/etudiant-comparaison" element={<EtudiantComparaison />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default Router;
