<?php
//Charge les classes required by "use"
spl_autoload_register(function ($className) {
    $file = dirname(__DIR__) . '\\src\\' . $className . '.php';
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
    if (file_exists($file)) include $file;
    else echo 'Autoload: Error when loading class'.PHP_EOL."<br>";
});

/*
// one folder back
$root = __DIR__ . '\\..\\';
echo $root."<br>";
$root = $_SERVER['DOCUMENT_ROOT'];
echo $root."<br>";
$root = dirname(__DIR__);
echo $root."<br>";
*/