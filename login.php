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
	<meta http-equiv="refresh" content="">
	<!-- Style -->
	<link rel="stylesheet" type="text/css" href="css/theme.css">
</head>
<body>

<section>

	<h1>Say Hello.</h1>

	<form action="" method="post">
        <?php echo $speaker->generate(); ?>
		<label for="login">Login</label>
		<input type="text" name="login" id="login">
		<label for="pass">Password</label>
		<input type="password" name="password" id="password">
		<input type="submit" value="Check">
	</form>
</section>

</body>
