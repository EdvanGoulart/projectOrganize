<?php

declare(strict_types=1);

require '../vendor/autoload.php';

session_start();

date_default_timezone_set(config('app.timezone'));

require base_path('/config/routes.php');
