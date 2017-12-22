<?php

class Aplicaciones extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $appID;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $nombre;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $descripcion;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $url;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $soporte;

    /**
     *
     * @var string
     * @Column(type="string", length=12, nullable=false)
     */
    public $ultimoUsuario;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $fechaCreacion;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("carrillo_dbaurora");
        $this->setSource("aplicaciones");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'aplicaciones';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Aplicaciones[]|Aplicaciones|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Aplicaciones|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
