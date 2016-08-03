<?php
/**
 * Frontend do app
 *
 * @author Joubert <eu@redrat.com.br>
 */

define('APP_PUBLIC_PATH', __DIR__);

require(APP_PUBLIC_PATH.'/../app/bootstrap.php');

\App\Router::run();
