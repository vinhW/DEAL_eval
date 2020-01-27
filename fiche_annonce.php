<?php
require_once 'inc/init.php';
$contact = ''; // Pour afficher le formulaire d'ajout au contact
$suggestion = '';
$contenu .= '';
$resultat_commentaire = '';
$commentaire_lien = '';
$avis_lien = '';


if(isset($_GET['id_annonce'])  ){ 
    $resultat = executeRequete('SELECT * FROM annonce WHERE id_annonce = :id_annonce', array(':id_annonce' => $_GET['id_annonce']));
        if($resultat->rowCount() == 0){
            header('location:index.php');
            exit();
        }
        //2- Preparation de l'affichage du produit demandé : 
        $produit = $resultat->fetch(PDO::FETCH_ASSOC);
          
         
        extract($produit); // On crée autant de variables qu'il y a d'indices dans le tableau pour en afficher les valeurs plus bas.

    if(isset($id_annonce)){
        $contact .='<form method="post" action="">';   
        $contact .= '<input type="hidden" name="id_produit" value="'. $id_annonce.'">';
        
            if(!estConnecte()){
                $contact .='<input type="submit" name="connexion" value="connectez vous !" class="btn btn-info col-6 offset-1">';

                
            }
        else{
                $contact .='<input type="submit" name="contacter" value="Ecrire au vendeur" class="btn btn-info col-6 offset-1">';
                 $commentaire_lien  .= '<div><a href="?id_annonce='. $id_annonce .'&action=modal">commentaires </a></div>';
                $avis_lien .= '<div><a href="?id_annonce='.$id_annonce .'&action=modal1">Avis et note</a></div>';

        }
    $contact.= '</form>';
    }
}
else{
    header('location:index.php');
    exit();
}

// debug($produit);

if(isset($_POST['contacter'])){// 
    $contenu_gauche ='<div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Ecrire au vendeur !</h4>
                                    </div>
                                    <div class="modal-body">
                                    <form action="">
                                    <textarea name="#" id="" cols="45" rows="10" ></textarea>
                                    <p class="mt-2"><a href="index.php" >retour a l\'accueil</a></p>
                                    <input type="submit" name="formulaire_contact" value="confirmer " class="btn btn-info col-6 ">
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
}

if(isset($_POST['connexion'])){// 
    $contenu_gauche ='<div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">connectez vous !</h1>
                                    </div>
                                    <div class="modal-body">
                                    <form action="">
                                    <h5> veuillez vous, connecter pour contacter le vendeur</h5>
                                 
                                   <button class="btn col-6"> <a href="connexion.php">CONNEXION</a></button>
                                    </form> 
                                   </div>
                                </div>
                            </div>
                        </div>';
}

if(isset($_GET['id_annonce']) && isset($_GET['action']) && $_GET['action'] == 'modal'){// Si "ajout_panier" existe dans $_POST, c'est qu'on a cliqué sur le bouton "panier". On affiche donc la modale correspondante :
    $contenu ='<div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">envoyer un commentaire !</h4>
                                    </div>
                                    <div class="modal-body">
                                    
                                    <form method="post" action="commentaire.php">
                                    <label for="commentaire">commentaires</label>
                                    <textarea name="commentaire" id="commentaire" cols="45" rows="7" ></textarea><br><br>
                                   <input type="hidden" name="id_annonce" value="'.$_GET['id_annonce'].'">
                                    <input type="submit" value="envoyer votre commentaire " class="btn btn-info col-6 ">
                                    </form> 

                                    </div>
                                </div>
                            </div>
                        </div>';
                        
}
if(isset($_GET['id_annonce']) && isset($_GET['action']) && $_GET['action'] == 'modal1'){// Si "ajout_panier" existe dans $_POST, c'est qu'on a cliqué sur le bouton "panier". On affiche donc la modale correspondante :
    $contenu ='<div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Deposez un avis !</h4>
                                    </div>
                                    <div class="modal-body">
                                    
                                    <form method="post" action="commentaire.php">
                                    <label for="avis">avis</label><br>
                                    <textarea name="avis" id="avis" cols="45" rows="3" ></textarea><br>
                                    <label for="note" class="mt-2">Note</label>
                                    <select name="note" id="note">
                                      
                                        <option checked >1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                    <br>
                                 <input type="hidden" name="id_annonce" value="'.$_GET['id_annonce'].'">
                                 <input type="hidden" name="id_membre" value="'.$membre_id.'">

                                    <input type="submit" value="Donnez votre avis " class="btn btn-info col-6 mt-3">
                                    </form> 

                                    </div>
                                </div>
                            </div>
                        </div>';
                        
}
debug($_POST);
$requete = executeRequete("SELECT * FROM annonce WHERE categorie_id = :categorie_id AND id_annonce != :id_annonce  ORDER BY RAND() LIMIT 2", 
array(':categorie_id' => $categorie_id, ':id_annonce' => $id_annonce));

while($produit = $requete->fetch(PDO::FETCH_ASSOC)){
$suggestion .= '<div class="col-4">';
    $suggestion .= '<h4>' . $produit['titre'] .'</h4>';
    $suggestion .= '<a href="?id_annonce=' . $produit['id_annonce'] .'"><img src="'. $produit['photo'].'" alt="'. $produit['titre'] .'" class="img-fluid"></a>';
$suggestion .= '</div>';

}




require_once 'inc/header.php';

echo $contenu_gauche;// Affiche la modale de confirmation d'ajout au panier
echo $contenu;// Affiche la modale de confirmation d'ajout au panier

?>


<div class="row">
    <div class="col-12">
        <h1><?php echo $titre; ?></h1>
    </div>
    <div class="col-md-8">
        <img src="<?php echo $photo; ?>" alt="<?php echo $titre; ?>" class="img-fluid">
    </div>
    <div class="col-md-4">
        <?php echo $contact; ?>
        <h3>description</h3>
        <p><?php $description; ?></p>

        <h3>details</h3>
        <ul>
            <li>Description : <?php echo $description_longue; ?></li>
            <li>titre : <?php echo $titre; ?></li>
            <li>adresse : <?php echo $adresse; ?></li>
            <li>date de publication : <?php echo $date_enregistrement; ?></li>
            <li>MEMBRE : <?php echo $membre_id; ?></li>


        </ul>

        <h4>prix : <?php echo $prix ; ?> €</h4>

      

        <p><a href="index.php?">Retour vers l'acceuil</a></p>
    </div>
</div>


<?php
echo $commentaire_lien;
echo $avis_lien;
?> 
<!-- EXERCICE -->
<hr>
<div class="row">
    <div class="col-12">
        <h3>Suggestion de produits</h3>
    </div>
    <?php echo $suggestion; ?>
</div>





<script>
// Script affichage de la modale :
$(function() {
    $('#myModal').modal('show'); });


</script>

<?php
require_once 'inc/footer.php';