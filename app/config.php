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
        if(self::envExist()) {
            $envfile = file(CONFIG_PATH.DIRECTORY_SEPARATOR.'.env');

            foreach ($envfile as $line) {
                putenv(trim($line));
            }
        }
    }

    /**
     * Verifica se existe variáveis de ambiente
     *
     * @return bool
     */
    public static function envExist()
    {
        return file_exists(CONFIG_PATH.DIRECTORY_SEPARATOR.'.env');
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

    /**
     * Escreve um novo arquivo de variáveis de ambiente
     *
     * @param array $values
     * @return void
     */
    public static function writeEnv(Array $values)
    {
        if(!self::envExist()) {
            file_put_contents(CONFIG_PATH.DIRECTORY_SEPARATOR.'.env', implode(PHP_EOL, $values));
        }
    }
}