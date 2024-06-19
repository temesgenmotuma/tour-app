<?php
session_start();
session_unset();
session_destroy();


$params = session_get_cookie_params();
setcookie('PHPSESSID','', time() -3600,$params['path'],$params['domain']);

header('Location: /tour/views/login.php');
exit();

