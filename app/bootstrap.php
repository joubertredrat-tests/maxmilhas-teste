<?php
/**
 * Inicia a aplicação, definindo variáveis, parametros e chamadas
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

error_reporting(E_ALL);
ini_set('display_errors', true);

define('APP_PATH', __DIR__);
define('CONFIG_PATH', APP_PATH.DIRECTORY_SEPARATOR.'conf');
define('MODEL_PATH', APP_PATH.DIRECTORY_SEPARATOR.'model');
define('CONTROLLER_PATH', APP_PATH.DIRECTORY_SEPARATOR.'controller');
define('VIEW_PATH', APP_PATH.DIRECTORY_SEPARATOR.'view');
define('SCHEMA_PATH', APP_PATH.DIRECTORY_SEPARATOR.'schema');
define('LOG_PATH', APP_PATH.DIRECTORY_SEPARATOR.'logs');
define('STORE_PATH', APP_PATH.DIRECTORY_SEPARATOR.'store');

/*
 * Chama o config da aplicação
 */
require(APP_PATH.DIRECTORY_SEPARATOR.'config.php');

\App\Config::loadEnv();

/*
 * Chama o config da aplicação
 */
require(APP_PATH.DIRECTORY_SEPARATOR.'database.php');

/*
 * Chama o autoloder da aplicação
 */
require(APP_PATH.DIRECTORY_SEPARATOR.'autoload.php');

/*
 * Chama o router da aplicação
 */
require(APP_PATH.DIRECTORY_SEPARATOR.'router.php');

/*
 * Chama o router da aplicação
 */
require(APP_PATH.DIRECTORY_SEPARATOR.'view.php');
