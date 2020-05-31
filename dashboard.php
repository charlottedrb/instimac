<?php
include 'src/includes-user.php';

if (empty($_SESSION['session']) || $_SESSION['session']->check() == FALSE || $_SESSION['user']->isGranted(['ADMIN', 'MODERATOR', 'USER']) == FALSE) {
    header('Location: login.php');
    exit;
}

?><!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instimac</title>
	<link rel="stylesheet" href="css/theme.css">
	<link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<div id="affichage"></div>

<script src="./js/ajax.js"></script>
<script src="./js/modales.js"></script>
<script src="./js/generate.js"></script>
</body>
</html>
