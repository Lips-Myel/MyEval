import React from "react";
import "./Admin.css";

function Admin() {
    return (
        <>
            <h1>
                <span>M</span>y<span>E</span>val
            </h1>
            <h2>S'évaluer, visualiser, progresser.</h2>

            <section className="section_admin">
                <form className="form_creation">
                    <input type="text" placeholder="Prénom" />
                    <input type="text" placeholder="Nom" />
                    <input type="email" id="" placeholder="Email" />
                    <input type="password" placeholder="Mot de passe" />
                    <button>Créer</button>
                </form>

                <form className="form_modification">
                    <div>
                        <label htmlFor="">Personne :</label>
                        <select name="" id="">
                            <option value="">
                                --Choisir une personne--
                            </option>
                        </select>
                    </div>

                    <div>
                        <label htmlFor="">Rôle :</label>
                        <select name="" id="">
                            <option value="">
                                --Choisir un rôle--
                            </option>
                        </select>
                    </div>

                    <div>
                        <label htmlFor="">Email :</label>
                        <input type="email" name="" id="" placeholder="Email" />
                    </div>

                    <div>
                        <label htmlFor="">Mot de passe :</label>
                        <input type="password" name="" id="" placeholder="Mot de passe" />
                    </div>
                    <div className="button_container">
                    <button>Modifier</button>
                    <button>Supprimer</button>
                    </div>
                </form>
            </section>
        </>
    );
}

export default Admin;
