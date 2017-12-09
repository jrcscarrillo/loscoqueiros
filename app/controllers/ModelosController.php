<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ModelosController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Modelos');
        parent::initialize();
    }

    public function indexAction() {
        $this->session->conditions = null;
        $this->view->form = new ModelosForm;
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "modelos",
                    "action"     => "index",
                ]
            );
        }
        $id = $this->request->getPost("id", "int");
        $modelo = Modelos::findFirstById($id);
        if (!$modelo) {
            $this->flash->error("Model does not exist");

            return $this->dispatcher->forward(
                [
                    "controller" => "modelos",
                    "action"     => "index",
                ]
            );
        }

        $form = new ModelosForm;

        $data = $this->request->getPost();
        if (!$form->isValid($data, $modelo)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "modelos",
                    "action"     => "index",
                ]
            );
        }

        if ($modelo->save() == false) {
            foreach ($modelo->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "modelos",
                    "action"     => "edit",
                ]
            );
        }

        $form->clear();

        $this->flash->success("Model was updated successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "modelos",
                "action"     => "index",
            ]
        );
    }

    public function newAction() {
        $this->view->form = new ModelosForm;
    }

    public function searchAction() {
        $numberPage = 1;

        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Modelos", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $misCodigos = Modelos::find($parameters);
        if (count($misCodigos) == 0) {
            $this->flash->notice("The search did not find any description model in our database");
            return $this->dispatcher->forward(
                  [
                     "controller" => "modelos",
                     "action" => "index",
                  ]
            );
        }

        $paginator = new Paginator(array(
           "data" => $misCodigos,
           "limit" => 10,
           "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
        $this->view->elementos = $misCodigos;
    }

    public function editAction($id) {
        if (!$this->request->isPost()) {
            $codigo = Modelos::findFirstById($id);
            if (!$codigo) {
                $this->flash->error("This model was not found");
                return $this->dispatcher->forward(
                      [
                         "controller" => "modelos",
                         "action" => "index",
                      ]
                );
            }
            $this->view->form = new ModelosForm($codigo, array('edit' => true));
        }
    }

    public function deleteAction($id) {

        $codigo = Modelos::findFirstById($id);
        if (!$codigo) {
            $this->flash->error("This model was not found");

            return $this->dispatcher->forward(
                  [
                     "controller" => "modelos",
                     "action" => "index",
                  ]
            );
        }

        if (!$codigo->delete()) {
            foreach ($codigo->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                  [
                     "controller" => "modelos",
                     "action" => "search",
                  ]
            );
        }

        $this->flash->success("This model was deleted from our database");

        return $this->dispatcher->forward(
              [
                 "controller" => "modelos",
                 "action" => "search",
              ]
        );
    }
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "modelos",
                    "action"     => "index",
                ]
            );
        }

        $form = new ModelosForm;
        $modelo = new Modelos();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $modelo)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "modelos",
                    "action"     => "new",
                ]
            );
        }
        
        if ($modelo->save() == false) {
            foreach ($modelo->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "modelos",
                    "action"     => "new",
                ]
            );
        }

        $form->clear();

        $this->flash->success("A new Model was added to our database successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "modelos",
                "action"     => "index",
            ]
        );
    }
}
