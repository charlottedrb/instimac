<?php

include "src/autoloader.php";

use Sanitize\Sanitize;

$str = 'Hé <3 Tu fais quoï <3 <b>Prout</b>? là?';

?><!DOCTYPE>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>

<p>
    <?php

    echo $str;
    echo '<br>';
    echo Sanitize::display(Sanitize::sanitize($str));
    echo '<br>';
    echo 'Le mercredi c&#039;est ravioli';
    ?>

</p>

</body>
</html>
