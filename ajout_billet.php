<?php
//tp5

session_start();
require_once 'dbconnect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
  <head>
    <meta charset="UTF-8">
    <title> Ajout d'un billet </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #2c3e50;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
            color: #2c3e50;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: inherit;
        }

        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #2980b9;
        }

        p {
            margin-top: 20px;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .success {
            background: #eafaf1;
            border: 1px solid #2ecc71;
            color: #27ae60;
            padding: 10px;
            border-radius: 5px;
            max-width: 600px;
        }
    </style>
  </head>
    <body>
    <h1>Ajouter un nouveau billet</h1>
    <?php
      require_once ('dbconnect.php');
      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $titre = trim($_POST['titre']);
        $contenu = trim($_POST['contenu']);
        try {
          $req = $bdd->prepare(
             'INSERT INTO billets(titre, contenu, date_creation) VALUES(?, ?, NOW())'
          );
          $req->execute([$titre, $contenu]);
          echo "<p>Billet ajouté avec succès !</p>";
        } catch (PDOException $e) {
          echo "<p>Erreur lors de l'ajout : " . $e->getMessage() . "</p>";
        }
        echo '<p><a href="index1.php">Retour à l\'accueil</a></p>';
      }else{
       ?>
    <form method="post">
        <label for="titre">Titre :</label><br>
        <input type="text" name="titre" id="titre" required><br><br>
        <label for="contenu">Contenu :</label><br>
        <textarea name="contenu" id="contenu" rows="6" cols="50" required></textarea><br><br>
        <button type="submit">Ajouter le billet</button>
    </form>
    <p><a href="index1.php">Retour à l'accueil</a></p>
    <?php } ?>
   
      <!--button logout-->
    <form action="logout.php" method="post" style="display:inline;">
      <button type="submit">Se déconnecter</button>
    </form>

  </body>
</html>
