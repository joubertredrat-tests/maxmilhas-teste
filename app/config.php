<?php
/**
 * Carrega as configs para funcionamento da aplicação
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Config
{
    /**
     * Carrega as variáveis de ambiente
     *
     * @return void
     */
    public static function loadEnv()
    {
        $envfile = file(CONFIG_PATH.DIRECTORY_SEPARATOR.'.env');

        foreach ($envfile as $line) {
            putenv(trim($line));
        }
    }

    /**
     * Requisita o valor de uma variável de amviente
     *
     * @param string $arg
     * @return mixed
     */
    public static function getValue($var)
    {
        return getenv($var);
    }
}