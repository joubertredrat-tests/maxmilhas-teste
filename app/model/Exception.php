<?php
/**
 * Classe de exception do aplicativo, responsável por escrever o log de erros
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Exception extends \Exception
{
    /**
     * Construtor
     *
     * @param string $msg Mensagem de erro
     * @return void
     */
    public function __construct($msg)
    {
        file_put_contents(
            LOG_PATH.DIRECTORY_SEPARATOR.'error.log',
            date('Y-m-d H:i:s').' - '.trim($msg).PHP_EOL, 
            FILE_APPEND
        );
        parent::__construct($msg);
    }

    /**
     * Construtor
     *
     * @param string $msg Mensagem de erro
     * @return void
     */
    public static function getMsgClassWrongTypeAttr($class, $attribute, $value, $type)
    {
        $msg = [];
        $msg[] = 'O atributo '.$attribute.' da classe '.$class.' ';
        $msg[] = 'deve receber um '.$type.', mas foi informado um valor inválido ';
        $msg[] = $value.' do tipo '.gettype($value);
        return implode('', $msg);
    }
}
