<?php
/**
 * Classe de exception do aplicativo, responsável por escrever o log de erros
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Functions
{
    /**
     * Valida os tipos de dados.
     *
     * @param mixed $value Valor a ser validado.
     * @param string $type Tipo de dado a ser validado.
     * @return bool Retorna true caso seja um tipo válido ou false caso contra.
     */
    public static function validateType($value, $type)
    {
        switch ($type)
        {
            case 'string':
                $return = is_string($value);
                break;
            case 'integer':
                $return = filter_var($value, FILTER_VALIDATE_INT) !== false;
                break;
            case 'float':
                $return = filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
                break;
            case 'boolean':
                $return = is_bool($value);
                break;
            case 'datetime':
                if (self::validateType(substr($value, 0, 4), 'datetime_year')) {
                    \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                    $validate = DateTime::getLastErrors();
                    $return = ($validate['warning_count'] == 0 && $validate['error_count'] == 0);
                }
                else
                    $return = false;
                break;
            case 'datetime_year':
                if ($value == '0000')
                    $return = true;
                else
                    $return = filter_var(
                        (int) $value, 
                        FILTER_VALIDATE_INT, 
                        ['options' => ['min_range' => 1000, 'max_range' => 9999]]
                    ) !== false;
                break;
            case 'datetime_date':
                if ($value === '0000-00-00')
                    $return = true;
                else
                    $return = self::validateType($value.' '.date('H:i:s'), 'datetime');
                break;
            case 'datetime_time':
                if ($value === '00:00:00')
                    $return = true;
                else
                    $return = self::validateType(date('Y-m-d').' '.$value, 'datetime');
                break;
            case 'datetime_timestamp':
                $return = filter_var(
                    (int) $value, 
                    FILTER_VALIDATE_INT, 
                    ['options' => ['min_range' => -2147483647, 'max_range' => 2147483647]]
                ) !== false;
                break;
            default:
                $return = false;
                break;
        }
        return $return;
    }

    /**
     * Requisita a url da aplicação
     *
     * @return string
     */
    public static function getAppUrl($uri = null)
    {
        $url = [];
        $url[] = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $url[] = 's';
        }
        $url[] = '://'.$_SERVER['HTTP_HOST'].'/';
        if ($uri) {
            $url[] = $uri;
        }

        return implode('', $url);
    }
}
