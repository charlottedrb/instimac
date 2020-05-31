<<<<<<< HEAD
<?php
include 'src/includes-user.php';

if (empty($_SESSION['session']) || $_SESSION['session']->check() == FALSE || $_SESSION['user']->isGranted(['ADMIN', 'MODERATOR', 'USER']) == FALSE) {
    header('Location: login.php');
    exit;
}

?>

<?php echo $speaker->generate(); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instimac</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <?php include('includes/styles.php'); ?>
</head>
<body>
    <div class="container-index">
        <div class="sidebar">
            <div class="mois-selection">
                <ul class="menu" id="menu">
                    <li class="active mois-name"><a href="#" id="decembre19">décembre 2019</a></li>
                    <li class="mois-name"><a href="#" id="janvier20">janvier 2020</a></li>
                    <li class="mois-name"><a href="#" id="fevrier20">février 2020</a></li>
                    <li class="mois-name"><a href="#" id="mars20">mars 2020</a></li>
                </ul>
                <div id="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg>
                </div>
            </div>
        </div>
        <div class="fil">
            <div class="event">
                <div class="container-event">
                   <h2 class="event-name"><a href="test_groupe.php">JEUDIMAC</a></h2>
                    <div class="publications">
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="event-date">
                    06
                </div>
            </div>
            <div class="event">
                <div class="container-event">
                    <h2 class="event-name"><a href="test_groupe.php">GALA IMAC</a></h2>
                    <div class="publications">
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="test_publication.php">
                            <div class="publication">
                                <div class="publication-image">
                                    <img src="img/taiga.jpg" alt="">
                                </div>
                                <div class="user">
                                    <div class="user-name">
                                        chdrb
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="event-date">
                    12
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/scripts.php'); ?>
</body>
</html>
