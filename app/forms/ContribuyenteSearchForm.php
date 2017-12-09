<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;

class ContribuyenteSearchForm extends Form {

    public function initialize($entity = null, $options = array()) {

        $Razon = new Text("Razon");
        $Razon->setLabel("Razon");
        $this->add($Razon);

        $NombreComercial = new Text("NombreComercial");
        $NombreComercial->setLabel("Nombre Comercial");
        $this->add($NombreComercial);

        $DirMatriz = new Text("DirMatriz");
        $DirMatriz->setLabel("Direccion Matriz");
        $this->add($DirMatriz);

        $DirEmisor = new Text("DirEmisor");
        $DirEmisor->setLabel("Direccion Establecimiento");
        $this->add($DirEmisor);

        $CodEmisor = new Numeric("CodEmisor");
        $CodEmisor->setLabel("Establecimiento");
        $this->add($CodEmisor);

        $Punto = new Numeric("Punto");
        $Punto->setLabel("Punto Emision");
        $this->add($Punto);

        $Resolucion = new Numeric("Resolucion");
        $Resolucion->setLabel("Resolucion");
        $this->add($Resolucion);

        $LlevaContabilidad = new Text("LlevaContabilidad");
        $LlevaContabilidad->setLabel("Lleva Contabilidad");
        $this->add($LlevaContabilidad);

        $Ambiente = new Numeric("Ambiente");
        $Ambiente->setLabel("Ambiente");
        $this->add($Ambiente);

        $Emision = new Numeric("Emision");
        $Emision->setLabel("Emision");
        $this->add($Emision);

        $Contingencia = new Numeric("Contingencia");
        $Contingencia->setLabel("Contingencia");
        $this->add($Contingencia);
    }

}
