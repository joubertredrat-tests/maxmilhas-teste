<?php
/**
 * Classe no padrão singleton para criar a conexão com o
 * banco de dados
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

use \PDO;

class Database
{
    /**
     * Conexão com o banco de dados 
     *
     * @var PDO
     */
    private static $dbh;

    /**
     * Construtor que não faz nada
     *
     * @return void
     */
    private function __construct()
    {

    }

    /**
     * Requisita uma instância do PDO.
     *
     * @return PDO
     */
    public static function getInstance()
    {
        if (!self::isInstantiated()) {
            self::setInstance(
                self::buildInstance(
                    \App\Config::getValue('DB_NAME'),
                    \App\Config::getValue('DB_USER'),
                    \App\Config::getValue('DB_PASS'),
                    \App\Config::getValue('DB_HOST'),
                    \App\Config::getValue('DB_PORT')
                )
            );
        }
        return self::$dbh;
    }

    /**
     * Define uma instancia do PDO no singleton.
     *
     * @param PDO $dbh
     * @return void
     */
    private static function setInstance(PDO $dbh)
    {
        self::$dbh = $dbh;
    }

    /**
     * Verifica se existe uma instancia do Doctrine no singleton.
     *
     * @return bool
     */
    public static function isInstantiated()
    {
        return self::$dbh instanceof PDO;
    }

    /**
     * Cria uma instância do PDO com os parametros definidos.
     *
     * @param string $bd_name Nome do banco de dados
     * @param string $bd_user Uusário do banco de dados
     * @param string $bd_pass Senha do usuário do banco de dados
     * @param string $bd_host Host do banco de dados
     * @param int $bd_port Porta do host do banco de dados
     * @return PDO
     */
    private static function buildInstance($bd_name, $bd_user, $bd_pass, $bd_host = 'localhost', $bd_port = 3306)
    {
        try {
            $dbh = new PDO('mysql:host='.$bd_host.'; port '.$bd_port.';dbname='.$bd_name, $bd_user, $bd_pass);  
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("SET CHARACTER SET utf8");
            $dbh->exec("SET NAMES utf8");

            return $dbh;
        } catch (PDOException $e) {
            exit("Connection to database failed: ".$e->getMessage());
        }
    }
}
