<?php
session_start(); // Activer les sessions

 $options = [
    'php' => 'PHP',
    'js'=> 'JavaScript',
    'python'=> 'Python',
    'java'=> 'Java',
 ];
// ===== FICHIER TEXTE =====
$file_texte='votes.txt';

//Initialiser les votes
if(file_exists($file_texte)){
    //lire et convertir le contenu du fichier
    $contenu=file_get_contents($file_texte);
    $votes=unserialize($contenu);//Convertit le texte en tableau PHP.
    //La fonction unserialize() en PHP sert à reconvertir une chaîne de texte (string) en tableau ou objet PHP, après qu’elle ait été stockée avec serialize()
    if (!is_array($votes)) { // Si le fichier est corrompu ou vide
        $votes = array_fill_keys(array_keys($options), 0); // Crée un tableau avec toutes les options initialisées à 0
        file_put_contents($file_texte, serialize($votes)); // Sauvegarde ce tableau dans le fichier
    }
}else{
    //créer un tableau vide avec les clésdes options
    $votes = array_fill_keys(array_keys($options), 0);
    //sauvegarder dans le fichier
    file_put_contents($file_texte,serialize($votes));

}

// ===== Vérifier si l'utilisateur a déjà voté =====
$deja_vote_cookie  = isset($_COOKIE['a_vote']);   // Vérifie si un cookie "a_vote" existe (empêche de revoter pendant 30 jours)
$deja_vote_session = isset($_SESSION['a_vote']);  // Vérifie si la session contient "a_vote" (empêche de revoter dans la même session)
$message = ""; // Variable pour afficher un message à l'utilisateur

// traitement du vote avec session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote']) && !isset($_SESSION['a_vote'])) {
    $choix = $_POST['vote'];
    if (isset($votes[$choix])) {
        $votes[$choix]++;

        // Sauvegarder les votes mis à jour
        file_put_contents($file_texte, serialize($votes));

        // Marquer que l'utilisateur a voté dans cette session
        $_SESSION['a_vote'] = true;
        $_SESSION['choix_vote'] = $choix;

        echo "<p>Merci pour votre vote pour <strong>{$options[$choix]}</strong> !</p>";
    }
} elseif (isset($_SESSION['a_vote'])) {
    echo "<p><em>Vous avez déjà voté dans cette session. Votre choix était : <strong>{$options[$_SESSION['choix_vote']]}</strong></em></p>";
}


// affichage des résultats
echo "<h2>Résultats du sondage :</h2>";
$total = array_sum($votes);

if ($total > 0) {
    foreach ($votes as $key => $nb) {
        $pourcentage = ($nb / $total) * 100;
        echo "<p><strong>{$options[$key]}</strong> : {$nb} votes (" . number_format($pourcentage, 1) . "%)</p>";
    }
} else {
    echo "<p>Aucun vote pour le moment.</p>";
}




 //vérifier si l'utilisateur  deja voté
 $deja_vote=isset($_COOKIE['a_vote']);
 $message='';
 //traitement du formulaire de vote
 if($_SERVER['REQUEST_METHOD']==='POST'&& isset($_POST['vote'])&&!$deja_vote){
    $choix=$_POST['vote'];
    //vérifier que le choix est valide
    if(isset($options[$choix])){
        //compter le vote ddans les cookies
        $nom_cookie_votes='votes_'.$choix;
        $votes_actuels=isset($_COOKIE['$nom_cookie_votes'])? intval($_COOKIE['$nom_cookies_vote']):0;
        $nouveaux_votes=$votes_actuels+1;
        //sauvegarder le nouveau total pour ce choix
        setcookie($nom_cookie_votes,$nouveaux_votes,time()+365*24*3600);//3
        //marquer cet utilisateur comme ayant vote
        setcookie('a_vote','true',time()+ 3);//30*24*3600
        setcookie('choix_vote',$choix,time()+ 3);//30*24*3600
        setcookie('date_vote', date('d/m/Y H:i'),time()+ 3);//30*24*3600
        $message= 'Merci pour votre vote!Vous avez choix:'.$options[$choix];
        //Mettre à jour les variables pour éviter les warnings
        $deja_vote=true;
        $_COOKIES['choix']=$choix; //Simuler cookie immédiatement
        $_COOKIE['date_vote']=date('d/m/Y H:i'); //Simuler cookie immédiatement
        
    }

 }
 //calculer les resultats depuis les cookies
        $resultats=[];
        $total_votes= 0;
        $votes= 0;
        foreach($options as $key => $label){
            $nom_cookie =isset($_COOKIE['$nom_cookies']) ?intval($_COOKIE['$nom_cookie']):0;
            $resultats[$key]=$votes;
            $total_votes+=$votes;

        }

?>
<h1>Sondage: Quel est votre langage de programmation préféré?</h1>
<?php if ($message): ?>
   <p><strong><?php echo  $message;?></strong></p>
   <?php endif; ?>
   <?php if ($deja_vote): ?>
    <h2>Vous avez deja voté</h2>
    <p>votre choix: <strong><?php echo $_COOKIE['date_vote']; ?></p>
    <p><em>Date de votre vote à nouveau dans 30 jours</em></p>
    <?php else: ?>
    <h2>Faires votre choix:</h2>
    <form method="POST">
        <?php foreach($options as $key => $label): ?>
            <p>
                <input type="radio" name="vote" value="<?php echo $key;?>"id="<?php echo $key;?>"required>
                <label for="<?php echo $key;?>"> <?php echo $label;?></label>
        </p>
        <?php endforeach;?>
        <button type="submit">Soumettre mon vote</button>
        </form>
        <?php endif;?>
        <hr>
        <h2>resultats du sontage</h2>
        <?php if($total_votes>0): ?>
            <p><strong>Total des votes :<?php echo $total_votes;?></strong></p>
            <?php foreach ($resultats as $key => $votes): ?>
                <?php
                $pourcentage = ($vote / $total_votes) *100;
                $pourcentage = number_format($pourcentage,1);
                ?>
                <p>
                    <strong><?php echo $options[$key];?></strong>
                    <?php echo $votes;?>votes(<?php echo $pourcentage_format;?>%)
                </p>
                <?php endforeach;?>
                <?php else: ?>
                    <p><em>Aucun vote pour le momment voyez le premier à voter!</em></p>
                    <?php endif;?>