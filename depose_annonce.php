<?php

require_once 'inc/init.php'; 
$contenu = '';
$contenu_categorie = '';
$annonce = '';

// debug($_POST);
//4- Enregistremnent du produit :
// debug($_POST);

$requete = executeRequete("SELECT * FROM categorie ");

while( $resultat_categorie = $requete->fetch(PDO::FETCH_ASSOC)){

$contenu_categorie .= '<option value="'.$resultat_categorie['id_categorie'].'" title="'.$resultat_categorie['mots_cles'].'">'. $resultat_categorie['titre'].'</option>';

}

if($_POST){ // equivalent a !empty($_POST), qui signifie que le formulaire a été envoyé

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
            copy($_FILES['photo']['tmp_name'],$photo_bdd);// On copie le fichier temporaire qui si trouve a l'adresse contenue dans $_FILES['photo']['tmp_name'] vers l'endroit definitif contenu dans $photo_bdd.
            //On enregistre uniquement le chemin de la photo en BDD, mais pas le fichier en tant que tel. Ce dernier est bien dans le repertoire "photo/" du site.
    }

        
    if(empty($contenu)){
        
     

                $requete = executeRequete("REPLACE INTO annonce VALUES(:id_annonce, :titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :code_postal, :membre_id, :categorie_id, NOW() )", array(
                                                                    ':id_annonce' => $_POST['id_annonce'],
                                                                    ':titre' => $_POST['titre'],
                                                                    ':description_courte' => $_POST['description_courte'],
                                                                    ':description_longue' => $_POST['description_longue'],
                                                                    ':prix' => $_POST['prix'],
                                                                    ':photo' => $photo_bdd,
                                                                    ':pays' => $_POST['pays'],
                                                                    ':ville' => $_POST['ville'],
                                                                    ':adresse' => $_POST['adresse'],
                                                                    ':code_postal' => $_POST['code_postal'],
                                                                    ':membre_id' => $_SESSION['membre']['id_membre'],
                                                                    ':categorie_id' => $_POST['categorie']
                                                                
           
        
        ));

   debug($requete);

        if($requete){// si la fonction executeRequete retourne un objet PDOStatement (donc implicitement evalué a TRUE), cest la requete a marché
          
            header('location:traitement_annonce.php');
            exit();

        }
        else{ // sinon on a recu false en cas d'erreur sur la requete
            $contenu .= '<div class="alert alert-danger">Erreur lors de la modification...</div>';
        }
        
        }// fin if(empty($contenu));
        } // fin du if($_POST)

    //8- remplissage du formulaire de modification de produit :
        if(isset($_GET['id_annonce'])){// si on a recu l'id_produit dans l'URL, c'est qu'on a demandé la modification du produit
            // On selectionne les infos du produit en BDD pour remplir le formulaire :
                $resultat = executeRequete( "SELECT * FROM annonce WHERE id_annonce = :id_annonce", array(':id_annonce' => $_GET['id_annonce']));
                $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
            
            }

require_once 'inc/header.php';
//2- Navigation entre les pages d'aministration :


?>
<h1 class="mt-4">Deposer une annonce</h1>

<?php
echo $contenu;
    // echo $contenu; // Pour afficher notamment le tableau des produits
//3- Formulaire d'ajout ou de modification de produit :
    ?>
<form method='post' action="" enctype="multipart/form-data">
    <!-- enctype specifie que le formulaire envoie des données binaires (fichier) en plus du texte (champs du formaulaire) : permet d'uploader un fichier photo -->
    <div>
        <input type="hidden" name="id_annonce" value="<?php echo $_POST['id_annonce'] ?? 0 ; ?>">
        <!-- On met un type hidden pour eviter de le modifier par accident. On precise une value a 0 pour que lors de l'insertion en BDD l'id_produit s'auto-incremente (creation de produit). -->
    </div>
    <div>
        <div><label for="titre">titre</label></div>
        <div><input type="text" name="titre" id="titre" value="<?php echo $_POST['titre'] ?? '' ; ?>"></div>
    </div>
    <div>
        <div><label for="description_courte">description_courte</label></div>
        <div><input type="text" name="description_courte" id="description_courte" value="<?php echo $_POST['description_courte'] ?? '' ; ?>"></div>
    </div>

    <div>
        <div><label for="description_longue">description longue</label></div>
        <div><textarea name="description_longue" id="description_longue" cols="30" rows="5"><?php echo $_POST['description_longue'] ?? '' ; ?></textarea></div>
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

    </div>
    <div>
        <div> <label for="photo">Photo</label></div>
        <div><!-- 5- Upload de la photo -->
                <input type="file" name="photo" id="photo" > <!-- ne pas oublier l'attribut enctype sur la balise <form>. -->
                <?php if(isset($_POST['photo'])){// en cas de modification du produit
                    echo '<input type="hidden" name="photo_actu" value="'.$_POST['photo'].'">';} ?>  <!--On veut mettre le chemin de la photo actuelle dans $_POST pour le remettre en BDD -->
        </div>
    </div>
    <div>
        <div><label for="prix">Prix</label></div>
        <div><input type="text" name="prix" id="prix" value="<?php echo $_POST['prix'] ?? '' ; ?>"></div>
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
        <div><input type="text" name="ville" id="ville" value="<?php echo $_POST['ville'] ?? '' ; ?>"></div>
    </div>
    <div>
        <div><label for="couleur">adresse</label></div> 
        <div><textarea name="adresse" id="adresse" cols="30" rows="5"><?php echo $_POST['adresse'] ?? '' ; ?></textarea></div>
    </div>
    <div>
        <div><label for="code_postal">Code postale</label></div>
        <div><input type="text" name="code_postal" id="code_postal" value="<?php echo $_POST['code_postal'] ?? ''; ?>"></div>
    </div>
            
    <div class="mt-2"><input type="submit" value="valider"></div>

</form>

<?php
require_once 'inc/footer.php';