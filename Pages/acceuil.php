<?php 
session_start();
include('../inc/connexion.php');
include('../inc/fonction.php');
$_SESSION['user']=Donne($_SESSION['id_user'],$bdd);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="menu fixe">
        <a href="acceuil.php">Home</a>
        <a href="amis.php">amis</a>
        <a href="suggestion_ami.php">suggestion d'ami</a>
        <a href="demande.php">demande d'amis</a>
        <a href="deco.php">deconnexion</a>
    </div>
    <div>
    Bienvenu 
    <p><?php echo $_SESSION['user']['Nom']; ?> </p>
    <!-- <div class="PDP"><img src="<?php echo $_SESSION['user']['PDP']; ?>" width=200 height=150></p></div> -->
    </div>
    

    Creer une publication:
    <form action="traitement3.php" method="post">
    <input type="text" name="publi">
    <input type="submit" value='envoyer'>
    </form>
    <?php $publi=get_publi($bdd) ;
        if($publi!=1){
            foreach($publi as $publi){?>
                <div>
                <p> 
                    date:<?php echo $publi['date_publi'];?>
                </p>
                <form action="publication.php" method="post">
                        <input type="hidden" value="<?php echo $publi['IdPublication']; ?>" name="Idpub">
                        <input type="hidden" value="<?php echo $publi['Id_membre']; ?>" name="Idm">
                        <input type="submit" value="voir plus">
                </form>
            </div>
     <?php } 
        } ?> 
</body>
</html>