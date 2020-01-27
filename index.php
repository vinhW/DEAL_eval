<?php
require_once 'inc/init.php';
                        $videoParPage = 5;


//1- Affichage des categories :
$resultat = executeRequete("SELECT DISTINCT titre, mots_cles FROM categorie");// On selectionne les differentes categorie

    $contenu_gauche .= '<div class="list-group mb-4">';
    // On affiche le bouton " tous les produits" :
        $contenu_gauche .= '<a href="?categorie=tous" class="list-group-item">Les annonces</a>';
        // On affiche les autres categories :

        while($categorie = $resultat->fetch(PDO::FETCH_ASSOC)){
            // debug($categorie);
             $contenu_gauche .= '<option><a href="?categorie='.$categorie['titre'].'" class="list-group-item" title="'.$categorie['mots_cles'].'">'.$categorie['titre'].'</a></option>';
        }
    $contenu_gauche .= '</div>';

//2- Affichage des produits de la categorie choisie :
$currentPage = (int)($_GET['page'] ?? 1) ?: 1;
if($currentPage <= 0){
    throw new Exception('numero de page invalide');
}
// debug($currentPage);
$count = $pdo->query('SELECT count(id_annonce) FROM  annonce');
    $resultat_count = (int)$count->fetch(PDO::FETCH_NUM)[0];
    debug($resultat_count);
    $perpage = 6;
$pages = ceil($resultat_count / $perpage);
debug($pages);
if($currentPage > $pages){
    throw new Exception('cette page n\'existe pas');
}
    $offset = $perpage * ($currentPage - 1 );
        if(isset($_GET['categorie']) && $_GET['categorie'] != 'tous' ){// Si existe l'indice "categorie" dans $_GET et que sa valeur est differente de 'tous', c'est qu'on a cliqué sur une categorie de la BDD. On requete alors les produits correspondants :
            $donnees = executeRequete("SELECT * FROM annonce WHERE categorie = :categorie", array(':categorie' =>$_GET['categorie']));
        }
        else{
            $donnees = executeRequete("SELECT * FROM annonce ORDER BY id_annonce  DESC LIMIT $perpage OFFSET $offset ");// On selectionne donc tous les produits
        }
         
            while($produit = $donnees->fetch(PDO::FETCH_ASSOC)){ //Boucle while il y a potentiellement plusieurs produits
                // debug($produit)



                $contenu_droite .= '<div class="col-sm-4 mb-4">';
                    $contenu_droite .= '<div class="card">';
                    // image cliquable :
                        $contenu_droite .= '<a href="fiche_annonce.php?id_annonce='.$produit['id_annonce'].'"><img src="'.$produit['photo'].'" alt="'.$produit['titre'].'" class="card-img-top"></a>';

                    // info du produit :

                        $contenu_droite .= '<div class="card-body">';
                            $contenu_droite .= '<h4>'.$produit['titre'].'</h4>';
                            $contenu_droite .= '<h5>'.$produit['prix'].' €</h5>';
                            $contenu_droite .= '<p>'.$produit['description_courte'].' </p>';
                            $contenu_droite .= '<p>categorie :'.$produit['categorie_id'].' </p>';
                            $contenu_droite .= '<p>Membre: '.$produit['membre_id'].' </p>';

                        $contenu_droite .= '</div>';
                    $contenu_droite .= '</div>';
                
                $contenu_droite .= '</div>';
                   
            }
       
require_once 'inc/header.php';
?>

<h1 class="mt-4">Nos annonces</h1>
<div class="row">
    <div class="col-md-3">
    <select>
    <option value="?categorie=tous">Toutes les categories</option>
        <?php echo $contenu_gauche; // Pour afficher les categories ?>
    </select>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php echo $contenu_droite;// Pour afficher les produits ?>
        </div>
       <div class="d-flex justify-content-between my-4">
            <?php if($currentPage > 1): ?>
            
            <a href="index.php?page=<?=$currentPage - 1 ?>" class="btn btn-primary">Page précédente</a>
            <?php endif ?>
            <?php if($currentPage < $pages): ?>
            <a href="index.php?page=<?=$currentPage + 1 ?>" class="btn btn-primary ml-auto">Page suivante </a>
            <?php endif ?>
            
       </div>
    </div>
</div>

<?php









require_once 'inc/footer.php';

