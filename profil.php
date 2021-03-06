<?php
require_once 'inc/init.php';
$affiche_formulaire = false;
 $contenu_annonce = '';
 $message = '';
 $note = '';
// exercice profil.
//1- si le visiteur n'est pas connecté on le redirige vers la page de connexion.
//2- afficher son profil tel que dessiné au tableau.


if(!estConnecte()){
header('location:connexion.php');
exit();
}
// debug($_SESSION);
extract($_SESSION['membre']); // extrait tout les indices de l'array aasociatif sous forme de variables, a l'aquelle est affecté la valeur correspondante. Exemple: $_SESSION['membre']['email'] devient la variable $email

if(isset($_GET['id_membre']) && isset($_GET['action']) && $_GET['action'] == 'modifier'){

    $affiche_formulaire = true;
}

if(!empty($_POST)){
    if(!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le pseudo doit contenir entre 4 et 20 caracteres.</div>';
    }
    if(!isset($_POST['nom']) || strlen($_POST['nom']) < 2 || strlen($_POST['nom']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le nom doit contenir entre 2 et 20 caracteres.</div>';
    }
    if(!isset($_POST['prenom']) || strlen($_POST['prenom']) < 2 || strlen($_POST['prenom']) > 20){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le prenom doit contenir entre 2 et 20 caracteres.</div>';
     }
    if(!isset($_POST['telephone']) || strlen($_POST['telephone']) != 10 ){ // si le champs pseudo n'existe pas ou que la 
                // taille est trop court ou trop long, on met un message a l'internaute
                $contenu .= '<div class="alert alert-danger">Le prenom doit contenir entre 2 et 20 caracteres.</div>';
    }
    if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ // la fonction predefinie avec le parametre filter_var() avec 
                // le parametre FILTER_VALIDATE_EMAIL valide si le string fourni est bien un email
                $contenu .= '<div class="alert alert-danger">l\'email est invalide</div>';
    }
    if(!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')  ){// si la civilité est diffente de 'm' et 'f' en meme temps
                $contenu .= '<div class="alert alert-danger">La civilité est invalide</div>';
    }
}

    if(!empty($_POST) && $_POST['mdp'] == ''){

        $contenu = executeRequete("UPDATE membre SET pseudo = :pseudo, nom = :nom, prenom = :prenom, telephone = :telephone, email = :email, civilite = :civilite WHERE id_membre = :id_membre", array( 
                ':id_membre' => $_POST['id_membre'],
                ':pseudo' => $_POST['pseudo'],
                ':nom' => $_POST['nom'],
                ':prenom' => $_POST['prenom'],
                ':telephone' => $_POST['telephone'],
                ':email' => $_POST['email'],
                ':civilite' => $_POST['civilite']
        ));

          if($contenu){
            $affiche_formulaire = false;
          
        $message = '<div class="alert alert-success">La modification a été realisée avec succès</div>';        
          }
  
        $contenu = executeRequete("SELECT * FROM membre WHERE id_membre = :id_membre", array (':id_membre' =>$_POST['id_membre']));
        $membre = $contenu->fetch(PDO::FETCH_ASSOC);
        $_SESSION['membre'] = $membre;

    }

      $donnees = executeRequete("SELECT Round(AVG(note), 2) AS note FROM note WHERE membre_id_cible");
        $note = $donnees->fetch(PDO::FETCH_ASSOC);
debug($note);

        $donnees = executeRequete("SELECT a.id_annonce, a.photo, a.titre AS titreA, prix, description_courte, c.titre AS titreC, m.pseudo FROM annonce a INNER JOIN categorie C ON a.categorie_id = c.id_categorie INNER JOIN membre m ON a.membre_id = m.id_membre WHERE membre_id = :membre_id", array(
              ':membre_id' => $_SESSION['membre']['id_membre']
        ));

        while($annonce = $donnees->fetch(PDO::FETCH_ASSOC)){
          
                $contenu_annonce .= '<div class="col-3 mr-3 ml-5 mb-3 border border-dark">';
                    // $contenu_annonce .= '<div class="card">';
                    // image cliquable :
                        $contenu_annonce .= '<a  href="fiche_annonce.php?id_annonce='.$annonce['id_annonce'].'"><img src="'.$annonce['photo'].'" alt="'.$annonce['titreA'].'" class="card-img-top" ></a>';
                        // $contenu_annonce .= '<div class="card-body">';
                            $contenu_annonce .= '<h4>'.$annonce['titreA'].'</h4>';
                            $contenu_annonce .= '<h5>'.$annonce['prix'].' €</h5>';
                            $contenu_annonce .= '<p>categorie : '.$annonce['titreC'].' </p>';
                            $contenu_annonce .= '<p>vendeur : '.$annonce['pseudo'].' </p>';
                    
                        // $contenu_annonce .= '</div>';
                    // $contenu_annonce .= '</div>';
                
                $contenu_annonce .= '</div>';
        }


