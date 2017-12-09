<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class FacturasController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for facturas
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Facturas', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "TxnID";

        $facturas = Facturas::find($parameters);
        if (count($facturas) == 0) {
            $this->flash->notice("The search did not find any facturas");

            $this->dispatcher->forward([
                "controller" => "facturas",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $facturas,
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
     * Edits a factura
     *
     * @param string $TxnID
     */
    public function editAction($TxnID)
    {
        if (!$this->request->isPost()) {

            $factura = Facturas::findFirstByTxnID($TxnID);
            if (!$factura) {
                $this->flash->error("factura was not found");

                $this->dispatcher->forward([
                    'controller' => "facturas",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->TxnID = $factura->TxnID;

            $this->tag->setDefault("TxnID", $factura->TxnID);
            $this->tag->setDefault("Ambiente", $factura->Ambiente);
            $this->tag->setDefault("TipoEmision", $factura->TipoEmision);
            $this->tag->setDefault("Razon", $factura->Razon);
            $this->tag->setDefault("Comercial", $factura->Comercial);
            $this->tag->setDefault("Ruc", $factura->Ruc);
            $this->tag->setDefault("ClaveAcceso", $factura->ClaveAcceso);
            $this->tag->setDefault("Estab", $factura->Estab);
            $this->tag->setDefault("CodDoc", $factura->CodDoc);
            $this->tag->setDefault("Punto", $factura->Punto);
            $this->tag->setDefault("Sq", $factura->Sq);
            $this->tag->setDefault("DirMatriz", $factura->DirMatriz);
            $this->tag->setDefault("FechaEmision", $factura->FechaEmision);
            $this->tag->setDefault("DirEstab", $factura->DirEstab);
            $this->tag->setDefault("ContEsp", $factura->ContEsp);
            $this->tag->setDefault("LlevaContab", $factura->LlevaContab);
            $this->tag->setDefault("TipoId", $factura->TipoId);
            $this->tag->setDefault("NroId", $factura->NroId);
            $this->tag->setDefault("Guia", $factura->Guia);
            $this->tag->setDefault("RazonComprador", $factura->RazonComprador);
            $this->tag->setDefault("TotalImpto", $factura->TotalImpto);
            $this->tag->setDefault("Propina", $factura->Propina);
            $this->tag->setDefault("ImporteTotal", $factura->ImporteTotal);
            $this->tag->setDefault("Moneda", $factura->Moneda);
            $this->tag->setDefault("FechaAutoriza", $factura->FechaAutoriza);
            $this->tag->setDefault("NumeroAutoriza", $factura->NumeroAutoriza);
            $this->tag->setDefault("CodMsg", $factura->CodMsg);
            $this->tag->setDefault("Mensaje", $factura->Mensaje);
            $this->tag->setDefault("MsgAdicional", $factura->MsgAdicional);
            $this->tag->setDefault("TipoError", $factura->TipoError);
            $this->tag->setDefault("idComprobantes", $factura->idComprobantes);
            
        }
    }

    /**
     * Creates a new factura
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "facturas",
                'action' => 'index'
            ]);

            return;
        }

        $factura = new Facturas();
        $factura->Txnid = $this->request->getPost("TxnID");
        $factura->Ambiente = $this->request->getPost("Ambiente");
        $factura->Tipoemision = $this->request->getPost("TipoEmision");
        $factura->Razon = $this->request->getPost("Razon");
        $factura->Comercial = $this->request->getPost("Comercial");
        $factura->Ruc = $this->request->getPost("Ruc");
        $factura->Claveacceso = $this->request->getPost("ClaveAcceso");
        $factura->Estab = $this->request->getPost("Estab");
        $factura->Coddoc = $this->request->getPost("CodDoc");
        $factura->Punto = $this->request->getPost("Punto");
        $factura->Sq = $this->request->getPost("Sq");
        $factura->Dirmatriz = $this->request->getPost("DirMatriz");
        $factura->Fechaemision = $this->request->getPost("FechaEmision");
        $factura->Direstab = $this->request->getPost("DirEstab");
        $factura->Contesp = $this->request->getPost("ContEsp");
        $factura->Llevacontab = $this->request->getPost("LlevaContab");
        $factura->Tipoid = $this->request->getPost("TipoId");
        $factura->Nroid = $this->request->getPost("NroId");
        $factura->Guia = $this->request->getPost("Guia");
        $factura->Razoncomprador = $this->request->getPost("RazonComprador");
        $factura->Totalimpto = $this->request->getPost("TotalImpto");
        $factura->Propina = $this->request->getPost("Propina");
        $factura->Importetotal = $this->request->getPost("ImporteTotal");
        $factura->Moneda = $this->request->getPost("Moneda");
        $factura->Fechaautoriza = $this->request->getPost("FechaAutoriza");
        $factura->Numeroautoriza = $this->request->getPost("NumeroAutoriza");
        $factura->Codmsg = $this->request->getPost("CodMsg");
        $factura->Mensaje = $this->request->getPost("Mensaje");
        $factura->Msgadicional = $this->request->getPost("MsgAdicional");
        $factura->Tipoerror = $this->request->getPost("TipoError");
        $factura->Idcomprobantes = $this->request->getPost("idComprobantes");
        

        if (!$factura->save()) {
            foreach ($factura->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "facturas",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("factura was created successfully");

        $this->dispatcher->forward([
            'controller' => "facturas",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a factura edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "facturas",
                'action' => 'index'
            ]);

            return;
        }

        $TxnID = $this->request->getPost("TxnID");
        $factura = Facturas::findFirstByTxnID($TxnID);

        if (!$factura) {
            $this->flash->error("factura does not exist " . $TxnID);

            $this->dispatcher->forward([
                'controller' => "facturas",
                'action' => 'index'
            ]);

            return;
        }

        $factura->Txnid = $this->request->getPost("TxnID");
        $factura->Ambiente = $this->request->getPost("Ambiente");
        $factura->Tipoemision = $this->request->getPost("TipoEmision");
        $factura->Razon = $this->request->getPost("Razon");
        $factura->Comercial = $this->request->getPost("Comercial");
        $factura->Ruc = $this->request->getPost("Ruc");
        $factura->Claveacceso = $this->request->getPost("ClaveAcceso");
        $factura->Estab = $this->request->getPost("Estab");
        $factura->Coddoc = $this->request->getPost("CodDoc");
        $factura->Punto = $this->request->getPost("Punto");
        $factura->Sq = $this->request->getPost("Sq");
        $factura->Dirmatriz = $this->request->getPost("DirMatriz");
        $factura->Fechaemision = $this->request->getPost("FechaEmision");
        $factura->Direstab = $this->request->getPost("DirEstab");
        $factura->Contesp = $this->request->getPost("ContEsp");
        $factura->Llevacontab = $this->request->getPost("LlevaContab");
        $factura->Tipoid = $this->request->getPost("TipoId");
        $factura->Nroid = $this->request->getPost("NroId");
        $factura->Guia = $this->request->getPost("Guia");
        $factura->Razoncomprador = $this->request->getPost("RazonComprador");
        $factura->Totalimpto = $this->request->getPost("TotalImpto");
        $factura->Propina = $this->request->getPost("Propina");
        $factura->Importetotal = $this->request->getPost("ImporteTotal");
        $factura->Moneda = $this->request->getPost("Moneda");
        $factura->Fechaautoriza = $this->request->getPost("FechaAutoriza");
        $factura->Numeroautoriza = $this->request->getPost("NumeroAutoriza");
        $factura->Codmsg = $this->request->getPost("CodMsg");
        $factura->Mensaje = $this->request->getPost("Mensaje");
        $factura->Msgadicional = $this->request->getPost("MsgAdicional");
        $factura->Tipoerror = $this->request->getPost("TipoError");
        $factura->Idcomprobantes = $this->request->getPost("idComprobantes");
        

        if (!$factura->save()) {

            foreach ($factura->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "facturas",
                'action' => 'edit',
                'params' => [$factura->TxnID]
            ]);

            return;
        }

        $this->flash->success("factura was updated successfully");

        $this->dispatcher->forward([
            'controller' => "facturas",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a factura
     *
     * @param string $TxnID
     */
    public function deleteAction($TxnID)
    {
        $factura = Facturas::findFirstByTxnID($TxnID);
        if (!$factura) {
            $this->flash->error("factura was not found");

            $this->dispatcher->forward([
                'controller' => "facturas",
                'action' => 'index'
            ]);

            return;
        }

        if (!$factura->delete()) {

            foreach ($factura->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "facturas",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("factura was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "facturas",
            'action' => "index"
        ]);
    }

}
