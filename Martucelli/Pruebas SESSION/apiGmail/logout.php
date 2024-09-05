<?php
session_start();
$_SESSION['verificado'] = false;
session_destroy();
header('Location: ../index.html');
exit();
