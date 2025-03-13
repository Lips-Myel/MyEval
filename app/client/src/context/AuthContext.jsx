import React, { createContext, useState, useContext, useEffect } from 'react';
import Cookies from 'js-cookie';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [token, setToken] = useState(null);
  const [user, setUser] = useState(null);

  useEffect(() => {
    // Récupère le token depuis les cookies
    const storedToken = Cookies.get('token');
    if (storedToken) {
      setToken(storedToken);
      try {
        // Décode la deuxième partie du token (payload)
        const decodedToken = JSON.parse(atob(storedToken.split('.')[1]));
        setUser({ userId: decodedToken.id, email: decodedToken.email });
      } catch (error) {
        console.error('Erreur lors du décodage du token:', error);
      }
    }
  }, []);

  const login = (newToken) => {
    // Stocke le token dans un cookie sécurisé
    Cookies.set('token', newToken, { expires: 1, path: '', secure: true, sameSite: 'None' });
    setToken(newToken);
    try {
      const decodedToken = JSON.parse(atob(newToken.split('.')[1]));
      setUser({ userId: decodedToken.id, email: decodedToken.email });
    } catch (error) {
      console.error('Erreur lors du décodage du token:', error);
    }
  };

  const logout = () => {
    Cookies.remove('token');
    setToken(null);
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ token, user, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