require_once 'inc/header.php';

?>
<h1>Profil</h1>
<?php
echo $message;
    echo '<h2>bonjour ' . $_SESSION['membre']['prenom'].' '.$_SESSION['membre']['nom']. '</h2><br>';

   echo '<div class="d-flex justify-content-around">';
   echo     '<div class="col-3">';

    echo      'Pseudo : ' .$_SESSION['membre']['pseudo']. '<br><br>';
    echo      'Nom : ' .$_SESSION['membre']['prenom'].' '.$_SESSION['membre']['nom'] .'<br><br>';
    echo      'Telephone : ' .$_SESSION['membre']['telephone']. '<br><br>';
    echo      'Email : ' .$_SESSION['membre']['email']. '<br><br>';
    echo      'Note du vendeur : ' .$note['note']. '<br><br>';
    echo      '<a href="?action=modifier&id_membre='. $_SESSION['membre']['id_membre'] .'">modifier profil</a><br>';
;
    if($_SESSION['membre']['statut'] == 'admin'){
    echo ' <strong>Vous est un administrateur </strong><br><br>';
    echo    '</div>';
    echo  '<div>';
}
if($affiche_formulaire):
?>
   
   
    <form method="post">
      <div class="form-row">
        <div class="form-group col-md-6">
          <input type="hidden" name="id_membre" value="<?php echo $_SESSION['membre']['id_membre']?>">
          <label for="pseudo">pseudo</label>
          <input type="text" name="pseudo" class="form-control" id="pseudo" placeholder="pseudo" value="<?php echo $_SESSION['membre']['pseudo'] ?>">
        </div>

        <div class="form-group col-md-6">
          <label for="inputPassword4">mot de passe</label>
          <input type="password" name="mdp" class="form-control" id="inputPassword4" placeholder="Password">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
        <label for="prenom">prenom</label>
        <input type="text" name="prenom" class="form-control" id="prenom" placeholder="prenom" value="<?php echo $_SESSION['membre']['prenom'] ?? ''; ?>">
      </div>
      <div class="form-group col-md-6">
        <label for="nom">nom</label>
         <input type="text" name="nom" class="form-control" id="nom" placeholder="nom" value="<?php echo $_SESSION['membre']['nom'] ?? ''; ?>">
      </div>
    </div>

  <div class="form-group">
    <label for="telephone">telephone</label>
    <input type="text" name="telephone" class="form-control" id="telephone" placeholder="telephone" value="<?php echo $_SESSION['membre']['telephone'] ?? ''; ?>">
  </div>
  <div class="form-group">
    <label for="email">email</label>
    <input type="text" name="email" class="form-control" id="email" placeholder="email" value="<?php echo $_SESSION['membre']['email'] ?? ''; ?>">
  </div>

    <div class="form-group col-md-6">
      <label for="civilite">civilité</label><br>
      <input type="radio"  name="civilite" id="homme" value="m" checked><label for="homme">Homme</label>
      <input type="radio"  name="civilite" id="femme" value="f" ><label for="femme" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'f') echo 'checked'; ?>>Femme</label>
    </div>

  <button type="submit" class="btn btn-primary">valider</button>
</form>
    </div>

</div>
<?php
endif;
?>
<h3 class="text-center " >Mes annonces publiées</h3>
<div class="row mt-4 ">

  <?php
echo $contenu_annonce;
?>
<!-- </div> -->

<?php
require_once 'inc/footer.php';

