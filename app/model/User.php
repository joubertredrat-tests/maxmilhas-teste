<?php
/**
 * Classe de manipulação dos usuários do app
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

use \PDO;

class User
{
    /**
     * User's identificator.
     *
     * @var integer
     */
    private $id;

    /**
     * User's username.
     *
     * @var string
     */
    private $username;

    /**
     * User's password.
     *
     * @var string
     */
    private $password;

    /**
     * User's creation date.
     *
     * @var datetime
     */
    private $create_date;

    /**
     * User's update date.
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
     * @param $id integer id User's identificator.
     * @return void
     */
    public function __construct($id = null)
    {
         if (filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $dbh = \App\Database::getInstance();
                $stmt = $dbh->prepare('SELECT * FROM users WHERE id = :id');
                $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) != 1) {
                    throw new \App\Exception(
                        'Ocorreu um erro durante a consulta na tabela "users", a chave identificadora é inválida: "'.$id.'".'
                    );
                }

                $this->id = $id;
                $this->username = $rows[0]['username'];
                $this->password = $rows[0]['password'];
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
            case 'username':
                if (preg_match('/[^A-Za-z0-9]/i', $value)) {
                    throw new \App\Exception(
                        'Atributo '.$attribute.' da classe '.__CLASS__.' só aceita caracteres alphanuméricos'
                    ); 
                }

                $this->username = $value;
                break;
            case 'password':
                if (!\App\Functions::validateType($value, 'string')) {
                    throw new \App\Exception(
                        \App\Exception::getMsgClassWrongTypeAttr(__CLASS__, $attribute, $value, 'string')
                    );
                }

                $this->password = self::passwordHash($value, PASSWORD_DEFAULT);
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
            case 'id':
            case 'username':
            case 'password':
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
        $this->new ? $this->insert() : $this->update();
    }

    /**
     * Insere user no banco de dados
     *
     * @return void
     */
    private function insert()
    {
        $query = 'INSERT INTO users (username, password) VALUES (:username, :password)';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':username', $this->username, \PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, \PDO::PARAM_STR);
        $stmt->execute();

        $this->id = $dbh->lastInsertId();
    }

    /**
     * Atualiza user no banco de dados
     *
     * @return void
     */
    private function update()
    {
        $query = 'UPDATE users SET password = :password WHERE id = :id';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':password', $this->password, \PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Requisita todas os usuários
     *
     * @return bool $object Define se será array de objetos 
     * @return array
     */
    public static function getAll($object = false)
    {
        $query = 'SELECT '.($object ? 'id' : '*').' FROM users';

        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare($query);
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
     * Gera uma senha em formato hash a partir de sennha em texto plano.
     *
     * @param string $password Senha em texto plano
     * @return string
     */
    public static function passwordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifica se a senha plana corresponde a senha com hash
     *
     * @param string $password_plan Senha em texto plano
     * @param string $password_hash Senha em formato hash
     * @return bool
     */
    public static function passwordVerify($password_plan, $password_hash)
    {
        return password_verify($password_plan, $password_hash);
    }

    /**
     * Autentica um usuário com um username e senha informados
     *
     * @param string $username usuário
     * @param string $password senha
     * @return Object|bool
     */
    public static function auth($username, $password)
    {
        $dbh = \App\Database::getInstance();
        $stmt = $dbh->prepare('SELECT id, password FROM users WHERE username = :username');
        $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) != 1) {
            return false;
        }

        if (self::passwordVerify($password, $rows[0]['password'])) {
            return new self($rows[0]['id']);
        } else {
            return false;
        }       
    }
}
