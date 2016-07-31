<?php

define('PUBLIC_PATH', __DIR__);

require(PUBLIC_PATH.'/../app/bootstrap.php');

\App\Router::run();