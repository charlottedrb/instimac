<?php
include 'src/includes-user.php';

if (empty($_SESSION['session']) || $_SESSION['session']->check() == FALSE || $_SESSION['user']->isGranted(['ADMIN', 'MODERATOR', 'USER']) == FALSE) {
    header('Location: login.php');
    exit;
}

?>

<?php echo $speaker->generate(); ?>

dashboartd
