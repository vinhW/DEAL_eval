<?php
require_once 'inc/init.php.'; //attention au ../
if (!estAdmin()){
    header('location:../connexion.php'); // si le membre n'est pas connecté ou n'est pas admin, on le redirige vers la page de connexion
    exit(); //on quitte le script
}
// debug($_POST);
$produit = array();
$affiche_formulaire = false;
$contenu_categorie = '';
$requete_categorie = executeRequete("SELECT * FROM categorie ");

while( $resultat_categorie = $requete_categorie->fetch(PDO::FETCH_ASSOC)){
$contenu_categorie .= '<option value="'.$resultat_categorie['id_categorie'].'" title="'.$resultat_categorie['mots_cles'].'">'. $resultat_categorie['titre'].'</option>';
}

//8- remplissage du formulaire de modification de produit :
    if(isset($_GET['id_annonce']) && isset($_GET['action']) && $_GET['action'] == 'modifier'){
           $resultat = executeRequete("SELECT a.id_annonce, a.titre as titreA, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, m.pseudo, c.titre as titreC, a.date_enregistrement FROM annonce a INNER JOIN membre m ON a.membre_id = m.id_membre INNER JOIN categorie C ON a.categorie_id = c.id_categorie WHERE id_annonce = :id_annonce", array(':id_annonce' => $_GET['id_annonce']));
            $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
            $affiche_formulaire = true;
        }



