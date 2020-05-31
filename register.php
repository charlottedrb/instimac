<?php

require 'src/includes-user.php';

if (!empty($_SESSION['session']) && $_SESSION['session']->check() && $_SESSION['user']->isGranted(['ADMIN', 'MODERATOR', 'REGISTERED'])) {
    header('Location: dashboard.php');
    exit;
}

use Sanitize\Sanitize;
use User\User;
use User\Speaker;
use Session\Session;

if (Sanitize::checkIssetFields($_POST, ['name', 'surname', 'email', 'password', 'password_confirm', 'agree'])) {

    if (Sanitize::checkEmptyFields($_POST, ['name', 'surname', 'email', 'password', 'password_confirm'])) {

        if (Sanitize::checkEmptyFields($_POST, ['agree'])) {

            $secured = Sanitize::arrayFields($_POST, ['name', 'surname', 'email', 'password', 'password_confirm']);

            if ($secured['password'] == $secured['password_confirm']) {

                if (User::isValidPassword($secured['password'])) {

                    $user = new User($db);
                    $user->name = $secured['name'];
                    $user->surname = $secured['surname'];
                    $user->email = $secured['email'];
                    $user->password = $secured['password'];
                    $user->enabled = 1;

                    if ($user->create(['USER'])) {

                        $_SESSION['user'] = $user;
                        $_SESSION['session'] = new Session();
                        header('Location: dashboard.php');
                        exit;

                    } else $speaker->add('error-registration');

                } else $speaker->add('error-password-valid');

            } else $speaker->add('error-password-match');

        } else $speaker->add('error-agreement');

    } else $speaker->add('error-form-fill');
}
?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="css/theme.css">
	<link rel="stylesheet" href="css/login.css">
</head>
<body id="login">
<div class="form-container">
	<h2>Avec un compte c'est mieux</h2>
	<form action="login.php" method="post" style="max-width: 800px;">

        <?php echo $speaker->generate(); ?>

		<input type="email" name="email" placeholder="Email" autofocus
			   value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required>

		<input type="text" name="name" class="form-input" placeholder="My name is...oohh"
			   value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" required>

		<input type="text" name="surname" placeholder="My surname is...hey hey"
			   value="<?php if (isset($_POST['surname'])) echo $_POST['surname']; ?>" required>

		<input type="password" name="password"
			   value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"
			   placeholder="Your fancy password..." id="" required>
		<input type="password" name="password_confirm"
			   value="<?php if (isset($_POST['password_confirm'])) echo $_POST['password_confirm']; ?>"
			   placeholder="Repeat your password, please..." id="" class="form-input" required>

		<label for="agree"><input type="checkbox" name="agree"
                <?php if (isset($_POST['agree'])) echo 'checked="checked"'; ?> value="1" required
								  style="display: inline-block; width: auto;" id="agree"> J'accepte les conditions sans
			les lire</label>

		<input type="submit" value="Créer mon compte" class="btn">
	</form>

	<p>Vous avez un compte ? <a href="login.php">C'est par ici.</a> /
		<a href="index.php">Accueil</a>
	</p>
</div>
</body>
</html>