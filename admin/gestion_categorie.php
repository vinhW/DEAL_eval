<?php
require_once '../inc/init.php.'; //attention au ../


if (!estAdmin()){
    header('location:../connexion.php'); // si le membre n'est pas connecté ou n'est pas admin, on le redirige vers la page de connexion
    exit(); //on quitte le script
}


$produit_actuel = array();
//8- remplissage du formulaire de modification de produit :
    if(isset($_GET['id_categorie']) && isset($_GET['action']) && $_GET['action'] == 'modifier'){// si on a recu l'id_produit dans l'URL, c'est qu'on a demandé la modification du produit
        // On selectionne les infos du produit en BDD pour remplir le formulaire :
            $resultat = executeRequete( "SELECT * FROM categorie WHERE id_categorie = :id_categorie", array(':id_categorie' => $_GET['id_categorie']));
            $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
        }

if($_POST){ // equivalent a !empty($_POST), qui signifie que le formulaire a été envoyé
    //insertion du produit en BDD :
     if(!isset($_POST['titre']) || strlen($_POST['titre']) < 4 || strlen($_POST['titre']) > 30){ // si le champs titre n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le titre doit contenir entre 4 et 20 caracteres.</div>';
    }
    if(!isset($_POST['mots_cles']) || strlen($_POST['mots_cles']) < 5){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">La categorie doit contenir entre 8 et 20 caracteres.</div>';
    }

    if(empty($contenu)){
<<<<<<< HEAD
        if($_POST['id_categorie'] == 0){
                $requete = executeRequete("INSERT INTO categorie VALUES ( 0, :titre, :mots_cles )", array(
                                                                ':titre' => $_POST['titre'],
                                                                ':mots_cles' => $_POST['mots_cles']));


        }
        else{ 
        $requete = executeRequete("UPDATE categorie SET titre = :titre, mots_cles = :mots_cles WHERE id_categorie = :id_categorie ", array(
                                                                ':id_categorie' => $_POST['id_categorie'],
                                                                ':titre' => $_POST['titre'],
                                                                ':mots_cles' => $_POST['mots_cles']));
                                                                
        }
=======
        
        $requete = executeRequete("UPDATE categorie SET titre=:titre, mots_cles = :mots_cles WHERE id_categorie=:id_categorie", array(':id_categorie' =>$_POST['id_categorie'],':titre'=>$_POST['titre'], ':mots_cles' =>$_POST['mots_cles']));
        // $requete = executeRequete("REPLACE INTO categorie VALUES (:id_categorie, :titre, :mots_cles )", array(
        //                                                         ':id_categorie' => $_POST['id_categorie'],
        //                                                         ':titre' => $_POST['titre'],
        //                                                         ':mots_cles' => $_POST['mots_cles']));
                                                              
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
        // REPLACE INTO se comporete comme un INSERT quand l'id_produit n'existe pas (0), ou  comme un UPDATE quand l'id_produit fourni existe
        if($requete){// si la fonction executeRequet retourne un objet PDOStatement (donc implicitement evalué a TRUE), cest la requete a marché
        $contenu .= '<div class="alert alert-success">La categorie a été enregistré.</div>';
        $produit_actuel = array();
        }
        else{ // sinon on a recu false en cas d'erreur sur la requete
        $contenu .= '<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
        }
    }
 
} // fin du if($_POST)

debug($_POST);


//7. Suppression du produit : 
if(isset($_GET['id_categorie']) &&  isset($_GET['action']) && $_GET['action'] == 'supprimer') {  //si existe id_produit dans l'url, donc dans $_GET, c'est qu'on à demandé la supression di produit
    $resultat = executeRequete("DELETE FROM categorie WHERE id_categorie = :id_categorie", array(':id_categorie' => $_GET['id_categorie']));

    if ($resultat->rowCount()==1){
        $contenu .= '<div class=alert amert-success"> Le produit à bien été supprimé</div>';
    }else{
        $contenu .= '<div class=alert amert-danger"> Erreur lors de la suppression du produit</div>';
    }
}


// 6. Affichage des produits dans le back-office :
$resultat = executeRequete("SELECT * FROM categorie"); // on selectionne tout les produits
$contenu .= '<div>Nombre de produits de categories : ' . $resultat-> rowCount() .'</div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>id_produit</th>';
        $contenu .= '<th>titre</th>';
        $contenu .= '<th>categorie</th>';
        $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

// Affichage des lignes du tableau

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit est un array avec toutes les informations d'un seul produit à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur)
        {
            if($indice == 'photo' && !empty($valeur)) {
                $contenu .= '<td><img src="../'. $valeur . '" style="width:90px"></td>';

            }
            
            
            else{
                $contenu .= '<td>' . $valeur . '</td>';
            }
        }
       
        $contenu .= '<td>
                            <a href="?action=modifier&id_categorie='. $produit['id_categorie'] .'">modifier</a> |
                            <a href="?action=supprimer&id_categorie='. $produit['id_categorie'] .'" onclick ="return confirm(\' Etes-vous certain de vouloir supprimer cette categorie ?\')">supprimer</a>
                    </td>';

    $contenu .= '</tr>';
}

    $contenu .='</table>';
$contenu .= '</div>';



require_once '../inc/header.php.';
// 2. Navigation entre les pages d'administration :

?>

<h1 class="mt-4">Gestion des Categories</h1>
<ul class="nav nav-tabs">
    <li><a class="nav-link active" href="gestion_categorie.php">Gestion des categories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion des membres</a></li>
    <li><a class="nav-link" href="../gestion_annonce.php">Gestion des annonces</a></li>
    <li><a class="nav-link" href="gestion_notes.php">Gestion des notes</a></li>
    <li><a class="nav-link" href="gestion_commentaires.php">Gestion des commentaires</a></li>
    <li><a class="nav-link" href="gestion_statistique.php">Gestion des statistiques</a></li>

 
</ul>


<?php

echo $contenu; //pour afficher notament le tableau des produits
?>
<form method='post' action="" >
    <!-- enctype specifie que le formulaire envoie des données binaires (fichier) en plus du texte (champs du formaulaire) : permet d'uploader un fichier photo -->
    <div>
        <input type="hidden" name="id_categorie" value="<?php echo $produit_actuel['id_categorie'] ?? 0 ; ?>">
        <!-- On met un type hidden pour eviter de le modifier par accident. On precise une value a 0 pour que lors de l'insertion en BDD l'id_produit s'auto-incremente (creation de produit). -->
    </div>
    <div>
        <div><label for="titre">Titre</label></div>
        <div><input type="text" name="titre" id="titre" value="<?php echo $produit_actuel['titre'] ?? '' ; ?>"></div>
    </div>

    <div>
        <div><label for="mots_cles">catégorie</label></div>
        <div><input type="text" name="mots_cles" id="mots_cles" value="<?php echo $produit_actuel['mots_cles'] ?? '' ; ?>"></div>
    </div>



    <div class="mt-2"><input type="submit" value="valider"></div>

</form>

<?php
require_once '../inc/footer.php.';
