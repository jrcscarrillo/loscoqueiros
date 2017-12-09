<?php

use Phalcon\Mvc\Model as tabla;
use Phalcon\Validation as validar;

class Users extends tabla {


    public $id;

    public $username;
    
    public $tipo;
    
    public $tipoId;
    
    public $numeroId;

    public $password;

    public $name;

    public $email;

    public $createdAt;

    public $active;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation() {
        $validator = new validar();

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize() {
        $this->setSchema("carrillo_dbaurora");
        $this->hasMany('id', 'Yourcode', 'userId', ['alias' => 'Yourcode']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource() {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users
     */
    public static function find($parameters = null) {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
     */
    public static function findFirst($parameters = null) {
        return parent::findFirst($parameters);
    }

    /**
     * 
     * @param type $parameters
     * @return grupo
     * use Phalcon\Mvc\Model as tabla;
     * use Phalcon\Validation as validar;
     * use \Phalcon\Mvc\Model\ResultsetSimple as grupo;
     * $parameters is an array with name, username, and email
     */
    public static function findEqualUsers($parameters) {
        $sql = "SELECT * FROM Users WHERE name = :vName OR username = :vUsername OR email = :vEmail";
        $di = \Phalcon\DI::getDefault();
        $db = $di['db'];
        $data = $db->query($sql);
        $data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        $results = $data->fetchAll();
        return $results;
    }

}
