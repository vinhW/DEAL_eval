<!DOCTYPE html>
<html lang="fr">
<head>

       <!-- CDN Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
       <!-- CDN Bootstrap js -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <!-- navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <!-- marque -->
            <a href="<?php echo RACINE_SITE . 'index.php'; ?>" class="navbar-brand">DEAL</a> <!-- on utilise notre constante pour faire un chemin absolu quelque soit le fichier dans lequel sera inclus ce header -->
            
            <!-- Burger -->
            <button class="navbar-toggler" type ="button" data-toggle="collapse" data-target="#nav1" aria-controls="navbarResponsive" aria-expanded ="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Le menu  -->
            <div class="collapse navbar-collapse" id="nav1">
                <ul class="navbar-nav ml-auto">
                    <?php 
                    
                
                        
            
                        if(estAdmin()){
                            echo '<li><a class="nav-link" href="'.RACINE_SITE.'admin/gestion_categorie.php">Gestion du site</a></li>';
                        }
                        if(estConnecte()){ // Membre connecté
                            echo '<li><a class="nav-link" href="'.RACINE_SITE.'depose_annonce.php">Déposer une annonce</a></li>';   
                            echo '<li><a class="nav-link" href="'.RACINE_SITE.'profil.php">'.$_SESSION['membre']['prenom'].'</a></li>';
                            echo '<li><a class="nav-link" href="'.RACINE_SITE.'connexion.php?action=deconnexion">Déconnexion</a></li>';      
                   
                        }else{ // membre deconnecté
                            echo '<li><a class="nav-link" href="'.RACINE_SITE.'inscription.php">Inscription</a></li>';
                            echo '<li><a class="nav-link" href="'.RACINE_SITE.'connexion.php">Connexion</a></li>';
                            }

                    ?>
                </ul>
            </div> <!-- fin du menu -->
        </div> <!--- container  --->
    </nav>


                            <!-- Contenu de ma page -->

    <div class="container" style="min-height: 80vh">
        <div class="row">
            <div class="col-12">
            