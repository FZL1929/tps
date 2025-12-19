<?php
//tp5
try{
    $bdd = new PDO(
        'mysql:host=localhost;dbname=isi_db;charset=utf8',
        'root',
        '',
        
    );
}
catch(Exception $e){
    die('erreur :'.$e->getMessage());
}


?>