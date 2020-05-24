<?php
include 'src/includes-user.php';

if (empty($_SESSION['session']) || !$_SESSION['session']->check() || !$_SESSION['user']->isGranted(['ADMIN', 'MODERATOR', 'USER'])) {

    header('Location: login.php');
    exit;
}

?>

<?php echo $speaker->generate(); ?>