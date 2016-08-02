<?php
/**
 * Classe de manipulação das galerias do app, bem como as pastas relacionadas
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

use \PDO;

class Gallery
{
    /**
     * Gallery's identificator.
     *
     * @var integer
     */
    private $id;

    /**
     * Gallery's name.
     *
     * @var string
     */
    private $name;

    /**
     * Gallery's creation date.
     *
     * @var datetime
     */
    private $create_date;

    /**
     * Gallery's update date.
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
     * Prefixo da pasta de galeria
     */
    const FOLDER_PREFIX = 'gallery_';

    /**
     * Método constutor da classe responsável por popular o objeto de acordo com a
     * chave identificadora do registro informado no parametro ou a criação de um
     * objeto vazio.
     *
     * @param integer id Gallery's identificator.
     * @return void
     */
    public function __construct($id = null)
    {
         if (filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $dbh = \App\Database::getInstance();
                $stmt = $dbh->prepare('SELECT * FROM galleries WHERE id = :id');
                $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) != 1) {
                    throw new \App\Exception(
                        'Ocorreu um erro durante a consulta na tabela "galleries", a chave identificadora é inválida: "'.$id.'".'
                    );
                }

                $this->id = $id;
                $this->name = $rows[0]['name'];
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
            case 'name':
                if (!\App\Functions::validateType($value, 'string')) {
                    throw new \App\Exception(
                        \App\Exception::getMsgClassWrongTypeAttr(__CLASS__, $attribute, $value, 'string')
                    );
                }

                $this->name = $value;
                break;            
            default:
                throw new \App\Exception('Atributo '.$attribute.' desconhecido da classe '.__CLASS__);
                break;
        }
    }

    /**
     * Informa o dado do atributo solicitado ou dispara exceção caso atributo não
     * exista.
     *
     * @param string $attribute Nome do atributo que deseja obter seu respectivo dado.
     * @return mixed Valor do atributo no seu tipo original.
     */
    public function __get($attribute)
    {
        switch ($attribute) {
            case 'name':
            case 'create_date':
            case 'update_date':
                return $this->$attribute;
                break;
            default:
                throw new \App\Exception('Atributo '.$attribute.' desconhecido da classe '.__CLASS__);
                break;
        }
    }

    /**
     * Chamada de ação no banco de dados
     *
     * @return void
     */
    public function save()
    {
        $this->new ? $this->add() : $this->update();
    }

    /**
     * Insere user no banco de dados
     *
     * @return void
     */
    private function insert()
    {
        $query = 'INSERT INTO galleries (name) VALUES (:name)';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':name', $this->name, \PDO::PARAM_STR);
        $stmt->execute();

        $this->id = $dbh->lastInsertId();

        $this->createFolder();
    }

    /**
     * Atualiza user no banco de dados
     *
     * @return void
     */
    private function update()
    {
        $query = 'UPDATE galleries SET name = :name WHERE id = :id';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':name', $this->name, \PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Atualiza user no banco de dados
     *
     * @return void
     */
    public function delete()
    {
        $query = 'DELETE FROM galleries WHERE id = :id';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();

        $this->removeFolder();
        $this->id = null;
    }

    /**
     * Requisita o endereço absoluto de uma galeria
     *
     * @return string
     */
    public function getFolderPath()
    {
        if (!$this->id) {
            throw new \App\Exception('Galeria não existe');
        }
        return STORE_PATH.DIRECTORY_SEPARATOR.FOLDER_PREFIX.$this->id;
    }

    /**
     * Cria a pasta da galeria
     *
     * @return void
     */
    private function createFolder()
    {
        mkdir($this->getFolderPath());
    }

    /**
     * Remove a pasta da galeria e todos seus arquivos
     *
     * @return void
     */
    private function removeFolder()
    {
        array_map('unlink', glob($this->getFolderPath().'/*.*'));
        rmdir($this->getFolderPath());
    }
}
