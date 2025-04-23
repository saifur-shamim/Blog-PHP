<?php

require_once 'vendor/autoload.php';

use App\Migration;

$migration = new Migration();
$migration->runMigrations();