if($_POST){ // equivalent a !empty($_POST), qui signifie que le formulaire a été envoyé
    //insertion du produit en BDD :
    if(!isset($_POST['titre']) || strlen($_POST['titre']) < 4 || strlen($_POST['titre']) > 30){ // si le champs titre n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le titre doit contenir entre 4 et 20 caracteres.</div>';
    }
    if(!isset($_POST['description_courte']) || strlen($_POST['description_courte']) < 5){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">La description courte doit contenir entre 8 et 20 caracteres.</div>';
    }
    if(!isset($_POST['description_longue']) || strlen($_POST['description_longue']) < 5){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le description longue doit contenir entre 2 et 20 caracteres.</div>';
    }
            
    if(!isset($_POST['prix']) || ($_POST['prix'] <= 0)  ){// si la civilité est diffente de 'm' et 'f' en meme temps
                $contenu .= '<div class="alert alert-danger">Le prix est invalide</div>';
    }
    if(!isset($_POST['ville']) || strlen($_POST['ville']) < 1 || strlen($_POST['ville']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">La pays doit contenir entre 1 et 20 caracteres</div>';
    }
            // if(!isset($_POST['ville']) || strlen($_POST['ville']) < 2 || strlen($_POST['ville']) > 40){ // si le champs pseudo n'existe pas ou que la 
            //     // taille est trop court ou trop long, on met un message a l'internaute
            //     $contenu .= '<div class="alert alert-danger">La ville doit contenir entre 1 et 20 caracteres</div>';
            // }

    if(!isset($_POST['adresse']) || strlen($_POST['adresse']) < 5 || strlen($_POST['adresse']) > 50){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">La adresse doit contenir entre 1 et 20 caracteres</div>';
    }
    if(!isset($_POST['code_postal']) || !preg_match('#^[0-9]{5}$#', $_POST['code_postal'])){ // preg_match retourne 0 si le code postale fourni ne corresponf pas a l'xpression reguliere
                // reguliere , sinon retourne 1. 
                // explication de l'expression reguliere (ou rationnelle ou regex):
                // l'expression est encadrée par des #
                // le ^ signifie qu'elle "commence par" , et le $ quelle "fini par". Autrement dit avec les deux signes, on definit l'integralité de l'expression.
                // [0-9] definit l'intervalle des caracteres autorisé, ici de 0 a 9, excluant tous les autres caracteres.
                // {5} définit que l'on souhaite precisement 5 de ces caracteres.
                $contenu .= '<div class="alert alert-danger">Le code postale n\'est pas valide</div>';
    }
        $photo_bdd = ''; // par defaut le champ est un string vide en BDD
    if(isset($_POST['photo_actu'])){ //  si on est en train de modifier le produit, on remet le chemin de la photo en BDD actuellement dans le formulaire
                $photo_bdd = $_POST['photo_actu'];
    }
    if(!empty($_FILES['photo']['name'])){// Si nous avons un nom du fichier, cest que nous sommes en train de le telecharger
            $fichier_photo = 'ref'. $_POST['id_annonce'].'_'.$_FILES['photo']['name'];
            // construisons un nom de fichier photo unique sur la base de la reference fournie dans le formulaire.
            $photo_bdd = 'photo/' . $fichier_photo;// ceci est le chemin relatif de la photo, chemin enregistrer en BDD. Il sera utilisé par les attribut SRC des balises <img> pour afficher la photo.
            copy($_FILES['photo']['tmp_name'],$photo_bdd);// On copie le fichier temporaire qui si trouve a l'adresse contenue dans $_FILES['photo']['tmp_name'] vers l'endroit defintif contenu dans $photo_bdd.
            //On enregistre uniquement le chemin de la photo en BDD, mais pas le fichier en tant que tel. Ce dernier est bien dans le repertoire "photo/" du site.
    }

debug($_POST);

if(empty($contenu)){
 // mettre en UPDATE
    $requete = executeRequete("UPDATE annonce SET titre = :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix, photo = :photo, pays = :pays, ville = :ville,  adresse = :adresse, code_postal = :code_postal, membre_id = :membre_id, categorie_id = :categorie_id WHERE id_annonce = :id_annonce", array(
                                                                ':id_annonce' => $_POST['id_annonce'],
                                                                ':titre' => $_POST['titre'],
                                                                ':description_courte' => $_POST['description_courte'],
                                                                ':description_longue' => $_POST['description_longue'],
                                                                ':prix' => $_POST['prix'],
                                                                ':photo' => '$photo_bdd',
                                                                ':pays' => $_POST['pays'],
                                                                ':ville' => $_POST['ville'],
                                                                ':adresse' => $_POST['adresse'],
                                                                ':code_postal' => $_POST['code_postal'],
                                                                ':membre_id' => $_SESSION['membre']['id_membre'],
                                                                ':categorie_id' => $_POST['categorie']
                                                             
                                                           

    )); 
// REPLACE INTO se comporete comme un INSERT quand l'id_produit n'existe pas (0), ou  comme un UPDATE quand l'id_produit fourni existe
    if($requete){// si la fonction executeRequet retourne un objet PDOStatement (donc implicitement evalué a TRUE), cest la requete a marché
        $contenu .= '<div class="alert alert-success">L\'annonce a été modifié avec succès.</div>';
        $affiche_formulaire = false;
    }  
        else{ // sinon on a recu false en cas d'erreur sur la requete
            $contenu .= '<div class="alert alert-danger">Erreur sur la modification ...</div>';
    }
    unset($produit_actuel);
    unset($_POST);

    }


} // fin du if($_POST)


//7. Suppression du produit : 
if(isset($_GET['id_annonce']) && isset($_GET['action']) && $_GET['action'] == 'supprimer') {  //si existe id_produit dans l'url, donc dans $_GET, c'est qu'on à demandé la supression di produit
    $resultat = executeRequete("DELETE FROM annonce WHERE id_annonce = :id_annonce", array(':id_annonce' => $_GET['id_annonce']));

    if ($resultat->rowCount()==1){
        $contenu .= '<div class=alert amert-success"> L\'annonce à bien été supprimé</div>';
    }else{
        $contenu .= '<div class=alert amert-danger"> Erreur lors de la suppression du produit</div>';
    }
}


// 6. Affichage des produits dans le back-office :
$resultat = executeRequete("SELECT a.id_annonce, a.titre as titreA, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, m.pseudo, c.titre as titreC, a.date_enregistrement FROM annonce a INNER JOIN membre m ON a.membre_id = m.id_membre INNER JOIN categorie C ON a.categorie_id = c.id_categorie"); // on selectionne tout les produits
$contenu .= '<div>Nombre d\'annonces : ' . $resultat-> rowCount() .'</div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>id_annonce</th>';
        $contenu .= '<th>titre</th>';
        $contenu .= '<th>description courte</th>';
        $contenu .= '<th>description longue</th>';
        $contenu .= '<th>prix</th>';
        $contenu .= '<th>photo</th>';
        $contenu .= '<th>pays</th>';
        $contenu .= '<th>ville</th>';
        $contenu .= '<th>adresse</th>';
        $contenu .= '<th>code postal</th>';
        $contenu .= '<th>membre</th>';
        $contenu .= '<th>categorie</th>';
        $contenu .= '<th>date_enregistrement</th>';
        $contenu .= '</tr>';
// debug($resultat);
// Affichage des lignes du tableau

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit est un array avec toutes les informations d'un seul produit à cgq tour de boucle. On le parcourt donc avec une boucle foreach :
 
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur)
        {
            if($indice == 'photo' && !empty($valeur)) {
                $contenu .= '<td><img src="' . $valeur . '" style="width:90px"></td>';

            }elseif($indice == 'description_longue'){
                $contenu .= '<td>' .substr($valeur, 0, 55 ) . '</td>';
            
            }elseif($indice == 'description_courte'){
                $contenu .= '<td>' .substr($valeur, 0, 55 ) . '</td>';
            }
            else{
                $contenu .= '<td>' . $valeur . '</td>';
            }
        }
     
        $contenu .= '<td>
                            <a href="?action=modifier&id_annonce='. $produit['id_annonce'] .'#formulaire">modifier</a> |
                            <a href="?action=supprimer&id_annonce='. $produit['id_annonce'] .'" onclick ="return confirm(\' Etes-vous certain de vouloir supprimer ce produit ?\')">supprimer</a>
                    </td>';

    $contenu .= '</tr>';
}

    $contenu .='</table>';
