<!--Créer la table users dans MySQL-->

<!--CREATE TABLE users (
    login VARCHAR(50) PRIMARY KEY, 
    nom VARCHAR(50),
    prenom VARCHAR(50),
    password VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL,

);
--ajouter un utilisateur test
INSERT INTO users (login,nom,prenom, password,email)
VALUES ('user','labari','fatima', 'abc123','fatimalabari@gmail.com');
-->
<?php
session_start();
require_once 'dbconnect.php';




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = $_POST['login'];
    //$nom = $_POST['nom'];
    //$prenom = $_POST['prenom'];
    $password = $_POST['password'];
    //$email = $_POST['email'];
    // Vérifier l'utilisateur dans la BD
    //$stmt = $bdd->prepare("SELECT * FROM users WHERE login = ?");
    //$stmt->execute([$login]);
    //$user = $stmt->fetch();
    
    // Hachage du mot de passe
    $password="abc123";
    $hash = password_hash($password, PASSWORD_DEFAULT);
    //$rq=$pdd->prepare("INSERT INTO users(email,password) VALUES(?,?)");
    //$rq->execute(['user@email.com',$hash]);

    //connexion
    $saisi = $password;
    //recuperation du hash depuis BDD
    $rq = $bdd->prepare("SELECT login, password FROM users WHERE login = ?");
    $rq->execute([$login]);
    $user =$rq->fetch();
    //verifier
    
    if ($user && password_verify($saisi, $user['password'])) {
        $_SESSION['login'] = $user['login'];
        //echo $_SESSION['login'] ;
        header("Location: index1.php");
        exit;
    } else {
       echo "<p style='color:red;'> Mot de passe incorrect</p>";
    }
}

?>
<!DOCTYPE html>
<form method="post">
    <label>Login:</label><input type="text" name="login" required><br><br>
    <label>Mot de passe:</label><input type="password" name="password" required><br><br>
    <button type="submit">Se connecter</button>
</form>
</html>





