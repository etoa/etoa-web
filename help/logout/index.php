<?php
require __DIR__ . '/../../vendor/autoload.php';

session_start();

header("WWW-Authenticate: Basic realm=\"EtoA.ch Hilfe\"");
header("HTTP/1.0 401 Unauthorized");
unset($_SESSION['etoahelp']);

if (isset($_SERVER["HTTP_REFERER"]))
    forward($_SERVER["HTTP_REFERER"]);
else
    forwardInternal('..');
