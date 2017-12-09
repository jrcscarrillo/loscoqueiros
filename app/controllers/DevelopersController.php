<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class DevelopersController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Developers');
        parent::initialize();
    }

    public function indexAction() {
        $this->session->conditions = null;
        $this->view->form = new DevelopersForm;
//        $usuario = $this->session->get('auth', 'id');
//        $this->tag->setDefault('userId', $usuario['id']);
    }

    /**
     * Saves current developer in screen
     *
     * @param string $id
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }
        $id = $this->request->getPost("id", "int");
        $developer = Developers::findFirstById($id);
        if (!$developer) {
            $this->flash->error("Developer does not exist");

            return $this->dispatcher->forward(
                [
                    "controller" => "developers",
                    "action"     => "index",
                ]
            );
        }

        $form = new DevelopersForm;

        $data = $this->request->getPost();
        if (!$form->isValid($data, $developer)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        if ($developer->save() == false) {
            foreach ($developer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "developers",
                    "action"     => "edit",
                ]
            );
        }

        $this->flash->success("Developer was updated successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "developers",
                "action"     => "search",
            ]
        );
    }

    public function newAction() {

        $this->view->form = new DevelopersForm();
    }

    public function searchAction() {
        $numberPage = 1;

        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Developers", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $misCodigos = Developers::find($parameters);
        if (count($misCodigos) == 0) {
            $this->flash->notice("The search did not find any developer in our database");
            return $this->dispatcher->forward(
                  [
                     "controller" => "developers",
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

    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "developers",
                    "action"     => "index",
                ]
            );
        }

        $form = new DevelopersForm;
        $developer = new Developers();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $developer)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "developers",
                    "action"     => "new",
                ]
            );
        }
        
        $developer->fullname = $data->fullname;
        $developer->address = $data->address;
        $developer->email = $data->email;
        $developer->telephone = $data->telephone;
        $developer->dateEnrolled = new Phalcon\Db\RawValue('now()');

        if ($developer->save() == false) {
            foreach ($developer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "developers",
                    "action"     => "new",
                ]
            );
        }

        $form->clear();

        $this->flash->success("A new Developer was added to our database successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "developers",
                "action"     => "index",
            ]
        );
    }
    /**
     * Edits a developer based on its id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $developer = Developers::findFirstById($id);
            if (!$developer) {
                $this->flash->error("Developer was not found");

                return $this->dispatcher->forward(
                    [
                        "controller" => "developers",
                        "action"     => "index",
                    ]
                );
            }

            $this->view->form = new DevelopersForm($developer, array('edit' => true));
        }
    }
    public function deleteAction($id) {

        $codigo = Developers::findFirstById($id);
        if (!$codigo) {
            $this->flash->error("This developer was not found");

            return $this->dispatcher->forward(
                  [
                     "controller" => "developers",
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
                     "controller" => "developers",
                     "action" => "search",
                  ]
            );
        }

        $this->flash->success("This developer was deleted from our database");

        return $this->dispatcher->forward(
              [
                 "controller" => "developers",
                 "action" => "search",
              ]
        );
    }
}
