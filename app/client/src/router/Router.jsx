import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Connexion from '../pages/connexion/Connexion.jsx';

function Router() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Connexion />} />
      </Routes>
    </BrowserRouter>
  );
}

export default Router;