$contenu .= '</div>';



require_once 'inc/header.php.';
// 2. Navigation entre les pages d'administration :

?>

<h1 class="mt-4">Gestion des annonces</h1>
<ul class="nav nav-tabs">
     <li><a class="nav-link" href="admin/gestion_categorie.php">Gestion des categories</a></li>
    <li><a class="nav-link" href="admin/gestion_membre.php">Gestion des membres</a></li>
    <li><a class="nav-link active" href="gestion_annonce.php">Gestion des annonces</a></li>
    <li><a class="nav-link" href="admin/gestion_notes.php">Gestion des notes</a></li>
    <li><a class="nav-link" href="admin/gestion_commentaires.php">Gestion des commentaires</a></li>
    <li><a class="nav-link" href="admin/gestion_statistique.php">Gestion des statistiques</a></li>

 
</ul>


<?php
echo $contenu; //pour afficher notament le tableau des produits
if($affiche_formulaire):
?>
<form method='post' action="" id="formulaire" enctype="multipart/form-data">
    <!-- enctype specifie que le formulaire envoie des données binaires (fichier) en plus du texte (champs du formaulaire) : permet d'uploader un fichier photo -->
    <div>
        <input type="hidden" name="id_annonce" value="<?php echo $_POST['id_annonce'] ?? $produit_actuel['id_annonce'] ?? 0 ; ?>">
        <!-- On met un type hidden pour eviter de le modifier par accident. On precise une value a 0 pour que lors de l'insertion en BDD l'id_produit s'auto-incremente (creation de produit). -->
    </div>
    <div>
        <div><label for="titre">titre</label></div>
        <div><input type="text" name="titre" id="titre" value="<?php echo $_POST['titre'] ?? $produit_actuel['titreA'] ??'' ; ?>"></div>
    </div>
    <div>
        <div><label for="description_courte">description_courte</label></div>
        <div><input type="text" name="description_courte" id="description_courte" value="<?php echo $_POST['description_courte'] ?? $produit_actuel['description_courte'] ?? '' ; ?>"></div>
    </div>

    <div>
        <div><label for="description_longue">description longue</label></div>
        <div><textarea name="description_longue" id="description_longue" cols="30" rows="5"><?php echo $_POST['description_longue'] ?? $produit_actuel['description_longue'] ?? '' ; ?></textarea></div>
    </div>

      <div>
        <div><label for="categorie">categorie</label></div>
        <div>
        <select name="categorie"> 
     <?php  
     echo $contenu_categorie ; 
  
     ?>
        </select>
        </div>
<img src="" alt="">
    </div>
    <div>
        <div> <label for="photo">Photo</label></div>
        <div><!-- 5- Upload de la photo -->
        <img src="<?php echo $produit_actuel['photo'] ?? ''; ?>" alt="<?php echo $produit_actuel['titreA'] ?? ''; ?>" style="width:90px">
                <input type="file" name="photo" id="photo" > <!-- ne pas oublier l'attribut enctype sur la balise <form>. -->
                <?php if(isset($_POST['photo'])){// en cas de modification du produit_actuel
                
                    echo '<input type="hidden" name="photo_actu" value="photo/'.$_POST['photo'].'">';} ?>  <!--On veut mettre le chemin de la photo actuelle dans $_POST pour le remettre en BDD -->
        </div>
    </div>
    <div>
        <div><label for="prix">Prix</label></div>
        <div><input type="text" name="prix" id="prix" value="<?php echo $_POST['prix'] ?? $produit_actuel['prix'] ?? 0 ; ?>"></div>
    </div>
    <div>
        <div><label for="pays">pays</label></div>
        <select name="pays" id="pays">
        <option>france</option>
        <option>espagne</option>
        <option>italie</option>
        <option>portugal</option>
        <option>angleterre</option>
        <option>allemagne</option>
        <option>belgique</option>
        <option>autre</option>
        </select>
        <!-- <div><textarea name="ville" id="ville"><?php //echo $_POST['ville'] ?? '' ; ?></textarea></div> -->
    </div>
    <div>
        <div><label for="ville">ville</label></div>
        <div><input type="text" name="ville" id="ville" value="<?php echo $_POST['ville'] ?? $produit_actuel['ville'] ?? '' ; ?>"></div>
    </div>
    <div>
        <div><label for="couleur">adresse</label></div> 
        <div><textarea name="adresse" id="adresse" cols="30" rows="5"><?php echo $_POST['adresse'] ?? $produit_actuel['adresse'] ?? '' ; ?></textarea></div>
    </div>
    <div>
        <div><label for="code_postal">Code postale</label></div>
        <div><input type="text" name="code_postal" id="code_postal" value="<?php echo $_POST['code_postal'] ?? $produit_actuel['code_postal'] ??''; ?>"></div>
    </div>
            
    <div class="mt-2"><input type="submit" value="valider"></div>

</form>
<?php 
endif;
require_once 'inc/footer.php.';
