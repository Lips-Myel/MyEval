/*
 * Bienvenue dans le fichier JavaScript principal de votre application !
 *
 * Nous vous recommandons d'inclure la version compilée de ce fichier JavaScript
 * (et de son fichier CSS) dans votre mise en page de base (base.html.twig).
 */

// Importation du CSS (Assurez-vous qu'il est correctement compilé en un seul fichier CSS)
import './styles/app.css';

// Importation de React et ReactDOM
import React from 'react';
import ReactDOM from 'react-dom/client';

// Importation du composant principal React (App)
import App from './react/App';

// Recherche de l'élément racine dans le HTML
const root = document.getElementById('react-root');

// Vérification que l'élément existe avant de rendre React
if (root) {
    ReactDOM.createRoot(root).render(<App />);
} else {
    console.error("L'élément racine React n'a pas été trouvé. Veuillez vérifier votre mise en page HTML.");
}
