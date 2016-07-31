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
define('MODEL_PATH', APP_PATH.DIRECTORY_SEPARATOR.'model');
define('CONTROLLER_PATH', APP_PATH.DIRECTORY_SEPARATOR.'controller');
define('VIEW_PATH', APP_PATH.DIRECTORY_SEPARATOR.'view');

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