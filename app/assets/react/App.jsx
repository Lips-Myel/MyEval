import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Home from './Home'; // Importation de Home.jsx

const App = () => {
    return (
        <Router>
            <Routes>
                {/* Définir la route par défaut */}
                <Route path="/" element={<Home />} />
            </Routes>
        </Router>
    );
};

export default App;
