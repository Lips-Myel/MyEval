import React from "react";
import logoMyEval from "../../assets/myeval-logo.png";
import "./Inscription.css";

function Inscription() {
    return (
        <>
            <section className="section_connexion">
            <h1>
                <span>M</span>y<span>E</span>val
            </h1>
                <h2>S'évaluer, visualiser, progresser.</h2>

                <form className="container_inputs">
                    <input
                        type="text"
                        placeholder="Nom"
                        required
                    />
                    <input
                        type="text"
                        placeholder="Prénom"
                    />
                    <input
                        type="email"
                        pattern=".+@example\.com"
                        placeholder="Email"
                        required
                    />
                    <input
                        type="password"
                        placeholder="Mot de passe"
                        required
                    />
                    <button>S'INSCRIRE</button>
                </form>
            </section>
        </>
    );
}

export default Inscription;
