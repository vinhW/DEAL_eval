<?php

require_once '../inc/init.php'; // attention au ../

//1- Verification administration :
    if(!estAdmin()){
        header('locatoin:../connexion.php');// Si le membre n'est pas connecté ou pas admin. On le redirige dans la page de connexion.
        exit();// On quitte le script
    }
    //4- Enregistremnent du produit :
    debug($_POST);

    if($_POST){ // equivalent a !empty($_POST), qui signifie que le formulaire a été envoyé

        //ici il faudrait mettre tout les controles sur le formulaire, ce qu'on ne fait pas pour alléger un peu...

        $photo_bdd = ''; // par defaut le champ est un string vide en BDD


        //9- Modification de la photo:
            if(isset($_POST['photo_actu'])){ //  si on est en train de modifier le produit, on remet le chemin de la photo en BDD actuellement dans le formulaire
                $photo_bdd = $_POST['photo_actu'];




            }

        if(!empty($_FILES['photo']['name'])){// Si nous avons un nom du fichier, cest que nous sommes en train de le telecharger
            
            $fichier_photo = 'ref'. $_POST['reference'].'_'.$_FILES['photo']['name'];
            // construisons un nom de fichier photo unique sur la base de la reference fournie dans le formulaire.

            $photo_bdd = 'photo/' . $fichier_photo;// ceci est le chemin relatifde la photo, chemin enregistrer en BDD. Il sera utilisé par les attribut SRC des balises <img> pour afficher la photo.
            copy($_FILES['photo']['tmp_name'],'../' .$photo_bdd);// On copie le fichier temporaire qui si trouve a l'adresse contenue dans $_FILES['photo']['tmp_name'] vers l'endroit defintif contenu dans $photo_bdd.

            //On enregistre uniquement le chemin de la photo en BDD, mais pas le fichier en tant que tel. Ce dernier est bien dans le repertoire "photo/" du site.

        }
        //5- suite : 
        debug($_FILES); // On voit que la superglobale $_FILES possede un indice "photo correspondant au "name" de l'input type "file" du formulaire. On y trouve aussi un indice predefini "name" qui contient le nom du fichier en cours d'upload, et un indice tmp_name contenant le chemin du fichier temporaire uploadé.





        //insertion du produit en BDD :
        $requete = executeRequete("REPLACE INTO produit VALUES(:id_produit, :reference, :categorie, :titre, :description, :couleur, :taille, :public,:photo, :prix, :stock )", array(
                                                                    ':id_produit' => $_POST['id_produit'],
                                                                    ':reference' => $_POST['reference'],
                                                                    ':categorie' => $_POST['categorie'],
                                                                    ':titre' => $_POST['titre'],
                                                                    ':description' => $_POST['description'],
                                                                    ':couleur' => $_POST['couleur'],
                                                                    ':taille' => $_POST['taille'],
                                                                    ':public' => $_POST['public'],
                                                                    ':photo' => $photo_bdd,
                                                                    ':prix' => $_POST['prix'],
                                                                    ':stock' => $_POST['stock']

        ));
// REPLACE INTO se comporete comme un INSERT quand l'id_produit n'existe pas (0), ou  comme un UPDATE quand l'id_produit fourni existe
        if($requete){// si la fonction executeRequet retourne un objet PDOStatement (donc implicitement evalué a TRUE), cest la requete a marché
            $contenu .= '<div class="alert alert-success">Le produit a été enregistré.</div>';
        }
        else{ // sinon on a recu false en cas d'erreur sur la requete
            $contenu .= '<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';

        }

    } // fin du if($_POST)




    //8- remplissage du formulaire de modification de produit :
        if(isset($_GET['id_produit'])){// si on a recu l'id_produit dans l'URL, c'est qu'on a demandé la modification du produit
            // On selectionne les infos du produit en BDD pour remplir le formulaire :
                $resultat = executeRequete( "SELECT * FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));
                $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);// pas de while car nous avons qu'un seul produit par id
            }

require_once '../inc/header.php';
//2- Navigation entre les pages d'aministration :


