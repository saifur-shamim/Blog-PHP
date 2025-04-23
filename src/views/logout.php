<?php
require __DIR__ . '/../../vendor/autoload.php';
use App\Controller\AuthController;

$logoutModel = new AuthController();
$logoutModel->logout();

?>
