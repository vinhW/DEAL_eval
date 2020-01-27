<?php
require_once 'inc/init.php';

// exercice profil.
//1- si le visiteur n'est pas connecté on le redirige vers la page de connexion.
//2- afficher son profil tel que dessiné au tableau.


if(!estConnecte()){
header('location:connexion.php');
exit();
}
// debug($_SESSION);
extract($_SESSION['membre']); // extrait tout les indices de l'array aasociatif sous forme de variables, a l'aquelle est affecté la valeur correspondante. Exemple: $_SESSION['membre']['email'] devient la variable $email
require_once 'inc/header.php';
?>


<h1>Profil</h1>
<?php
    echo '<h2>bonjour ' . $_SESSION['membre']['prenom'].' '.$_SESSION['membre']['nom']. '</h2><br>';
if($_SESSION['membre']['statut'] == 'user'){
    echo ' Vous est un administrateur <br>';
}
   
    echo 'votre email est ' .$_SESSION['membre']['email']. '<br>';
 




require_once 'inc/footer.php';