?>
<h1 class="mt-4">Gestion boutique</h1>
<ul class="nav nav-tabs">
    <li><a class="nav-link" href="gestion_boutique.php">Affichage des produits</a></li>
    <li><a class="nav-link active" href="formulaire_produit.php">Ajout/modification produit</a></li>
</ul>
<?php

    echo $contenu; // Pour afficher notamment le tableau des produits
//3- Formulaire d'ajout ou de modification de produit :
    ?>
<form method='post' action="" enctype="multipart/form-data">
    <!-- enctype specifie que le formulaire envoie des données binaires (fichier) en plus du texte (champs du formaulaire) : permet d'uploader un fichier photo -->
    <div>
        <input type="hidden" name="id_produit" value="<?php echo $produit_actuel['id_produit'] ?? 0 ; ?>">
        <!-- On met un type hidden pour eviter de le modifier par accident. On precise une value a 0 pour que lors de l'insertion en BDD l'id_produit s'auto-incremente (creation de produit). -->
    </div>
    <div>
        <div><label for="reference">Reference</label></div>
        <div><input type="text" name="reference" id="reference" value="<?php echo $produit_actuel['reference'] ?? '' ; ?>"></div>
    </div>

    <div>
        <div><label for="categorie">catégorie</label></div>
        <div><input type="text" name="categorie" id="categorie" value="<?php echo $produit_actuel['categorie'] ?? '' ; ?>"></div>
    </div>

    <div>
        <div><label for="titre">Titre</label></div>
        <div><input type="text" name="titre" id="titre" value="<?php echo $produit_actuel['titre'] ?? '' ; ?>"></div>
    </div>

    <div>
        <div><label for="description">Description</label></div>
        <div><textarea name="description" id="description"><?php echo $produit_actuel['description'] ?? '' ; ?></textarea></div>
    </div>

    <div>
        <div><label for="couleur">Couleur</label></div>
        <div><input type="text" name="couleur" id="couleur" value="<?php echo $produit_actuel['couleur'] ?? '' ; ?>"></div>
    </div>

    <div>
        <div><label for="taille">Taille</label></div>
        <div>
            <select name="taille">
                <option value="s" <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 's') echo 'selected'; ?> >S</option>
                <option value="m" <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'm') echo 'selected'; ?> >M</option>
                <option value="l" <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'l') echo 'selected'; ?> >L</option>
                <option value="xl" <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'xl') echo 'selected'; ?> >XL</option>
            </select>
        </div>

    </div>

    <div>
        <div><label for="public">public</label></div>
        <div><input type="radio" name="public" id="public" value="m" checked>homme</div>
        <div><input type="radio" name="public" id="public" value="f" <?php if(isset($produit_actuel['public']) && $produit_actuel['public'] == 'f') echo 'checked'; ?> >femme</div>
        <div><input type="radio" name="public" id="public" value="mixte" <?php if(isset($produit_actuel['public']) && $produit_actuel['public'] == 'mixte') echo 'selected'; ?> >mixte</div>
    </div>
    <div>
        <div> <label for="photo">Photo</label></div>
        <div><!-- 5- Upload de la photo -->
                <input type="file" name="photo" id="photo" > <!-- ne pas oublier l'attribut enctype sur la balise <form>. -->
                <?php if(isset($produit_actuel['photo'])){// en cas de modification du produit
                      echo '<input type="hidden" name="photo_actu" value="'.$produit_actuel['photo'].'">'; // On veut mettre le chemin de la photo actuelle dans $_POST pour le remettre en BDD

                } ?>
        </div>
    </div>
<!--  -->
    <div>
        <div><label for="prix">Prix</label></div>
        <div><input type="text" name="prix" id="prix" value="<?php echo $produit_actuel['prix'] ?? 0 ; ?>"></div>
    </div>

    <div>
        <div><label for="stock">Stock</label></div>
        <div><input type="text" name="stock" id="stock" value="<?php echo $produit_actuel['stock'] ?? 0 ; ?>"></div>
    </div>

    <div class="mt-2"><input type="submit" value="valider"></div>

</form>

<?php
require_once '../inc/footer.php';