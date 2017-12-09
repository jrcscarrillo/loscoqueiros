<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class ContribuyenteForm extends Form {

    public function initialize($entity = null, $options = array()) {
        if (!isset($options['edit'])) {
            $element = new Numeric("Id");
            $this->add($element->setLabel("Registro Unico Contribuyente"));
        } else {
            $this->add(new Hidden("Id"));
        }

        $Ruc = new Numeric("Ruc");
        $Ruc->setLabel("RUC");
        $Ruc->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar el numero del RUC del Contribuyente'
              ))
        ));
        $this->add($Ruc);

        $Razon = new Text("Razon");
        $Razon->setLabel("Razon Social");
        $Razon->setFilters(array('striptags', 'string'));
        $Razon->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar la Razon Social del Contribuyente'
              ))
        ));
        $this->add($Razon);

        $NombreComercial = new Text("NombreComercial");
        $NombreComercial->setLabel("Nombre Comercial");
        $NombreComercial->setFilters(array('striptags', 'string'));
        $NombreComercial->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar el Nombre Comercial del Contribuyente'
              ))
        ));
        $this->add($NombreComercial);

        $DirMatriz = new Text("DirMatriz");
        $DirMatriz->setLabel("Direccion Matriz");
        $DirMatriz->setFilters(array('striptags', 'string'));
        $DirMatriz->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar el Direccion Comercial de la Matriz del Contribuyente'
              ))
        ));
        $this->add($DirMatriz);

        $DirEmisor = new Text("DirEmisor");
        $DirEmisor->setLabel("Direccion Establecimiento");
        $DirEmisor->setFilters(array('striptags', 'string'));
        $DirEmisor->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar el Direccion Comercial de la Matriz del Contribuyente'
              ))
        ));
        $this->add($DirEmisor);

        $CodEmisor = new Numeric("CodEmisor");
        $CodEmisor->setLabel("Establecimiento");
        $CodEmisor->setFilters(array('int'));
        $CodEmisor->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar el Codigo Establecimiento del Contribuyente'
              ))
        ));
        $this->add($CodEmisor);

        $Punto = new Numeric("Punto");
        $Punto->setLabel("Punto Emision");
        $Punto->setFilters(array('int'));
        $Punto->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar el Numero del punto de emision del Contribuyente'
              )),
           new StringLength(
              [
              'max' => 3,
              'min' => 3,
              'messageMaximum' => "La longitud del campo son 3 caracteres",
              'messageMinimum' => "La longitud del campo son 3 caracteres"
              ])
        ));
        $this->add($Punto);

        $Resolucion = new Numeric("Resolucion");
        $Resolucion->setLabel("Resolucion");
        $Resolucion->setFilters(array('int'));
        $Resolucion->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar el Numero de Resolucion del Contribuyente'
              ))
        ));
        $this->add($Resolucion);

        $LlevaContabilidad = new Select("LlevaContabilidad", array("SI" => "SI", "NO" => "NO"));
        $LlevaContabilidad->setLabel("Lleva Contabilidad");
        $LlevaContabilidad->addValidators(array(
           new PresenceOf(array(
              'message' => 'Debe ingresar -SI- o -NO- para la contabilidad del Contribuyente'
              ))
        ));
        $this->add($LlevaContabilidad);
    }

}
