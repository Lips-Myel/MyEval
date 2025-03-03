import React, { useState } from 'react';
import axios from 'axios';

function Connexion() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (!email || !password) {
      alert('Veuillez entrer une adresse e-mail et un mot de passe.');
      return;
    }

    try {
      const response = await axios.post(
        'https://localhost/api/login',
        { email, password },
        {
          headers: {
            'Content-Type': 'application/json',
            //'Authorization': `Bearer ${token}`,
          },
          withCredentials: true,  // Gère la session
        }
      );

      console.log('Connexion réussie:', response.data);

      // Traitement de la réponse après une connexion réussie
    } catch (error) {
      console.error('Erreur réseau ou serveur:', error);
      alert(error.response?.data?.message || 'Erreur lors de la connexion.');
    }
  };

  const handleLogout = async () => {
    try {
      const response = await axios.post(
        'https://localhost/api/logout',
        {},
        {
          withCredentials: true,  // Gère la session
        }
      );

      console.log('Déconnexion réussie:', response.data);
      alert('Vous êtes déconnecté.');

      // Redirection après déconnexion
      window.location.href = '/';
    } catch (error) {
      console.error('Erreur lors de la déconnexion:', error);
      alert('Erreur lors de la déconnexion.');
    }
  };

  return (
    <div>
      <h1>Connexion</h1>
      <form id="login-form" onSubmit={handleSubmit}>
        <div className="login-input">
          <label htmlFor="login-username">Identifiant</label>
          <input
            type="text"
            id="login-username"
            className="text-input"
            placeholder="Identifiant"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>

        <div className="login-input">
          <label htmlFor="login-password">Mot de passe</label>
          <input
            type="password"
            id="login-password"
            className="text-input"
            placeholder="Mot de passe"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>

        <input
          type="submit"
          id="login-submit"
          value="Connexion"
          className="red-button"
        />
      </form>

      <button id="logout" type="button" onClick={handleLogout}>
        Déconnexion
      </button>
    </div>
  );
}

export default Connexion;
