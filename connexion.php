<?php
require_once 'inc/init.php';
    $message = ''; // pour le message de deconnexion

    //2- deconnexion de l'internaute :
    debug($_GET);       
    if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){ // si existe "action" dans $_GET et que sa valeur correspond a "deconnexion", c'est que le membre a cliqué sur "deconnexion" .
        unset($_SESSION['membre']); // On supprime la partie "membre" de la session (sans toucher a l'eventuel "panier")
        $message = '<div class="alert alert-info">vous etes deconnecté </div>';
    }


    //3- iInternaute deja connecté: on le redirige vers son profil :
    if(estConnecte()){
        header('location:profil.php'); // on redirige vers le profil.php
        exit(); // et on quitte le script


    }


    // 1- Traitement du formulaire de connexion 
    debug($_POST);
    if(!empty($_POST)){ // si le formulaire a été envoyé
        //Contorle du formulaire
        if(empty($_POST['mdp']) || empty($_POST['pseudo'])){
            $contenu .= '<div class="alert alert-danger">Les identifiants sont obligatoires.</div>';
        }

        //s'il n'y a pas d'erreur sur le formulaire, on peut verifier le pseudo et le mdp:
            if(empty($contenu)){// si vide , il n'y a pas d'erreur

                $resultat = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo']));

                    if($resultat->rowCount() == 1){// s'il y a une ligne en BDD , alors le pseudo existe: on peut verifier le mot de passe
                    
                        $membre = $resultat->fetch(PDO::FETCH_ASSOC); // pas de while car 1 seul resultat dans notre requete
                        if(password_verify($_POST['mdp'], $membre['mdp'])){// si le hash de mdp du formulaire correspond a celui de la BDD, on peut connecter le membre
                            $_SESSION['membre'] = $membre; // nous remplissons une session avec les informations du membre contenues dans l'array $membre.

                            header('location:profil.php');// le pseudo et le mdp etant corrects, on  redirige l'internaute vers la page profil.php
                            exit();// on quitte le script
                        }
                        else{
                            $contenu .= '<div class="alert alert-danger">Erreur sur les identifiants.</div>';
                        }
                    
                    }
                    else {
                        $contenu .= '<div class="alert alert-danger">Erreur sur les identifiants.</div>';
                    }

            } // fin du if(empty($contenu))



    } // fin du if(!empty($_POST))

require_once 'inc/header.php';
?>
<h1 class="mt-4">connexion</h1>
<?php
echo $message; // pour la déconnexion
echo $contenu; // pour la connexion
?>

<form method= "post" action="">
    <div> 
        <div><label for="pseudo">pseudo</label></div>
        <div><input type="text" name="pseudo" id="pseudo"></div>
    </div>
    <div>
        <div><label for="mdp">mot de passe</label></div>
        <div><input type="password" name="mdp" id="mdp"></div>
    </div>

<div class="mt-2"><input type="submit" value="se connecter"></div>


</form>
<?php
require_once 'inc/footer.php';