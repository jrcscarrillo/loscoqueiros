<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;

class ModelosForm extends Form
{

    public function initialize($entity = null, $options = array())
    {

        if (!isset($options['edit'])) {
            $element = new Numeric("id");
            $this->add($element->setLabel("Model Number"));
        } else {
            $this->add(new Hidden("id"));
        }

        $modelname = new Text("modelName");
        $modelname->setLabel("Model Name");
        $modelname->setFilters(array('striptags', 'string'));
        $modelname->addValidators(array(
            new PresenceOf(array(
                'message' => 'A Model Name is required'
            ))
        ));
        $this->add($modelname);

        $actionname = new Text("actionName");
        $actionname->setLabel("Action Name");
        $actionname->setFilters(array('striptags', 'string'));
        $actionname->addValidators(array(
            new PresenceOf(array(
                'message' => 'Action Name is required'
            ))
        ));
        $this->add($actionname);

        $modelType = new Text("modelType");
        $modelType->setLabel("Model Type");
        $modelType->setFilters(array('striptags', 'string'));
        $modelType->addValidators(array(
            new PresenceOf(array(
                'message' => 'Model Type is required'
            ))
        ));
        $this->add($modelType);
        

        $modelDes = new Text("modelDes");
        $modelDes->setLabel("Model Description");
        $modelDes->setFilters(array('striptags', 'string'));
        $modelDes->addValidators(array(
            new PresenceOf(array(
                'message' => 'Model Description is required'
           ))
        ));
        $this->add($modelDes);
    }

}