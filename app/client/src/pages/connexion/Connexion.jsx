import React, { useState } from "react";
import { Link } from 'react-router-dom';
import { useNavigate } from "react-router";
import { IoIosSend } from "react-icons/io";
import logoMyEval from "../../assets/myeval-logo.png";
import "./Connexion.css";

function Connexion() {
    const navigate = useNavigate();

    // Initialisation de l'état avec 'usermail' pour l'email
    const [user, setUser] = useState({
        email: '', password: ''
    });

    // Met à jour l'état de 'user' à chaque modification des inputs
    const handleInputs = e => {
        setUser(current => ({ ...current, [e.target.id]: e.target.value }));
    }

    // Envoie le formulaire lors de la soumission
    const handleSubmit = e => {
        e.preventDefault();
        fetch('http://localhost/api/login', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(user),
            credentials: "include",  // Utilisez "include" pour envoyer des cookies (équivalent de withCredentials: true)
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                    localStorage.setItem('token', data.token);
                    navigate('/etudiant-espace-perso');
            })
            .catch(console.error);
    }

    return (
        <>
            <nav>
                <Link to="/inscription"><IoIosSend />S’inscrire</Link>
            </nav>
            <section className="section_connexion">
                <div className="container_img">
                    <img src={logoMyEval} alt="logo du site MyEval" />
                </div>
                <h2>S'évaluer, visualiser, progresser.</h2>

                <form className="container_inputs" onSubmit={handleSubmit}>
                    <input
                        type="email"
                        id="email"  // Id correspondant à l'état
                        placeholder="Email"
                        value={user.email}  // Correspond à usermail dans l'état
                        onChange={handleInputs}
                        required
                    />
                    <input
                        type="password"
                        id="password"  // Id correspondant à l'état
                        placeholder="Mot de passe"
                        value={user.password}  // Correspond à password dans l'état
                        onChange={handleInputs}
                        required
                    />
                    <button type="submit">SE CONNECTER</button>
                </form>
            </section>
        </>
    );
}



export default Connexion;
