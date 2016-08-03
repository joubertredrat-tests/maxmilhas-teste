<?php
/**
 * Controller das galerias do app
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Index
{
    public function __construct()
    {
        if(!\App\Config::envExist()) {
            echo 'App not installed, please run install on '.\App\Functions::getAppUrl('install');
            exit();
        }
    }

    /**
     * Exibição de galerias
     *
     * @return void
     */
    public function index()
    {
        $galleries = \App\Gallery::getAll();

        \App\View::call('index', ['galleries' => $galleries]);
    }
}
