<?php
/**
 * Classe de chamada das views da aplicação.
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class View
{
	/**
	 * Método principal de chamada das views, responsável pela chamada da view
	 * e os parametros enviados pelo controller.
	 *
	 * @param string $view Nome da view a ser chamada
	 * @param array $args Dados a ser enviados para a view.
	 * @return void
	 */
    public static function call($view, Array $args = [])
    {
    	if ($args) {
    		extract($args);
    	}
        $view = str_replace('\\', '/', $view);
        require(VIEW_PATH.DIRECTORY_SEPARATOR.$view.'.php');
    }
}
