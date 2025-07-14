<?php
    function dbconnect(){
        $bdd = mysqli_connect('localhost', 'ETU004246','B9VjWST8','db_s2_ETU004246');
        //$bdd = mysqli_connect('localhost', 'root', '', 'partage_objets');
        return $bdd;
    }
?>