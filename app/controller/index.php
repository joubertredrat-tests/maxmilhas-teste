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
