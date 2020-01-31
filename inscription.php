<?php
require_once 'inc/init.php';

$affiche_formulaire = true; // Pour afficher le formulaire tant que le mon membre n'est pas inscrit


    // Traitement du formulaire :
debug($_POST);
        if(!empty($_POST)){ // si on a cliqué sur s'inscrire
            // on valide tout les champs du formulair :
            if(!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le pseudo doit contenir entre 4 et 20 caracteres.</div>';
            }
            if(!isset($_POST['mdp']) || strlen($_POST['mdp']) < 8 || strlen($_POST['mdp']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le mot de passe doit contenir entre 8 et 20 caracteres.</div>';
            }
            if(!isset($_POST['nom']) || strlen($_POST['nom']) < 2 || strlen($_POST['nom']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le nom doit contenir entre 2 et 20 caracteres.</div>';
            }
            if(!isset($_POST['prenom']) || strlen($_POST['prenom']) < 2 || strlen($_POST['prenom']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le prenom doit contenir entre 2 et 20 caracteres.</div>';
            }
            if(!isset($_POST['telephone']) || strlen($_POST['telephone']) != 10 ){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le prenom doit contenir entre 2 et 20 caracteres.</div>';
            }
            if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ // la fonction predefinie avec le parametre filter_var() avec 
                // le parametre FILTER_VALIDATE_EMAIL valide si le string fourni est bien un email
                $contenu .= '<div class="alert alert-danger">l\'email est invalide</div>';
            }
            if(!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')  ){// si la civilité est diffente de 'm' et 'f' en meme temps
                $contenu .= '<div class="alert alert-danger">La civilité est invalide</div>';
            }
           

         
            // S'il n'y a plus d'erreur sur le formulaire, on verifie l'unicité du pseudo avant d'inscrire le membre : 
                if(empty($contenu)){// si la variable est vide c'est qu'il n'y a pas de message d'erreur
                    // On verifie l'unicité du pseudo en BDD:
                    $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo",array(':pseudo' => $_POST['pseudo']));
                        if($membre->rowCount() > 0){// si la requete contient 1 ou plusieurs lignes, c'est le pseudo est deja en BDD
                            $contenu .= '<div class="alert alert-danger">Le pseudo est indisponible, Veuillez en choisir un autre.</div>';              
                        }
                        else{// sinon on peut inscrire le membre
                            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Nous hashons le mdp avec cete fonction qui utilise a l'heure actuelle l'agorithme bcrypt.
                            // Lors de la connexion de l'internaute, il faudra comparer le hash de connexion avec celui de la BDD.

                            $succes = executeRequete("INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :telephone, :email, :civilite, 'user', NOW())",array(
                                ':pseudo' => $_POST['pseudo'],
                                ':mdp' => $mdp, // on prend le mdp hassh"
                                ':nom' => $_POST['nom'],
                                ':prenom' => $_POST['prenom'],
                                ':telephone' => $_POST['telephone'],
                                ':email' => $_POST['email'],
                                ':civilite' => $_POST['civilite'],
                               
                                
                             
                            ));
                            
                            if($succes){
                                $contenu .= '<div class="alert alert-success">Vous etes inscrit . <a href="connexion.php">cliquez ici pour vous connecter</a></div>';
                              
                                $affiche_formulaire = false;// pour ne plus afficher le formulaire
                            }
                            else{
                                $contenu .='<div class="alert alert-danger">oups, erreur lors de l\'enregistrement... Veuillez essayer plus tard .</div>';
                            }
                        }
                }// fin du if(empty($contenu))

        } // fin du   if(!EMPTY($_POST))
require_once 'inc/header.php';
?>

    <h1 class="mt-4">Inscription</h1>
<?php
echo $contenu; // pour afficher les messages
if($affiche_formulaire): // si membre pas inscrit on affiche le formulaire. Syntaxe en if(): ..... endif;
?>
        <form method="post" action="">
            <div>
                <div><label for="pseudo">pseudo</label></div>
                <div><input type="text" name="pseudo" id="pseudo" value="<?php echo $_POST['pseudo'] ?? ''; ?>"></div>
            </div>
            <div>
                <div><label for="mdp">mot de passe</label></div>
                <div><input type="password" name="mdp" id="mdp" value="<?php echo $_POST['mdp'] ?? ''; ?>"></div>
            </div>
            <div>
                <div><label for="nom">Nom</label></div>
                <div><input type="text" name="nom" id="nom" value="<?php echo $_POST['nom'] ?? ''; ?>"></div>
            </div>
            <div>
                <div><label for="prenom">prenom</label></div>
                <div><input type="text" name="prenom" id="prenom" value="<?php echo $_POST['prenom'] ?? ''; ?>"></div>
            </div>
            <div>
                <div><label for="telephone">telephone</label></div>
                <div><input type="text" name="telephone" id="telephone" value="<?php echo $_POST['telephone'] ?? ''; ?>"></div>
            </div>
            <div>
                <div><label for="email">Email</label></div>
                <div><input type="text" name="email" id="email" value="<?php echo $_POST['email'] ?? ''; ?>"></div>
            </div>
            <div>
                <div><label >Civilité</label></div>
                <div><input type="radio" name="civilite" id="homme" value="m" checked><label for="homme">Homme</label></div>
                 <div><input type="radio" name="civilite" id=Femme" value="f" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'f') echo 'checked'; ?> ><label for="Femme">Femme</label></div>
            </div>
           
            
            <div class="mt-2"><input  type="submit" value="s'inscrire"></div>


        </form>

<?php

endif;

require_once 'inc/footer.php';
