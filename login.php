<?php

require_once 'src/includes-user.php';

use Sanitize\Sanitize;
use Session\Session;
use User\User;

if (!empty($_SESSION['session']) && $_SESSION['session']->check() && $_SESSION['user']->isGranted(['ADMIN', 'MODERATOR', 'REGISTERED'])) {
    header('Location: dashboard.php');
    exit;
}

if (Sanitize::checkIssetFields($_POST, ['login', 'password']) && Sanitize::checkEmptyFields($_POST, ['login', 'login'])) {

    $login = trim(Sanitize::sanitize($_POST['login']));
    $pass = trim(Sanitize::sanitize($_POST['password']));

    $user = new User($db);

    if ($user->auth($login, $pass)) {

        $_SESSION['user'] = $user;
        $_SESSION['session'] = new Session();
        header('Location: dashboard.php');
        exit;

    } else $speaker->add('error-password-match');
}

?><!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8"/>
	<meta http-equiv="pragma" content="nocache"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/theme.css">
</head>
<body id="login">
<div class="form-container">
	<h2>Connectez-vous.</h2>
	<form action="login.php" method="post">
        <?php echo $speaker->generate(); ?>
		<input type="text" name="login" id="email" placeholder="Email">
		<input type="password" name="password" id="password" placeholder="Mot de passe">
		<button class="btn">Connexion</button>
	</form>
	<p>Pas de compte ? <a href="register.php">C'est par ici.</a> /
		<a href="index.php">Accueil</a>
	</p>
</div>
</body>
</html>

