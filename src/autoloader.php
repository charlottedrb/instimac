<?php
/*
// one folder back
$root = __DIR__ . '\\..\\';
echo $root."<br>";
$root = $_SERVER['DOCUMENT_ROOT'];
echo $root."<br>";
$root = dirname(__DIR__);
echo $root."<br>";
*/

spl_autoload_register(function ($className) {

    $file = dirname(__DIR__) . '\\src\\' . $className . '.php';
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
    //echo 'Directory: '.$file.';<br>';
    if (file_exists($file)) {
        //echo 'OK for ' . $className . ' in ' . $file . ";<br>".PHP_EOL;
        include $file;
    } else {
        //echo '/!\ Not Founded ' . $className . ' in ' . $file . ';<br>'.PHP_EOL;
    }
    //echo '----------------<br>';
});