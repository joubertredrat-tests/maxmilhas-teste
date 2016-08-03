<?php
/**
 * Classe de manipulação de sessão de usuário do app
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Session
{
    /**
     * Prefixo de sessão
     */
    const SESSION_PREFIX = 'app_';

    /**
     * Construtor
     *
     * @return void
     */
    private function __construct()
    {

    }

    /**
     * Requisita um usuário presente na sessão
     *
     * @return \App\User|nool
     */
    public function getUserSession()
    {
        return $_SESSION[self::SESSION_PREFIX.'user'] ? new \App\User($_SESSION[self::SESSION_PREFIX.'user']) : false;
    }

    /**
     * Inicia a sessão de um usuário autenticado
     *
     * @return void
     */
    public static function startSession(\App\User $User)
    {
        session_start();

        $_SESSION[self::SESSION_PREFIX.'auth'] = true;
        $_SESSION[self::SESSION_PREFIX.'user'] = $User->id;

        session_write_close();
    }

    /**
     * Finaliza a sessão de um usuário
     *
     * @return void
     */
    public static function finishSession()
    {
        unset($_SESSION[self::SESSION_PREFIX.'auth'], $_SESSION[self::SESSION_PREFIX.'user']);
    }

    /**
     * Verifica se existe uma sessão ativa para um usuário
     *
     * @return bool
     */
    public static function userAuth()
    {
        session_start();
        $return = isset($_SESSION[self::SESSION_PREFIX.'auth']) && $_SESSION[self::SESSION_PREFIX.'auth'];
        session_write_close();

        return $return;
    }
}