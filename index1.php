
<?php
    session_start();
    require_once 'dbconnect.php';


     // Si l'utilisateur n'est pas connecté → redirection vers login
    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 20px 0;
            margin: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .billet {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .billet p {
            text-align: center;
        }
        .billet h3 {
            margin-top: 0;
            color: #333;
            text-align: center;
            
        }
        .date {
            font-size: 0.9em;
            color: #888;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
            border-radius: 5px;
        }
        button a {
            color: white;
            text-decoration: none;
        }
        button:hover {
            background-color: #45a049;
        }
        .new-billet {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .new-billet:hover {
            background-color: #0b7dda;
        }
        

        
        
    </style>
</head>
<body>


     <!-- afficher le nom du user + logout -->
    <p style="text-align:right; padding:10px;">
        Connecté en tant que <strong><?php echo $_SESSION['login']; ?></strong>
        | <a href="logout.php">Se déconnecter</a>
    </p>


    
    <h1>Mon super blog !</h1>

    <?php
       // Récupérer tous les billets
        $req = $bdd->query('
           SELECT id, titre, contenu,
           DATE_FORMAT(date_creation, "%d/%m/%y à %Hh%imin%ss") AS date_billet
           FROM billets
           ORDER BY date_creation DESC
           LIMIT 0, 5
        ');

        $has_billets = false;
    ?>

    <?php while ($billet = $req->fetch()): ?>
        <?php $has_billets = true; ?>
        <div class="billet">
            <h3><?= htmlspecialchars($billet['titre']) ?></h3>
            <p class="date">le <?= $billet['date_billet'] ?></p>
            <p><?= nl2br(htmlspecialchars($billet['contenu'])) ?></p>
            
          
            <p>         
                <button><a href="commentaires.php?billet=<?= $billet['id'] ?>">Voir les commentaires</a></button><br><br>
                <button><a href="commentaires.php?billet=<?= $billet['id'] ?>">Ajouter un commentaire</a></button>
            </p>
        </div>
    <?php endwhile; ?>

    <?php if (!$has_billets): ?>
        <p>Aucun billet trouvé.</p>
    <?php endif; ?>

    <!-- Lien : créer un nouveau billet -->
    <p>
        <a class="new-billet" href="ajout_billet.php">Créer un nouveau billet</a>
    </p>
</body>
</html>
