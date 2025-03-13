import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from '../context/AuthContext'; // Import du AuthProvider
import Connexion from '../pages/connexion/Connexion.jsx';
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
        <Route path="/" element={<Connexion />} />
        <Route path="/inscription" element={<Inscription />} />
        <Route path="/" element={<Layout />} ></Route>
        <Route path="/etudiant-espace-perso" element={<EtudiantEspacePerso />} />
        <Route path="/etudiant-auto-evaluation" element={<EtudiantAutoEvaluation />} />
        <Route path="/etudiant-comparaison" element={<EtudiantComparaison />} />
        <Route path="/admin" element={<Admin />} />
      </Routes>
    </BrowserRouter>
    </AuthProvider>
  );
}

export default Router;
