<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class AppliedtosyncController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for appliedtosync
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Appliedtosync', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "Id";

        $appliedtosync = Appliedtosync::find($parameters);
        if (count($appliedtosync) == 0) {
            $this->flash->notice("The search did not find any appliedtosync");

            $this->dispatcher->forward([
                "controller" => "appliedtosync",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $appliedtosync,
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
     * Edits a appliedtosync
     *
     * @param string $Id
     */
    public function editAction($Id)
    {
        if (!$this->request->isPost()) {

            $appliedtosync = Appliedtosync::findFirstById($Id);
            if (!$appliedtosync) {
                $this->flash->error("appliedtosync was not found");

                $this->dispatcher->forward([
                    'controller' => "appliedtosync",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->Id = $appliedtosync->Id;

            $this->tag->setDefault("Id", $appliedtosync->Id);
            $this->tag->setDefault("datecreated", $appliedtosync->datecreated);
            $this->tag->setDefault("user", $appliedtosync->user);
            $this->tag->setDefault("billDesde", $appliedtosync->billDesde);
            $this->tag->setDefault("billHasta", $appliedtosync->billHasta);
            $this->tag->setDefault("invoiceDesde", $appliedtosync->invoiceDesde);
            $this->tag->setDefault("invoiceHasta", $appliedtosync->invoiceHasta);
            $this->tag->setDefault("billCreditDesde", $appliedtosync->billCreditDesde);
            $this->tag->setDefault("billCreditHasta", $appliedtosync->billCreditHasta);
            $this->tag->setDefault("creditMemoDesde", $appliedtosync->creditMemoDesde);
            $this->tag->setDefault("creditMemoHasta", $appliedtosync->creditMemoHasta);
            $this->tag->setDefault("productionDesde", $appliedtosync->productionDesde);
            $this->tag->setDefault("productionHasta", $appliedtosync->productionHasta);
            $this->tag->setDefault("retencionDesde", $appliedtosync->retencionDesde);
            $this->tag->setDefault("retencionHasta", $appliedtosync->retencionHasta);
            $this->tag->setDefault("otrosDesde", $appliedtosync->otrosDesde);
            $this->tag->setDefault("otrosHasta", $appliedtosync->otrosHasta);
            
        }
    }

    /**
     * Creates a new appliedtosync
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "appliedtosync",
                'action' => 'index'
            ]);

            return;
        }

        $appliedtosync = new Appliedtosync();
        $appliedtosync->Datecreated = $this->request->getPost("datecreated");
        $appliedtosync->User = $this->request->getPost("user");
        $appliedtosync->Billdesde = $this->request->getPost("billDesde");
        $appliedtosync->Billhasta = $this->request->getPost("billHasta");
        $appliedtosync->Invoicedesde = $this->request->getPost("invoiceDesde");
        $appliedtosync->Invoicehasta = $this->request->getPost("invoiceHasta");
        $appliedtosync->Billcreditdesde = $this->request->getPost("billCreditDesde");
        $appliedtosync->Billcredithasta = $this->request->getPost("billCreditHasta");
        $appliedtosync->Creditmemodesde = $this->request->getPost("creditMemoDesde");
        $appliedtosync->Creditmemohasta = $this->request->getPost("creditMemoHasta");
        $appliedtosync->Productiondesde = $this->request->getPost("productionDesde");
        $appliedtosync->Productionhasta = $this->request->getPost("productionHasta");
        $appliedtosync->Retenciondesde = $this->request->getPost("retencionDesde");
        $appliedtosync->Retencionhasta = $this->request->getPost("retencionHasta");
        $appliedtosync->Otrosdesde = $this->request->getPost("otrosDesde");
        $appliedtosync->Otroshasta = $this->request->getPost("otrosHasta");
        

        if (!$appliedtosync->save()) {
            foreach ($appliedtosync->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "appliedtosync",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("appliedtosync was created successfully");

        $this->dispatcher->forward([
            'controller' => "appliedtosync",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a appliedtosync edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "appliedtosync",
                'action' => 'index'
            ]);

            return;
        }

        $Id = $this->request->getPost("Id");
        $appliedtosync = Appliedtosync::findFirstById($Id);

        if (!$appliedtosync) {
            $this->flash->error("appliedtosync does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "appliedtosync",
                'action' => 'index'
            ]);

            return;
        }

        $appliedtosync->Datecreated = $this->request->getPost("datecreated");
        $appliedtosync->User = $this->request->getPost("user");
        $appliedtosync->Billdesde = $this->request->getPost("billDesde");
        $appliedtosync->Billhasta = $this->request->getPost("billHasta");
        $appliedtosync->Invoicedesde = $this->request->getPost("invoiceDesde");
        $appliedtosync->Invoicehasta = $this->request->getPost("invoiceHasta");
        $appliedtosync->Billcreditdesde = $this->request->getPost("billCreditDesde");
        $appliedtosync->Billcredithasta = $this->request->getPost("billCreditHasta");
        $appliedtosync->Creditmemodesde = $this->request->getPost("creditMemoDesde");
        $appliedtosync->Creditmemohasta = $this->request->getPost("creditMemoHasta");
        $appliedtosync->Productiondesde = $this->request->getPost("productionDesde");
        $appliedtosync->Productionhasta = $this->request->getPost("productionHasta");
        $appliedtosync->Retenciondesde = $this->request->getPost("retencionDesde");
        $appliedtosync->Retencionhasta = $this->request->getPost("retencionHasta");
        $appliedtosync->Otrosdesde = $this->request->getPost("otrosDesde");
        $appliedtosync->Otroshasta = $this->request->getPost("otrosHasta");
        

        if (!$appliedtosync->save()) {

            foreach ($appliedtosync->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "appliedtosync",
                'action' => 'edit',
                'params' => [$appliedtosync->Id]
            ]);

            return;
        }

        $this->flash->success("appliedtosync was updated successfully");

        $this->dispatcher->forward([
            'controller' => "appliedtosync",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a appliedtosync
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $appliedtosync = Appliedtosync::findFirstById($Id);
        if (!$appliedtosync) {
            $this->flash->error("appliedtosync was not found");

            $this->dispatcher->forward([
                'controller' => "appliedtosync",
                'action' => 'index'
            ]);

            return;
        }

        if (!$appliedtosync->delete()) {

            foreach ($appliedtosync->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "appliedtosync",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("appliedtosync was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "appliedtosync",
            'action' => "index"
        ]);
    }

}
