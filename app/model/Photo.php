<?php
/**
 * Classe de manipulação das galerias do app, bem como as pastas relacionadas
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

use \PDO;

class Photo
{
    /**
     * Photo's identificator.
     *
     * @var integer
     */
    private $id;

    /**
     * Gallery's identificator.
     *
     * @var App\Gallery
     */
    private $Gallery;

    /**
     * Photo's filename.
     *
     * @var string
     */
    private $filename;

    /**
     * Photo's original filename from upload.
     *
     * @var string
     */
    private $original_filename;

    /**
     * Photo position in the gallery.
     *
     * @var integer
     */
    private $position;

    /**
     * Photo's creation date.
     *
     * @var datetime
     */
    private $create_date;

    /**
     * Photo's update date.
     *
     * @var datetime
     */
    private $update_date;

    /**
     * Defines object as new
     *
     * @var bool
     */
    private $new = true;

    /**
     * Método constutor da classe responsável por popular o objeto de acordo com a
     * chave identificadora do registro informado no parametro ou a criação de um
     * objeto vazio.
     *
     * @param $id integer id Photo's identificator.
     * @return void
     */
    public function __construct($id = null)
    {
         if (filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $dbh = \App\Database::getInstance();
                $stmt = $dbh->prepare('SELECT * FROM photos WHERE id = :id');
                $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) != 1) {
                    throw new \App\Exception(
                        'Ocorreu um erro durante a consulta na tabela "photos", a chave identificadora é inválida: "'.$id.'".'
                    );
                }

                $this->id = $id;
                $this->Gallery = new \App\Gallery($rows[0]['galleries_id']);
                $this->filename = $rows[0]['filename'];
                $this->original_filename = $rows[0]['original_filename'];
                $this->position = $rows[0]['position'];
                $this->create_date = $rows[0]['create_date'];
                $this->update_date = $rows[0]['update_date'];
                $this->new = false;
        } elseif (is_null($id)) {
            // Nada a fazer, é um novo objeto vazio
        } else {
            throw new \App\Exception(
                'Tentativa de injection na classe '.__CLASS__.', variável $id recebeu o valor '.$id.' do tipo '.gettype($id)
            );
        }
    }

    /**
     * Atribui o dado ao objeto de acordo com o atributo informado ou dispara exception
     * caso atributo não exista.
     *
     * @param string $attribute Nome do atributo que irá receber o dado.
     * @param mixed $value Dado a ser atribuido ao atributo.
     * @return void
     */
    public function __set($attribute, $value)
    {
        switch ($attribute) {
            case 'original_filename':
                if (!\App\Functions::validateType($value, 'string')) {
                    throw new \App\Exception(
                        \App\Exception::getMsgClassWrongTypeAttr(__CLASS__, $attribute, $value, 'string')
                    );
                }

                $this->original_filename = $value;
                break;            
            default:
                throw new \App\Exception('Atributo '.$attribute.' desconhecido da classe '.__CLASS__);
                break;
        }
    }

    /**
     * Informa o dado do atributo solicitado ou dispara exception caso atributo não
     * exista.
     *
     * @param string $attribute Nome do atributo que deseja obter seu respectivo dado.
     * @return mixed Valor do atributo no seu tipo original.
     */
    public function __get($attribute)
    {
        switch ($attribute) {
            case 'id':
            case 'Gallery':
            case 'filename':
            case 'original_filename':
            case 'position':
            case 'create_date':
            case 'update_date':
                return $this->$attribute;
                break;
            case 'galleries_id':
                return $this->Gallery->id;
                break;
            default:
                throw new \App\Exception('Atributo '.$attribute.' desconhecido da classe '.__CLASS__);
                break;
        }
    }

    /**
     * Define uma galeria na foto
     *
     * @param \App\Gallery $Gallery
     * @return void
     */
    public function setGallery(\App\Gallery $Gallery)
    {
       $this->Gallery = $Gallery;
    }

    /**
     * Requisita uma galeria na foto
     *
     * @return \App\Gallery|null
     */
    public function getGallery()
    {
        return $this->Gallery;
    }

    /**
     * Chamada de ação no banco de dados
     *
     * @return void
     */
    public function save()
    {
        $this->new ? $this->insert() : null;
    }

    /**
     * Insere foto no banco de dados
     *
     * @return void
     */
    private function insert()
    {
        if (!$this->galleries_id) {
            throw new \App\Exception('Galeria não definida para a foto');
        }

        $this->populateFilename();
        $this->putNewPosition();
        if(!$this->storePhoto()) {
            throw new \App\Exception('Não foi possível salvar a foto da galeria');     
        }

        $query = 'INSERT INTO photos (galleries_id, position, filename, original_filename) VALUES (:galleries_id, :position, :filename, :original_filename)';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':galleries_id', $this->Gallery->id, \PDO::PARAM_INT);
        $stmt->bindValue(':position', $this->position, \PDO::PARAM_INT);
        $stmt->bindValue(':filename', $this->filename, \PDO::PARAM_STR);
        $stmt->bindValue(':original_filename', $this->original_filename, \PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $x) {
            echo $x->getMessage();
        }

        $this->id = $dbh->lastInsertId();

        $this->storePhoto();
    }

    /**
     * Remove foto do banco de dados
     *
     * @return void
     */
    public function delete()
    {
        $query = 'DELETE FROM photos WHERE id = :id';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();

        $this->reorder();
        $this->id = null;
    }

    /**
     * Reordena as fotos
     *
     * @return void
     */
    private function reorder()
    {
        $query = 'UPDATE photos SET position = position -1 WHERE position > :position';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':position', $this->position, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Sobe uma ordem na posição da foto
     *
     * @return void
     */
    public function upPosition()
    {
        $new_position = ($this->position - 1);

        $dbh = \App\Database::getInstance();

        $query = 'UPDATE photos SET position = 0 WHERE position = :position';
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':position', $this->position, \PDO::PARAM_INT);
        $stmt->execute();

        $query = 'UPDATE photos SET position = :position WHERE position = :new_position';
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':position', $this->position, \PDO::PARAM_INT);
        $stmt->bindValue(':new_position', $new_position, \PDO::PARAM_INT);
        $stmt->execute();

        $query = 'UPDATE photos SET position = :new_position WHERE position = 0';
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':new_position', $new_position, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Desce uma ordem na posição da foto
     *
     * @return void
     */
    public function downPosition()
    {
        $dbh = \App\Database::getInstance();
        $new_position = $this->position + 1;

        $query = 'UPDATE photos SET position = 0 WHERE position = :position';
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':position', $this->position, \PDO::PARAM_INT);
        $stmt->execute();

        $query = 'UPDATE photos SET position = :position WHERE position = :new_position';
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':position', $this->position, \PDO::PARAM_INT);
        $stmt->bindValue(':new_position', $new_position, \PDO::PARAM_INT);
        $stmt->execute();

        $query = 'UPDATE photos SET position = :new_position WHERE position = 0';
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':new_position', $new_position, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Requisita todas as galerias
     *
     * @return bool $object Define se será array de objetos 
     * @return array
     */
    public static function getAll($gallery_id, $object = false)
    {
        $query = 'SELECT '.($object ? 'id' : '*').' FROM photos WHERE galleries_id = :galleries_id ORDER BY position ASC';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':galleries_id', $gallery_id, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $return = [];

        foreach ($rows as $row) {
            if ($object) {
                $return[] = new self($row['id']);
            } else {
                $return[] = $row;
            }
        }

        return $return;
    }

    /**
     * Define um novo nome de foto para o arquivo armazenado
     *
     * @return string
     */
    private function populateFilename()
    {
        $this->original_filename = $_FILES['original_filename']['name'];
        $fileinfo = pathinfo($this->original_filename);
        $this->filename = time().'.'.$fileinfo['extension'];
    }

    /**
     * Requisita nova ordem para uma nova foto
     *
     * @return int
     */
    private function putNewPosition()
    {
        $this->position = ($this->Gallery->getTotalPhotos() + 1);
    }

    /**
     * Guarda uma foto na pasta correspondente
     *
     * @return bool
     */    
    private function storePhoto()
    {
        return move_uploaded_file(
            $_FILES['original_filename']['tmp_name'],
            $this->Gallery->getFolderPath().'/'.$this->filename
        );
    }

    /**
     * Exibe a imagem
     *
     * @return void
     */
    public function display()
    {
        header('Content-type: '.mime_content_type($this->Gallery->getFolderPath().'/'.$this->filename));
        readfile($this->Gallery->getFolderPath().'/'.$this->filename);        
    }
}
