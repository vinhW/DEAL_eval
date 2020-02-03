<?php
require_once 'inc/init.php';

$videoParPage = 5;
//1- Affichage des categories :
$resultat = executeRequete("SELECT DISTINCT id_categorie, titre FROM categorie");// On selectionne les differentes categorie

    
        while($categorie = $resultat->fetch(PDO::FETCH_ASSOC)){
             $contenu_gauche .= '<option value='.$categorie['id_categorie'].'>'.$categorie['titre'].'</option>';
        }

//2- Affichage des produits de la categorie choisie :
$currentPage = (int)($_GET['page'] ?? 1) ?: 1;
if($currentPage <= 0){
    throw new Exception('numero de page invalide');
}
// debug($currentPage);
$where = "1";
 if(isset($_GET['categorie']) && $_GET['categorie'] != 'tous' ){
     $where .= ' AND categorie_id='.$_GET['categorie'];
 }

$count = $pdo->query('SELECT count(id_annonce) FROM  annonce WHERE '.$where);

//  }
    $resultat_count = (int)$count->fetch(PDO::FETCH_NUM)[0];
    // debug($resultat_count);
    $perpage = 6;
$pages = ceil($resultat_count / $perpage);
// debug($pages);
if($currentPage > $pages){
    throw new Exception('cette page n\'existe pas');
}
    $offset = $perpage * ($currentPage - 1 );

        if(isset($_GET['categorie']) && $_GET['categorie'] != 'tous' ){// Si existe l'indice "categorie" dans $_GET et que sa valeur est differente de 'tous', c'est qu'on a cliqué sur une categorie de la BDD. On requete alors les produits correspondants :
            
            
            $donnees = executeRequete("SELECT * FROM annonce WHERE categorie_id = :categorie ORDER BY id_annonce  DESC LIMIT $perpage OFFSET $offset ", array(':categorie' =>$_GET['categorie']));
        }
        else{
            $donnees = executeRequete("SELECT a.id_annonce, a.photo, a.titre AS titreA, prix, description_courte, c.titre AS titreC, m.pseudo  FROM annonce a INNER JOIN categorie c ON a.categorie_id = c.id_categorie INNER JOIN membre m ON a.membre_id = m.id_membre ORDER BY id_annonce  DESC LIMIT $perpage OFFSET $offset ");// On selectionne donc tous les produits
        }
      
            while($produit = $donnees->fetch(PDO::FETCH_ASSOC)){ //Boucle while il y a potentiellement plusieurs produits
              


                $contenu_droite .= '<div class="col-sm-4 mb-4">';
                    $contenu_droite .= '<div class="card">';
                    // image cliquable :
                        $contenu_droite .= '<a href="fiche_annonce.php?id_annonce='.$produit['id_annonce'].'"><img src="'.$produit['photo'].'" alt="'.$produit['titreA'].'" class="card-img-top"></a>';

                    // info du produit :

                        $contenu_droite .= '<div class="card-body">';
                            $contenu_droite .= '<h4>'.$produit['titreA'].'</h4>';
                            $contenu_droite .= '<h5>'.$produit['prix'].' €</h5>';
                            $contenu_droite .= '<p>'.$produit['description_courte'].' </p>';
                            $contenu_droite .= '<p>categorie : '.$produit['titreC'].' </p>';
                            $contenu_droite .= '<p>vendeur : '.$produit['pseudo'].' </p>';

                        $contenu_droite .= '</div>';
                    $contenu_droite .= '</div>';
                
                $contenu_droite .= '</div>';
                   
            }
         
       
require_once 'inc/header.php';
?>

<h1 class="mt-4">Nos annonces</h1>
<div class="row">
    <div class="col-md-3">
        <form action=""> <!-- deroulant categorie -->
            <select name="categorie">
            <option value="tous">Toutes les categories</option>
            <?php echo $contenu_gauche; // Pour afficher les categories ?>
            </select>


            <div><input type="submit"></div>
            </form>
    </div>

    <div class="col-md-9">
    <form method="post" action="">
       
        <div><select name="tri" id="tri">
        <option>croissant</option>
        <option>decroissant</option>
        </select>  
        <input type="submit" ></div>
    </form>
    <br>
        <div class="row">
            <?php echo $contenu_droite;// Pour afficher les produits ?>
        </div>
       <div class="d-flex justify-content-between my-4">
            <?php if($currentPage > 1): ?>
            
            <!-- <a href="index.php?page=<?=$currentPage - 1 ?>" class="btn btn-primary">Page précédente</a> -->
            <a href="index.php?<?php 
                $cat = (!empty($_GET['categorie'])) ? '&categorie='.$_GET['categorie'] : ''; 
                echo 'page='.($currentPage - 1).$cat;
                ?>" class="btn btn-primary">Page précédente</a>
            <?php endif ?>
            <?php if($currentPage < $pages): ?>
            <!-- <a href="index.php?page=<?=$currentPage + 1 ?>" class="btn btn-primary ml-auto">Page suivante </a> -->
            <a href="index.php?<?php echo 'page='.($currentPage + 1).((!empty($_GET['categorie'])) ? '&categorie='.$_GET['categorie'] : ''); ?>"  class="btn btn-primary ml-auto">Page suivante </a>
            <?php endif ?>
            
       </div>
    </div>
</div>

<?php

debug($_POST);






require_once 'inc/footer.php';

