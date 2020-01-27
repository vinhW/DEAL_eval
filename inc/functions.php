<?php
function debug($var){
    echo '<pre>';
        var_dump($var);
    echo '</pre>';
}

// fonctions liées au membre : 
function estConnecte(){
    if(isset($_SESSION['membre'])){ // si membre existe dans la session, c'est que l'internaute est passé par la page de connexion avec le bon pseudo/mdp
        // et que nous avons rempli la session avec ses informations.
        return true;
    }
    else{
        return false;
    }
}

function estAdmin(){ // Cette fonction indique si le membre est administrateur et connecté 
    if(estConnecte() && $_SESSION['membre']['statut'] == "admin"){ // On verifie d'abord que la session "membre" existe, avant de verifier qu'elle contient le statut de 1 qui correspond a un admin
        return true;
    }
    else{
        return false;
    }
}

// fonction pour faire des requetes :

     //   $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo",array(':pseudo' => $_POST['pseudo']));

        function executeRequete($requete, $param = array()){ // cette fonction attend un string qui contient la requete SQL a executer, $param est un parametre optionnel cont la valeur
            // par defaut est un array() vide. il est destiné a recevoir un tableau associatif qui lie les marqueurs de la requete a leur valeur.
            // Si on ne lui fournit pas ce tableau, $param prend un array vide par defaut.

            // on fait les htmlspecialchars() :
            foreach($param as $indice => $valeur){
                $param[$indice] = htmlspecialchars($valeur); // On parcours le tableau $param par ses indices et ses valeurs. A chaque tour de boucle, on prend la valeur, on la passe 
                // dans htmlspecialchars() et on la range a sa place au meme indice. Evite les injections XSS et CSS.          
            }
            global $pdo; // permet d'avoir acces a la variable dans cette fonction, car $pdo est declarée a l'exterieur de celle-ci.
            $resultat = $pdo->prepare($requete);// On prepare la requete recue dans $requete 
            $succes = $resultat->execute($param);// puis on l'excute en lui donnant $param dont le role est d'associer les marqueurs a leur valeur.
            // execute() retourne un booléen pour dire si la requete a marché o pas .

            if($succes){ // Si true c'est qu'il n'y a pas d'erreur sur la requete
                return $resultat; // On retourne donc l'objet PDOStatement necessaire notamment aux SELECT
            }
            else{
                return false;// Sinon, s'il y a une erreur, on retourne FALSE.
            }
        }




