<?php
require_once '../inc/init.php.'; //attention au ../

if (!estAdmin()){
    header('location:../connexion.php'); // si le membre n'est pas connecté ou n'est pas admin, on le redirige vers la page de connexion
    exit(); //on quitte le script
}

$affiche_formulaire = false;

if(isset($_GET['id_membre']) && isset($_GET['action']) && $_GET['action'] == 'modifier'){// si on a recu l'id_produit dans l'URL, c'est qu'on a demandé la modification du produit
        // On selectionne les infos du produit en BDD pour remplir le formulaire :
            $resultat = executeRequete( "SELECT * FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));
            $membre_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
            $affiche_formulaire = true;
        }





 // equivalent a !empty($_POST), qui signifie que le formulaire a été envoyé
  if(!empty($_POST)){ // si on a cliqué sur s'inscrire
            // on valide tout les champs du formulair :
            if(!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le pseudo doit contenir entre 4 et 20 caracteres.</div>';
            }
            // if(!isset($_POST['mdp']) || strlen($_POST['mdp']) < 8 || strlen($_POST['mdp']) > 250){ // si le champs pseudo n'existe pas ou que la 
            //     // taille est trop court ou trop long, on met un message a l'internaute
            //     $contenu .= '<div class="alert alert-danger">Le mot de passe doit contenir entre 8 et 20 caracteres.</div>';
            // }
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
           

debug($_POST);

            if(empty($contenu)){
                    // $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo",array(':pseudo' => $_POST['pseudo']));
                        
                        // sinon on peut inscrire le membre
                            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Nous hashons le mdp avec cete fonction qui utilise a l'heure actuelle l'agorithme bcrypt.
                            // Lors de la connexion de l'internaute, il faudra comparer le hash de connexion avec celui de la BDD.

                
<<<<<<< HEAD
            $requete = executeRequete("UPDATE membre SET pseudo= :pseudo, nom = :nom, prenom = :prenom, telephone = :telephone, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre", array(
                                                                ':id_membre' => $_POST['id_membre'],
                                                                ':pseudo' => $_POST['pseudo'],
=======
            $requete = executeRequete("REPLACE INTO membre VALUES(:id_membre, :pseudo/*, :mdp*/, :nom, :prenom, :telephone, :email, :civilite, :statut, NOW())", array(
                                                                ':id_membre' => $_POST['id_membre'],
                                                                ':pseudo' => $_POST['pseudo'],
                                                                // ':mdp' => $_POST['mdp'],
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
                                                                ':nom' => $_POST['nom'],
                                                                ':prenom' => $_POST['prenom'],
                                                                ':telephone' => $_POST['telephone'],
                                                                ':email' => $_POST['email'],
                                                                ':civilite' => $_POST['civilite'],
                                                                ':statut' => $_POST['statut']
    ));
  
    // REPLACE INTO se comporete comme un INSERT quand l'id_produit n'existe pas (0), ou  comme un UPDATE quand l'id_produit fourni existe
    if($requete){// si la fonction executeRequet retourne un objet PDOStatement (donc implicitement evalué a TRUE), cest la requete a marché
        $contenu .= '<div class="alert alert-success">Le membre a bien été modifié.</div>';
          $affiche_formulaire = false;
    }
    else{ // sinon on a recu false en cas d'erreur sur la requete
        $contenu .= '<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
    }
                        }

    //insertion du produit en BDD :
    
} // fin du if($_POST)

//8- remplissage du formulaire de modification de produit :


//7. Suppression du produit : 
if(isset($_GET['id_membre']) && isset($_GET['action']) && $_GET['action'] == 'supprimer') {  //si existe id_produit dans l'url, donc dans $_GET, c'est qu'on à demandé la supression di produit
    $resultat = executeRequete("DELETE FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));

    if ($resultat->rowCount()==1){
        $contenu .= '<div class=alert amert-success"> Le produit à bien été supprimé</div>';
    }else{
        $contenu .= '<div class=alert amert-danger"> Erreur lors de la suppression du produit</div>';
    }
}


// 6. Affichage des produits dans le back-office :
$resultat = executeRequete("SELECT * FROM membre"); // on selectionne tout les produits
$contenu .= '<div>Nombre de membres : ' . $resultat-> rowCount() .'</div>';

$contenu .= '<div class"table-responsive ">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>id_membre</th>';
        $contenu .= '<th>pseudo</th>';
        $contenu .= '<th>mdp</th>';
        $contenu .= '<th>nom</th>';
        $contenu .= '<th>prenom</th>';
        $contenu .= '<th>telephone</th>';
        $contenu .= '<th>email</th>';
        $contenu .= '<th>civilité</th>';
        $contenu .= '<th>statut</th>';
        $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

// Affichage des lignes du tableau

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit est un array avec toutes les informations d'un seul produit à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur)
        {    
            if($indice == 'mdp'){
               
                    $contenu .= '<td>' .substr($valeur, 0, 10 ) . '</td>';
                
            }
            else{
                $contenu .= '<td>' . $valeur . '</td>';
            }       
            
        }
        $contenu .= '<td>
                            <a href="?action=modifier&id_membre='. $produit['id_membre'] .'#formulaire">modifier</a> |
                            <a href="?action=supprimer&id_membre='. $produit['id_membre'] .'" onclick ="return confirm(\' Etes-vous certain de vouloir supprimer ce produit ?\')">supprimer</a>
                    </td>';

    $contenu .= '</tr>';
}

    $contenu .='</table>';
$contenu .= '</div>';



require_once '../inc/header.php.';
// 2. Navigation entre les pages d'administration :

?>

<h1 class="mt-4">Gestion des Membres</h1>
<ul class="nav nav-tabs">
    <li><a class="nav-link" href="gestion_categorie.php">Gestion des categories</a></li>
    <li><a class="nav-link active" href="gestion_membre.php">Gestion des membres</a></li>
    <li><a class="nav-link " href="../gestion_annonce.php">Gestion des annonces</a></li>
    <li><a class="nav-link" href="gestion_notes.php">Gestion des notes</a></li>
    <li><a class="nav-link" href="gestion_commentaires.php">Gestion des commentaires</a></li>
    <li><a class="nav-link" href="gestion_statistique.php">Gestion des statistiques</a></li>


</ul>


<?php
echo $contenu; //pour afficher notament le tableau des produits
if($affiche_formulaire):

?>
<form method="post" action="" id="formulaire">
    <div>
        <div>
            <input type="hidden" name="id_membre" value="<?php echo $membre_actuel['id_membre'] ?? 0 ; ?>">
        </div>
        <div><label for="pseudo">pseudo</label></div>
        <div><input type="text" name="pseudo" id="pseudo" value="<?php echo $membre_actuel['pseudo'] ?? ''; ?>"></div>
    </div>
<<<<<<< HEAD
    <div>
        <div><input type="hidden" name="mdp" id="mdp" value="<?php echo $membre_actuel['mdp'] ?? ''; ?>"></div>
    </div>
=======
    <!-- <div>
        <div><input type="hidden" name="mdp" id="mdp" value="<?php echo $membre_actuel['mdp'] ?? ''; ?>"></div>
    </div> -->
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2

    <div>
        <div><label for="nom">Nom</label></div>
        <div><input type="text" name="nom" id="nom" value="<?php echo $membre_actuel['nom'] ?? ''; ?>"></div>
    </div>

    <div>
        <div><label for="prenom">prenom</label></div>
        <div><input type="text" name="prenom" id="prenom" value="<?php echo $membre_actuel['prenom'] ?? ''; ?>"></div>
    </div>

    <div>
        <div><label for="telephone">telephone</label></div>
        <div><input type="text" name="telephone" id="telephone" value="<?php echo $membre_actuel['telephone'] ?? ''; ?>"></div>
   </div>

    <div>
        <div><label for="email">Email</label></div>
        <div><input type="text" name="email" id="email" value="<?php echo $membre_actuel['email'] ?? ''; ?>"></div>
    </div>
    <div>
        <div><label>Civilité</label></div>
        <div><input type="radio" name="civilite" id="homme" value="m" checked><label for="homme">Homme</label></div>
        <div><input type="radio" name="civilite" id="Femme" value="f"
                <?php if((isset($_POST['civilite']) && $_POST['civilite'] == 'f' ) || (isset($membre_actuel) && $membre_actuel['civilite'] == 'f')) echo 'checked'; ?>><label
                for="Femme">Femme</label></div>
    </div>
    <div>
        <div><label for="statut">statut</label></div>
        <div><select name="statut" id="statut">
                <option value="user">user</option>
                <option value="admin" >admin</option>
            </select>
        </div>
    </div>


    <div class="mt-2"><input type="submit" value="valider"></div>


</form>




<?php
<<<<<<< HEAD
endif;
=======
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
debug($_POST);
require_once '../inc/footer.php.';