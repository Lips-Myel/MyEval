import React, { useState } from "react";
import { useNavigate } from "react-router";
import "./Inscription.css";

function Inscription() {
    const navigate = useNavigate();

    const [user, setUser] = useState({
        firstName: '',
        lastName: '',
        email: '',
        password: ''
    });

    const [error, setError] = useState('');

    const handleInputs = (e) => {
        setUser(current => ({ ...current, [e.target.id]: e.target.value }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        fetch('http://localhost/api/create-users', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(user),
            credentials: "include",
        })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return res.json();
            })
            .then(data => {
                console.log(data);
                localStorage.setItem('token', data.token);
                navigate('/etudiant-espace-perso');
            })
            .catch(err => {
                setError(err.message);
                console.error(err);
            });
    };

    return (
        <section className="section_connexion">
            <h1>
                <span>M</span>y<span>E</span>val
            </h1>
            <h2>S'évaluer, visualiser, progresser.</h2>

            {error && <p className="error">{error}</p>}

            <form className="container_inputs" onSubmit={handleSubmit}>
                <input
                    type="text"
                    placeholder="Nom"
                    required
                    id="firstName"
                    value={user.firstName}
                    onChange={handleInputs}
                />
                <input
                    type="text"
                    placeholder="Prénom"
                    id="lastName"
                    value={user.lastName}
                    onChange={handleInputs}
                />
                <input
                    type="email"
                    placeholder="Email"
                    required
                    id="email"
                    value={user.email}
                    onChange={handleInputs}
                />
                <input
                    type="password"
                    placeholder="Mot de passe"
                    required
                    id="password"
                    value={user.password}
                    onChange={handleInputs}
                />
                <button type="submit">S'INSCRIRE</button>
            </form>
        </section>
    );
}

export default Inscription;
