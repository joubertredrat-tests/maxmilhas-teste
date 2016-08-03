<?php
/**
 * Controller do installer do app
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Install
{
    public function __construct()
    {

    }

    /**
     * Index da instalação do app
     *
     * @return void
     */
    public function index()
    {
        if(\App\Config::envExist()) {
            header('Location: '.\App\Functions::getAppUrl());
        }

        $ok = null;

        if ($_POST) {
            $db_name = $_POST['db_name'];
            $db_user = $_POST['db_user'];
            $db_password = $_POST['db_password'];
            $db_host = $_POST['db_host'];
            $db_port = $_POST['db_port'];

            $ok = \App\Database::testConnection($db_name, $db_user, $db_password, $db_host, $db_port);

            if ($ok) {
                $evn[] = 'DB_HOST='.$db_host;
                $evn[] = 'DB_PORT='.$db_port;
                $evn[] = 'DB_NAME='.$db_name;
                $evn[] = 'DB_USER='.$db_user;
                $evn[] = 'DB_PASS='.$db_password;

                \App\Config::writeEnv($evn);
                \App\Config::loadEnv();

                $dbh = \App\Database::getInstance();
                $dbh->exec(file_get_contents(SCHEMA_PATH.DIRECTORY_SEPARATOR.'001.sql'));

                header('Location: '.\App\Functions::getAppUrl('install/finish'));
            }
        }

        \App\View::call('install\form', ['ok' => $ok]);
    }

    /**
     * Informações finais da instalação
     *
     * @return void
     */
    public function finish()
    {
        \App\View::call('install\finish');
    }
}
