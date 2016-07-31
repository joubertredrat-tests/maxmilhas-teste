<?php
/**
 * Autoloader da aplicação, responsável por requisitar
 * os arquivos correspondentes a cada classe na Model
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require(__DIR__.'/vendor/autoload.php');
}

spl_autoload_register(function ($class) {
    if (strpos($class, __NAMESPACE__.'\\') === 0) {
        $name = substr($class, strlen(__NAMESPACE__) + 1);
        require(MODEL_PATH.DIRECTORY_SEPARATOR.$name.'.php');
    }
});
