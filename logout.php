<?php
require_once 'src/includes.php';

use Session\Session;

session_destroy();

unset($_SESSION['user']);
unset($_SESSION['session']);
unset($_COOKIE[Session::getCookieName()]);

header('Location: login.php?speaker=success-disconnect');