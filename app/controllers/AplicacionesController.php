<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class AplicacionesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for aplicaciones
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Aplicaciones', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "appID";

        $aplicaciones = Aplicaciones::find($parameters);
        if (count($aplicaciones) == 0) {
            $this->flash->notice("The search did not find any aplicaciones");

            $this->dispatcher->forward([
                "controller" => "aplicaciones",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $aplicaciones,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a aplicacione
     *
     * @param string $appID
     */
    public function editAction($appID)
    {
        if (!$this->request->isPost()) {

            $aplicacione = Aplicaciones::findFirstByappID($appID);
            if (!$aplicacione) {
                $this->flash->error("aplicacione was not found");

                $this->dispatcher->forward([
                    'controller' => "aplicaciones",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->appID = $aplicacione->appID;

            $this->tag->setDefault("appID", $aplicacione->appID);
            $this->tag->setDefault("nombre", $aplicacione->nombre);
            $this->tag->setDefault("descripcion", $aplicacione->descripcion);
            $this->tag->setDefault("url", $aplicacione->url);
            $this->tag->setDefault("soporte", $aplicacione->soporte);
            $this->tag->setDefault("ultimoUsuario", $aplicacione->ultimoUsuario);
            $this->tag->setDefault("fechaCreacion", $aplicacione->fechaCreacion);
            
        }
    }

    /**
     * Creates a new aplicacione
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "aplicaciones",
                'action' => 'index'
            ]);

            return;
        }

        $aplicacione = new Aplicaciones();
        $aplicacione->Nombre = $this->request->getPost("nombre");
        $aplicacione->Descripcion = $this->request->getPost("descripcion");
        $aplicacione->Url = $this->request->getPost("url");
        $aplicacione->Soporte = $this->request->getPost("soporte");
        $aplicacione->Ultimousuario = $this->request->getPost("ultimoUsuario");
        $aplicacione->Fechacreacion = $this->request->getPost("fechaCreacion");
        

        if (!$aplicacione->save()) {
            foreach ($aplicacione->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "aplicaciones",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("aplicacione was created successfully");

        $this->dispatcher->forward([
            'controller' => "aplicaciones",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a aplicacione edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "aplicaciones",
                'action' => 'index'
            ]);

            return;
        }

        $appID = $this->request->getPost("appID");
        $aplicacione = Aplicaciones::findFirstByappID($appID);

        if (!$aplicacione) {
            $this->flash->error("aplicacione does not exist " . $appID);

            $this->dispatcher->forward([
                'controller' => "aplicaciones",
                'action' => 'index'
            ]);

            return;
        }

        $aplicacione->Nombre = $this->request->getPost("nombre");
        $aplicacione->Descripcion = $this->request->getPost("descripcion");
        $aplicacione->Url = $this->request->getPost("url");
        $aplicacione->Soporte = $this->request->getPost("soporte");
        $aplicacione->Ultimousuario = $this->request->getPost("ultimoUsuario");
        $aplicacione->Fechacreacion = $this->request->getPost("fechaCreacion");
        

        if (!$aplicacione->save()) {

            foreach ($aplicacione->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "aplicaciones",
                'action' => 'edit',
                'params' => [$aplicacione->appID]
            ]);

            return;
        }

        $this->flash->success("aplicacione was updated successfully");

        $this->dispatcher->forward([
            'controller' => "aplicaciones",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a aplicacione
     *
     * @param string $appID
     */
    public function deleteAction($appID)
    {
        $aplicacione = Aplicaciones::findFirstByappID($appID);
        if (!$aplicacione) {
            $this->flash->error("aplicacione was not found");

            $this->dispatcher->forward([
                'controller' => "aplicaciones",
                'action' => 'index'
            ]);

            return;
        }

        if (!$aplicacione->delete()) {

            foreach ($aplicacione->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "aplicaciones",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("aplicacione was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "aplicaciones",
            'action' => "index"
        ]);
    }

}
