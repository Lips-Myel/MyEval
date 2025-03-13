import React from 'react';
import { Link, Outlet, useNavigate } from 'react-router';
import logoMyEval from '../../assets/myeval-logo.png';
import './Layout.css';
import { IoIosLogOut } from "react-icons/io";
import { CgProfile } from "react-icons/cg";



function Layout() {
  const navigate = useNavigate();

  const handleLogout = () => {
    localStorage.removeItem('token'); // Supprime le token du localStorage
    navigate('/'); // Redirige vers la page d'accueil ou une autre page
  };

  return (
    <>
      <header className='header'>
        <Link to="/etudiant-espace-perso" className='container_img_header rotating'>
          <img className='rotating' src={logoMyEval} alt="Logo MyEval" />
        </Link>
        <nav className='container_button_header'>
          <Link to="/etudiant-espace-perso"><CgProfile />Mon profil</Link>
          <Link to="/" onClick={handleLogout}><IoIosLogOut />
          Déconnexion</Link> {/* Garde le Link et appelle handleLogout */}
        </nav>
      </header>
      <Outlet />
    </>
  );
}

export default Layout;
