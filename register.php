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
	<title>Do and success, the key to do what you need.</title>
	<link rel="stylesheet" href="../webdesign/webpack.css">
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,500,700&display=swap" rel="stylesheet">
</head>
<body class="th-gradient centered-parent section-full">

<section>


	<div class="centered-inner form">

		<h1>Create your account, it's easy and fast</h1>
		<form action="" method="post">

            <?php echo $speaker->generate(); ?>

			<input type="email" name="email" placeholder="Email" autofocus
				   value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required id="" class="form-input">

			<input type="text" name="name" class="form-input" placeholder="My name is...oohh"
				   value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" required id="">

			<input type="text" name="surname" placeholder="My surname is...hey hey"
				   value="<?php if (isset($_POST['surname'])) echo $_POST['surname']; ?>" required id=""
				   class="form-input">

			<input type="password" name="password"
				   value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"
				   placeholder="Your fancy password..." id="" class="form-input" required>
			<input type="password" name="password_confirm"
				   value="<?php if (isset($_POST['password_confirm'])) echo $_POST['password_confirm']; ?>"
				   placeholder="Repeat your password, please..." id="" class="form-input" required>

			<label for="agree"><input type="checkbox" name="agree"
                    <?php if (isset($_POST['agree'])) echo 'checked="checked"'; ?> value="1" id="agree"
									  class="form-checkbox" required> I agree to
				doandsuccess.com conditions, It means:</label>
			<ul>
				<li>Use, process and store your data for <span class="underlined">doandsuccess.com use only</span></li>
				<li>I know our conditions can evolved at anytime and <span class="underlined">I will be informed</span>
					in
					that case
				</li>
			</ul>

			<input type="submit" value="Create my account" class="form-btn shadow-light ">
		</form>
	</div>

</section>

<script src="js/global.js"></script>

</body>
</html>