<?php
require_once '../inc/init.php.'; //attention au ../


if (!estAdmin()){
    header('location:../connexion.php'); // si le membre n'est pas connecté ou n'est pas admin, on le redirige vers la page de connexion
    exit(); //on quitte le script
}

// debug($_SESSION);
$produit_actuel = array();





// membre mieux notés -------------------------------------------------------
$resultat = executeRequete("SELECT m.prenom, m.nom, m.pseudo,Round(AVG(n.note), 2) AS note, COUNT(n.avis) FROM note n INNER JOIN membre m ON n.membre_id_cible = m.id_membre  GROUP BY n.membre_id_cible ORDER BY note  DESC LIMIT 5"); // on selectionne tout les produits
 $contenu .= '<div><strong>Top 5 des membres les mieux notés </strong></div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
      
        $contenu .= '<th>prenom</th>';
        $contenu .= '<th>nom</th>';
        $contenu .= '<th>pseudo</th>';
        $contenu .= '<th>note moyenne</th>';
<<<<<<< HEAD
        $contenu .= '<th>nombre d\'avis</th>';
=======
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
        $contenu .= '</tr>';

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit_actuel est un array avec toutes les informations d'un seul produit_actuel à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
  
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur){
                $contenu .= '<td>' . $valeur . '</td>';
        }
    $contenu .= '</tr>';
<<<<<<< HEAD
    // debug($produit);
=======
    debug($produit);
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
}
    $contenu .='</table>';
$contenu .= '</div>';

// membre actifs ----------------------------------------------------------------
<<<<<<< HEAD
$resultat = executeRequete("SELECT m.prenom, m.nom, m.pseudo, COUNT(c.id_commentaire) FROM commentaire c INNER JOIN membre m ON c.membre_id = m.id_membre WHERE m.id_membre GROUP BY c.membre_id ORDER BY COUNT(c.id_commentaire) DESC LIMIT 5"); // on selectionne tout les produits
=======
$resultat = executeRequete("SELECT m.prenom, m.nom, m.pseudo, COUNT(a.id_annonce) FROM annonce a INNER JOIN membre m ON a.membre_id = m.id_membre WHERE a.id_annonce ORDER BY id_annonce DESC LIMIT 5"); // on selectionne tout les produits
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
 $contenu .= '<div><strong>Top 5 des membres les plus actifs </strong></div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>prenom</th>';
        $contenu .= '<th>nom</th>';
        $contenu .= '<th>pseudo</th>';
<<<<<<< HEAD
        $contenu .= '<th>nombre de commentaires</th>';
=======
        $contenu .= '<th>nombre d\'annonces</th>';
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
        $contenu .= '</tr>';

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit_actuel est un array avec toutes les informations d'un seul produit_actuel à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
  
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur){
                $contenu .= '<td>' . $valeur . '</td>';
        }
    $contenu .= '</tr>';
<<<<<<< HEAD
    // debug($produit);
=======
    debug($produit);
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
}
    $contenu .='</table>';
$contenu .= '</div>';

// Annonce les plus anciennes----------------------------------------------------------------
$resultat = executeRequete("SELECT m.prenom, m.nom, m.pseudo,a.titre, DATE_FORMAT(a.date_enregistrement , '%d/%m/%Y %h:%i') FROM annonce a INNER JOIN membre m ON a.membre_id = m.id_membre  ORDER BY a.date_enregistrement ASC LIMIT 5"); // on selectionne tout les produits
 $contenu .= '<div><strong>Top 5 des annonces les plus anciennes </strong></div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>prenom</th>';
        $contenu .= '<th>nom</th>';
        $contenu .= '<th>pseudo</th>';
        $contenu .= '<th>annonce</th>';
        $contenu .= '<th>date</th>';
        $contenu .= '</tr>';

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit_actuel est un array avec toutes les informations d'un seul produit_actuel à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
  
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur){
                $contenu .= '<td>' . $valeur . '</td>';
        }
    $contenu .= '</tr>';
<<<<<<< HEAD
    // debug($produit);
=======
    debug($produit);
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
}
    $contenu .='</table>';
$contenu .= '</div>';

// categorie contenant le plus d'annonce----------------------------------------------------------------
<<<<<<< HEAD
$resultat = executeRequete("SELECT c.titre,COUNT(a.id_annonce) FROM categorie c INNER JOIN annonce a ON c.id_categorie = a.categorie_id  GROUP BY c.titre ORDER BY a.id_annonce ASC LIMIT 5"); // on selectionne tout les produits
=======
$resultat = executeRequete("SELECT c.titre,COUNT(a.id_annonce) FROM categorie c INNER JOIN annonce a ON c.id_categorie = a.categorie_id  ORDER BY a.id_annonce ASC LIMIT 5"); // on selectionne tout les produits
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
 $contenu .= '<div><strong>Top 5 des categories les plus publiées </strong></div>';

$contenu .= '<div class"table-responsive">';
    $contenu .='<table class="table">';
    // Lignes des entête du tableau :
    $contenu .= '<tr>';
        $contenu .= '<th>categories</th>';
        $contenu .= '<th>nombre d\'annonce de la categorie</th>';
        $contenu .= '</tr>';

while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) { // produit_actuel est un array avec toutes les informations d'un seul produit_actuel à chaque tour de boucle. On le parcourt donc avec une boucle foreach :
  
    $contenu .= '<tr>';
        foreach($produit as $indice =>$valeur){
                $contenu .= '<td>' . $valeur . '</td>';
        }
    $contenu .= '</tr>';
<<<<<<< HEAD
    // debug($produit);
=======
    debug($produit);
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
}
    $contenu .='</table>';
$contenu .= '</div>';



require_once '../inc/header.php.';
// 2. Navigation entre les pages d'administration :

?>

<h1 class="mt-4">Gestion des Notes</h1>
<ul class="nav nav-tabs">
    <li><a class="nav-link " href="gestion_categorie.php">Gestion des categories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion des membres</a></li>
    <li><a class="nav-link" href="../gestion_annonce.php">Gestion des annonces</a></li>
<<<<<<< HEAD
    <li><a class="nav-link" href="gestion_notes.php">Gestion des notes</a></li>
    <li><a class="nav-link" href="gestion_commentaires.php">Gestion des commentaires</a></li>
    <li><a class="nav-link active" href="gestion_commentaires.php">Gestion des statistiques</a></li>
=======
    <li><a class="nav-link active" href="gestion_notes.php">Gestion des notes</a></li>
    <li><a class="nav-link" href="gestion_commentaires.php">Gestion des commentaires</a></li>
    <li><a class="nav-link" href="gestion_commentaires.php">Gestion des statistiques</a></li>
>>>>>>> b2ce2e92171b149eed882e9d956c1ae848a003f2
 
</ul>


<?php

echo $contenu.'<br><pre>'; //pour afficher notament le tableau des produits





debug($produit_actuel);
require_once '../inc/footer.php.';
