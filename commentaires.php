<!--tp5-->
<?php
session_start();               // Démarrer la session
require_once 'dbconnect.php';

//si l'utilisateur n'est pas connecté  à login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commentaires</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 { color: #2c3e50; }
        .billet {
            background: #ffffff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .commentaire {
            background: #fafafa;
            border-left: 4px solid #3498db;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .commentaire strong { color: #2980b9; }
        form {
            background: #ffffff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 20px;
        }
        label { font-weight: bold; color: #2c3e50; }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
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
        button:hover { background: #2980b9; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }

        form {
          background: #fdfdfd;
          border-left: 4px solid #2ecc71;
          box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .commentaire {
          background: #fdfdfd;
          border-left: 4px solid #3498db;
          padding: 15px;
          margin-bottom: 20px;
          border-radius: 6px;
          box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .commentaire .auteur {
          font-weight: bold;
          color: #2c3e50;
          font-size: 1.1em;
          margin-bottom: 5px;
        }

        .commentaire .date {
          font-size: 0.9em;
          color: #888;
          margin-bottom: 10px;
        }

        .commentaire .texte {
          font-size: 1em;
          color: #333;
          line-height: 1.5;
        }

        .commentaire:hover {
          background-color: #eef6ff;
          transition: background-color 0.3s ease;
        }




    </style>
</head>
<body>

<!-- affichage user connecté en haut -->
<p style="text-align:right;">Connecté en tant que <strong><?php echo isset($_SESSION['login']) ?></strong>
    <a href="logout.php">Se déconnecter</a>
</p>

<?php



if (isset($_GET['billet'])) {
    $id_billet = (int) $_GET['billet'];
    
}
$id_billet = intval($_GET['billet']);


    // Récupération du billet
    try {
        $req_billet = $bdd->prepare('
            SELECT id, titre, contenu,
            DATE_FORMAT(date_creation, "%d/%m/%Y à %Hh%imin%ss") AS date_creation_fr
            FROM billets
            WHERE id = ?
        ');
        $req_billet->execute([$id_billet]);
        $billet = $req_billet->fetch();
    } catch (PDOException $e) {
        die("Erreur SQL (billet) : " . $e->getMessage());
    }
    //affichage du billet
    if ($billet) {
        echo "<h1>Commentaires sur : " . htmlspecialchars($billet['titre']) . "</h1>";
        echo "<p>" . nl2br(htmlspecialchars($billet['contenu'])) . "</p>";
        echo "<p><em>Publié le " . $billet['date_creation_fr'] . "</em></p>";

        // Récupération des commentaires
        try {
            $req_com = $bdd->prepare('
                SELECT auteur, commentaire,
                DATE_FORMAT(date_commentaire, "%d/%m/%Y à %Hh%imin%ss") AS date_commentaire_fr
                FROM commentaires
                WHERE id_billet = ?
                ORDER BY date_commentaire DESC
            ');
            $req_com->execute([$id_billet]);
            
        } catch (PDOException $e) {
            die("Erreur SQL (commentaires) : " . $e->getMessage());
        }

        echo "<h2>Commentaires</h2>";
        while ($com = $req_com->fetch()) {
            echo "<div class='commentaire'>";
            echo "<div class='auteur'>".htmlspecialchars($com['auteur'])."</div>";
            echo "<div class='date'>" . $com['date_commentaire_fr'] . "</div>";
            echo "<div class='texte'>".nl2br(htmlspecialchars($com['commentaire']))."</div>";
            echo "</div>";
        }
        

        // Formulaire pour l'ajout d'un commentaire
        echo '<form method="post">
            <label for="auteur">Auteur :</label>
            <input type="text" name="auteur" id="auteur" required>
            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" rows="4" required></textarea>
            <button type="submit">Ajouter un commentaire</button>
        </form>';
        // Ajouter un commentaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $auteur = trim($_POST['auteur']);
           $com = trim($_POST['commentaire']);

        if (!empty($auteur) && !empty($com)) {
            $insert = $bdd->prepare("INSERT INTO commentaires (id_billet, auteur, commentaire, date_commentaire)
                VALUES (?, ?, ?, NOW())");
            $insert->execute([$id_billet, $auteur, $com]);
            header("Location: commentaires.php?billet=$id_billet");
            exit;
        }
        else {
            $message = "Veuillez remplir tous les champs.";
        }
      
    
        echo '<p><a href="index1.php">Retour à l\'accueil</a></p>';
    } else {
        //echo "<p>Billet introuvable.</p>";
    }
} else {
    echo "<p>Identifiant du billet manquant.</p>";
}

?>
</body>
</html>
