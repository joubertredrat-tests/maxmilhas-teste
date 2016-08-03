<?php
/**
 * Classe de manipulação das galerias do app, bem como as pastas relacionadas
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

use \PDO;

class PhotoView extends Photo
{


    /**
     * Método constutor da classe responsável por popular o objeto de acordo com a
     * chave identificadora do registro informado no parametro ou a criação de um
     * objeto vazio.
     *
     * @param $id integer id Photo's identificator.
     * @return void
     */
    public function __construct($gallery_id, $position)
    {
        if (
            filter_var($gallery_id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) &&
            filter_var($position, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
        ) {
            $dbh = \App\Database::getInstance();
            $stmt = $dbh->prepare('SELECT * FROM photos WHERE galleries_id = :galleries_id AND position = :position');
            $stmt->bindValue(':galleries_id', $gallery_id, \PDO::PARAM_INT);
            $stmt->bindValue(':position', $position, \PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($rows) != 1) {
                throw new \App\Exception(
                    'Ocorreu um erro durante a consulta na tabela "photos", a chave identificadora é inválida: "'.$id.'".'
                );
            }

            parent::__construct($rows[0]['id']);
        }
    }

    public function isFirst()
    {
        return $this->position == 1;
    }

    public function isLast()
    {
        return $this->position == $this->Gallery->getTotalPhotos();
    }

    public function getPrevious()
    {
        return ($this->position - 1);
    }

    public function getNext()
    {
        return ($this->position + 1);
    }
}
