<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connectez-vous</title>
    <link rel="stylesheet" href="css/login.css">
    <?php include('includes/styles.php'); ?>
</head>
<body id="login">
    <div class="form-container">
        <h2>Connectez-vous.</h2>
        <form action="">
            <input type="text" name="pseudo" id="pseudo" placeholder="Pseudo ou mail">
            <input type="text" name="mdp" id="mdp" placeholder="Mot de passe">
            
        </form>
        <p>Pas de compte ? <a href="registered.php">C'est par ici.</a></p>
    </div>
</body>
</html>