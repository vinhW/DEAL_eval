<?php
require_once 'inc/init.php';

if(isset($_POST['commentaire'])){
    $requete = executeRequete("INSERT INTO commentaire (commentaire, membre_id, annonce_id, date_enregistrement) VALUES (:commentaire,:membre_id, :annonce_id, NOW())", array(

           
            ':commentaire' => $_POST['commentaire'],
            ':membre_id' => $_SESSION['membre']['id_membre'],
            ':annonce_id' => $_POST['id_annonce']
    ));
 
 
header('location:fiche_annonce.php?id_annonce='.$_POST['id_annonce']);
exit();
   }

                // avis et note
 debug($_POST);

    
if(isset($_POST['avis'])){
        if(!isset($_POST['avis']) || strlen($_POST['avis']) < 5 || strlen($_POST['avis']) > 50){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">L\'avis doit contenir entre 1 et 20 caracteres</div>';
    }
    
    $requete = executeRequete("INSERT INTO note (note, avis,membre_id_auteur, membre_id_cible, date_enregistrement) VALUES (:note, :avis, :membre_id_auteur, :membre_id_cible, NOW())", array(

           
            ':note' => $_POST['note'],
            ':avis' => $_POST['avis'],
            ':membre_id_auteur' => $_SESSION['membre']['id_membre'],
            ':membre_id_cible' => $_POST['id_membre']
    ));
 
header('location:fiche_annonce.php?id_annonce='.$_POST['id_annonce']);
exit();

   }

  