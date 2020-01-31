<?php
require_once '../inc/init.php.'; //attention au ../


if (!estAdmin()){
    header('location:../connexion.php'); // si le membre n'est pas connecté ou n'est pas admin, on le redirige vers la page de connexion
    exit(); //on quitte le script
}

$affiche_formulaire = false;
// $produit_actuel = array();


//8- remplissage du formulaire de modification de produit :
    if(isset($_GET['id_commentaire']) && isset($_GET['action']) && $_GET['action'] == 'modifier'){// si on a recu l'id_produit dans l'URL, c'est qu'on a demandé la modification du produit
        // On selectionne les infos du produit en BDD pour remplir le formulaire :
            $resultat = executeRequete( "SELECT * FROM commentaire WHERE id_commentaire = :id_commentaire", array(':id_commentaire' => $_GET['id_commentaire']));
            $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
            $affiche_formulaire = true;
        }


if($_POST){

    if(!isset($_POST['commentaire']) || strlen($_POST['commentaire']) < 4 || strlen($_POST['commentaire']) > 150){ // si le champs titre n'existe pas ou que la 
                    // taille est trop court ou trop long, on met un message a l'internaute
                    $contenu .= '<div class="alert alert-danger">Le commentaire doit contenir entre 4 et 20 caracteres.</div>';
                    }



    if(empty($contenu)){
       
        $requete = executeRequete("REPLACE INTO commentaire VALUES (:id_commentaire, :commentaire, :membre_id, :annonce_id, NOW())", array(
                    ':id_commentaire' => $_POST['id_commentaire'],
                    ':commentaire' => $_POST['commentaire'],
                    ':membre_id' => $_SESSION['membre']['id_membre'],
                    ':annonce_id' => $_POST['annonce_id']
                ));
        // REPLACE INTO se comporete comme un INSERT quand l'id_produit n'existe pas (0), ou  comme un UPDATE quand l'id_produit fourni existe
        if($requete){// si la fonction executeRequet retourne un objet PDOStatement (donc implicitement evalué a TRUE), cest la requete a marché
        $contenu .= '<div class="alert alert-success">La categorie a été modifié avec succès</div>';
        $affiche_formulaire = false;
        $produit_actuel = array('');

        }
        else{ // sinon on a recu false en cas d'erreur sur la requete
        $contenu .= '<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
        }
        unset($produit_actuel);
        unset($_POST);
    
    }
 
} 


//7. Suppression du produit : 
if(isset($_GET['id_commentaire']) &&  isset($_GET['action']) && $_GET['action'] == 'supprimer') {  //si existe id_produit dans l'url, donc dans $_GET, c'est qu'on à demandé la supression di produit
    $resultat = executeRequete("DELETE FROM categorie WHERE id_commentaire = :id_commentaire", array(':id_commentaire' => $_GET['id_commentaire']));

    if ($resultat->rowCount()==1){
        $contenu .= '<div class=alert amert-success"> Le produit à bien été supprimé</div>';
    }else{
        $contenu .= '<div class=alert amert-danger"> Erreur lors de la suppression du produit</div>';
    }
}


// 6. Affichage des produits dans le back-office :
$resultat = executeRequete("SELECT c.id_commentaire, m.pseudo, m.email, a.id_annonce, a.titre as titreA, c.commentaire, c.date_enregistrement FROM commentaire c INNER JOIN membre m ON c.membre_id = m.id_membre INNER JOIN annonce a ON c.annonce_id = a.id_annonce"); // on selectionne tout les produits
$contenu .= '<div>Nombre de produits de commentaires : ' . $resultat-> rowCount() .'</div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>id_commentaire</th>';
        $contenu .= '<th>id_membre</th>';
        $contenu .= '<th>email membre</th>';
        $contenu .= '<th>id_annonce</th>';
        $contenu .= '<th>titre annonce</th>';
        $contenu .= '<th>commentaire</th>';
        $contenu .= '<th>date enregistrement</th>';
        $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

// Affichage des lignes du tableau

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit est un array avec toutes les informations d'un seul produit à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur)
        {
                $contenu .= '<td>' . $valeur . '</td>';
        }
        $contenu .= '<td>
                            <a href="?action=modifier&id_commentaire='. $produit['id_commentaire'] .'#formulaire">modifier</a> |
                            <a href="?action=supprimer&id_commentaire='. $produit['id_commentaire'] .'" onclick ="return confirm(\' Etes-vous certain de vouloir supprimer ce commentaire ?\')">supprimer</a>
                    </td>';

    $contenu .= '</tr>';
}

    $contenu .='</table>';
$contenu .= '</div>';



require_once '../inc/header.php.';
// 2. Navigation entre les pages d'administration :

?>

<h1 class="mt-4">Gestion des Commentaires</h1>
<ul class="nav nav-tabs">
    <li><a class="nav-link " href="gestion_categorie.php">Gestion des categories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion des membres</a></li>
    <li><a class="nav-link" href="../gestion_annonce.php">Gestion des annonces</a></li>
    <li><a class="nav-link" href="gestion_notes.php">Gestion des notes</a></li>
    <li><a class="nav-link active" href="gestion_commentaires.php">Gestion des commentaires</a></li>
    <li><a class="nav-link" href="gestion_statistique.php">Gestion des statistiques</a></li>

 
</ul>


<?php

echo $contenu; //pour afficher notament le tableau des produits
if($affiche_formulaire):
?>
<form method='post' id="formulaire" action="" >
    <!-- enctype specifie que le formulaire envoie des données binaires (fichier) en plus du texte (champs du formaulaire) : permet d'uploader un fichier photo -->
    <div>
        <label for="id_commentaire">id commentaire : <?php echo $produit_actuel['id_commentaire'] ?? 0 ; ?></label>
        <input type="hidden" name="id_commentaire" value="<?php echo $produit_actuel['id_commentaire'] ?? 0 ; ?>">
        <!-- On met un type hidden pour eviter de le modifier par accident. On precise une value a 0 pour que lors de l'insertion en BDD l'id_produit s'auto-incremente (creation de produit). -->
    </div>
    <div>
        <div><label for="commentaire">commentaire</label></div>
        <div><textarea name="commentaire" id="" cols="60" rows="5"><?php echo $produit_actuel['commentaire'] ?? '' ; ?></textarea></div>
    </div>
    <div>
        <input type="hidden" name="membre_id" value="<?php echo $produit_actuel['membre_id'] ?? '' ; ?>">
        <input type="hidden" name="annonce_id" value="<?php echo $produit_actuel['annonce_id'] ?? '' ; ?>">
    </div>




    <div class="mt-2"><input type="submit" value="valider"></div>

</form>

<?php
endif;

require_once '../inc/footer.php.';
