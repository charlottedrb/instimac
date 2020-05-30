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
    <div id="affichage"></div>
    <div class="modal" id="processingModal-embed">
        <span class="closeModal" onkeypress="if(event.which == 27){ exit(document.getElementById('processingModal-embed')); }"
        onclick="exit();">quitter &times;</span>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/WbCuYdQ6sQw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen id="processingModal-video" class="modal-content_embed"></iframe>
    </div>

    <?php include('includes/scripts.php'); ?>
</body>