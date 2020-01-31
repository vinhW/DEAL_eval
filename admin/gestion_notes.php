<?php
require_once '../inc/init.php.'; //attention au ../


if (!estAdmin()){
    header('location:../connexion.php'); // si le membre n'est pas connecté ou n'est pas admin, on le redirige vers la page de connexion
    exit(); //on quitte le script
}
$affiche_formulaire = false;
// debug($_SESSION);
$produit_actuel = array();



//8- remplissage du formulaire de modification de produit :
    if(isset($_GET['id_note']) && isset($_GET['action']) && $_GET['action'] == 'modifier'){// si on a recu l'id_produit dans l'URL, c'est qu'on a demandé la modification du produit
        // On selectionne les infos du produit en BDD pour remplir le formulaire :
            $resultat = executeRequete( "SELECT * FROM note WHERE id_note = :id_note", array(':id_note' => $_GET['id_note']));
            $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
            
            $affiche_formulaire = true;



        }


if($_POST){ // equivalent a !empty($_POST), qui signifie que le formulaire a été envoyé
    //insertion du produit en BDD :
     if(!isset($_POST['avis']) || strlen($_POST['avis']) < 4 || strlen($_POST['avis']) > 30){ // si le champs titre n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le commentaire doit contenir entre 4 et 20 caracteres.</div>';
    }
    if(!isset($_POST['note']) ){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">La note doit etre entre 1 et 5.</div>';
    }

    if(empty($contenu)){
        //  utiliser un update
<<<<<<< HEAD
        $requete = executeRequete("UPDATE note SET note = ':note' , avis = ':avis'  WHERE id_note =' :id_note'", array(
=======
        $requete = executeRequete("REPLACE INTO note VALUES (:id_note, :note , :avis, :membre_id_auteur, :membre_id_cible, NOW())", array(
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
                                                                ':id_note' => $_POST['id_note'],
                                                                ':note' => $_POST['note'],
                                                                ':avis' => $_POST['avis']
                                                                
                                                            ));


        if($requete){// si la fonction executeRequet retourne un objet PDOStatement (donc implicitement evalué a TRUE), cest la requete a marché
        $contenu .= '<div class="alert alert-success">L\'avis a été modifié !.</div>';
        $affiche_formulaire = false;

        }
        else{ // sinon on a recu false en cas d'erreur sur la requete
        $contenu .= '<div class="alert alert-danger">Erreur lors de la modification</div>';
        }
    }
 
} // fin du if($_POST)

// debug($_POST);

<<<<<<< HEAD

// debug($produit_actuel);
=======
//8- remplissage du formulaire de modification de produit :
    if(isset($_GET['id_note']) && isset($_GET['action']) && $_GET['action'] == 'modifier'){// si on a recu l'id_produit dans l'URL, c'est qu'on a demandé la modification du produit
        // On selectionne les infos du produit en BDD pour remplir le formulaire :
            $resultat = executeRequete( "SELECT * FROM note WHERE id_note = :id_note", array(':id_note' => $_GET['id_note']));
            $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
        }
debug($produit_actuel);
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
//7. Suppression du produit : 
if(isset($_GET['id_note']) &&  isset($_GET['action']) && $_GET['action'] == 'supprimer') {  //si existe id_produit dans l'url, donc dans $_GET, c'est qu'on à demandé la supression di produit
    $resultat = executeRequete("DELETE FROM note WHERE id_note = :id_note", array(':id_note' => $_GET['id_note']));

    if ($resultat->rowCount()==1){
        $contenu .= '<div class=alert amert-success"> Le produit à bien été supprimé</div>';
    }else{
        $contenu .= '<div class=alert amert-danger"> Erreur lors de la suppression du produit</div>';
    }
}


// 6. Affichage des produits dans le back-office :
$resultat = executeRequete("SELECT n.id_note, note, avis, m.pseudo as pseudoAuteur, m.email, K.pseudo as pseudoCible, n.date_enregistrement FROM note n INNER JOIN membre m ON n.membre_id_auteur = m.id_membre INNER JOIN membre k ON n.membre_id_cible = k.id_membre"); // on selectionne tout les produits
$contenu .= '<div>Nombre de Notes : ' . $resultat-> rowCount() .'</div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>id_note</th>';
        $contenu .= '<th>note</th>';
        $contenu .= '<th>avis</th>';
        $contenu .= '<th>membre_id_auteur</th>';
        $contenu .= '<th>email auteur</th>';
        $contenu .= '<th>membre_id_cible</th>';
        $contenu .= '<th>date enregistrement</th>';
        $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

// Affichage des lignes du tableau


while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit_actuel est un array avec toutes les informations d'un seul produit_actuel à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
  
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur){
                $contenu .= '<td>' . $valeur . '</td>';
        }
       
        $contenu .= '<td>
                            <a href="?action=modifier&id_note='. $produit['id_note'] .'#formulaire">modifier</a> |
                            <a href="?action=supprimer&id_note='. $produit['id_note'] .'" onclick ="return confirm(\' Etes-vous certain de vouloir supprimer cette categorie ?\')">supprimer</a>
                    </td>';

    $contenu .= '</tr>';
}
    $contenu .='</table>';
$contenu .= '</div>';
<<<<<<< HEAD
// debug($produit_actuel);
=======
//  debug($produit);
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2


require_once '../inc/header.php.';
// 2. Navigation entre les pages d'administration :

?>

<h1 class="mt-4">Gestion des Notes</h1>
<ul class="nav nav-tabs">
    <li><a class="nav-link " href="gestion_categorie.php">Gestion des categories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion des membres</a></li>
    <li><a class="nav-link" href="../gestion_annonce.php">Gestion des annonces</a></li>
    <li><a class="nav-link active" href="gestion_notes.php">Gestion des notes</a></li>
    <li><a class="nav-link" href="gestion_commentaires.php">Gestion des commentaires</a></li>
    <li><a class="nav-link" href="gestion_commentaires.php">Gestion des statistiques</a></li>
 
</ul>


<?php

echo $contenu; //pour afficher notament le tableau des produits
if($affiche_formulaire):
?>
        <form method="post" id="formulaire" action="">
                                    <input type="hidden" name="id_note" value="<?php echo $produit_actuel['id_note'] ?? '' ;?> ">
                                    <label for="avis">avis</label><br>
                                    <textarea name="avis" id="avis" cols="45" rows="3" ><?php echo $produit_actuel['avis'] ?? '' ; ?></textarea><br>
                                    <label for="note" class="mt-2">Note</label>
                                    <select name="note" id="note" >
<<<<<<< HEAD
                                        <option checked>1</option>
                                        <option <?php if((isset($produit_actuel['note']) && $produit_actuel['note'] == '2') || (isset($_POST['note']) && $_POST['note'] == '2')) echo 'checked'; ?>>2</option>
                                        <option <?php if((isset($produit_actuel['note']) && $produit_actuel['note'] == '3') || (isset($_POST['note']) && $_POST['note'] == '3')) echo 'checked'; ?>>3</option>
                                        <option <?php if((isset($produit_actuel['note']) && $produit_actuel['note'] == '4') || (isset($_POST['note']) && $_POST['note'] == '4')) echo 'checked'; ?>>4</option>
                                        <option <?php if((isset($produit_actuel['note']) && $produit_actuel['note'] == '5') || (isset($_POST['note']) && $_POST['note'] == '5')) echo 'checked'; ?>>5</option>
=======
                                        <option <?php if(isset($produit_actuel['note']) && $produit_actuel['note'] == '1') echo 'checked'; ?>>1</option>
                                        <option <?php if(isset($produit_actuel['note']) && $produit_actuel['note'] == '2') echo 'checked'; ?>>2</option>
                                        <option <?php if(isset($produit_actuel['note']) && $produit_actuel['note'] == '3') echo 'checked'; ?>>3</option>
                                        <option <?php if(isset($produit_actuel['note']) && $produit_actuel['note'] == '4') echo 'checked'; ?>>4</option>
                                        <option <?php if(isset($produit_actuel['note']) && $produit_actuel['note'] == '5') echo 'checked'; ?>>5</option>
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
                                    </select>
                                    <br>
                                 <input type="hidden" name="membre_id_auteur" value="<?php echo $produit_actuel['membre_id_auteur'] ?? '' ; ?>">
                                 <input type="hidden" name="membre_id_cible" value="<?php echo $produit_actuel['membre_id_cible'] ?? '' ; ?> ">

                                    <input type="submit" value="valider" class="mt-3">
        </form> 

<?php
<<<<<<< HEAD
endif;
// debug($produit_actuel);
=======
debug($produit_actuel);
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
require_once '../inc/footer.php.';
