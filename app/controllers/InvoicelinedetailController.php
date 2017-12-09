<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class InvoicelinedetailController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for invoicelinedetail
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Invoicelinedetail', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "TxnLineID";

        $invoicelinedetail = Invoicelinedetail::find($parameters);
        if (count($invoicelinedetail) == 0) {
            $this->flash->notice("The search did not find any invoicelinedetail");

            $this->dispatcher->forward([
                "controller" => "invoicelinedetail",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $invoicelinedetail,
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
     * Edits a invoicelinedetail
     *
     * @param string $TxnLineID
     */
    public function editAction($TxnLineID)
    {
        if (!$this->request->isPost()) {

            $invoicelinedetail = Invoicelinedetail::findFirstByTxnLineID($TxnLineID);
            if (!$invoicelinedetail) {
                $this->flash->error("invoicelinedetail was not found");

                $this->dispatcher->forward([
                    'controller' => "invoicelinedetail",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->TxnLineID = $invoicelinedetail->getTxnlineid();

            $this->tag->setDefault("TxnLineID", $invoicelinedetail->getTxnlineid());
            $this->tag->setDefault("ItemRef_ListID", $invoicelinedetail->getItemrefListid());
            $this->tag->setDefault("ItemRef_FullName", $invoicelinedetail->getItemrefFullname());
            $this->tag->setDefault("Description", $invoicelinedetail->getDescription());
            $this->tag->setDefault("Quantity", $invoicelinedetail->getQuantity());
            $this->tag->setDefault("UnitOfMeasure", $invoicelinedetail->getUnitofmeasure());
            $this->tag->setDefault("OverrideUOMSetRef_ListID", $invoicelinedetail->getOverrideuomsetrefListid());
            $this->tag->setDefault("OverrideUOMSetRef_FullName", $invoicelinedetail->getOverrideuomsetrefFullname());
            $this->tag->setDefault("Rate", $invoicelinedetail->getRate());
            $this->tag->setDefault("RatePercent", $invoicelinedetail->getRatepercent());
            $this->tag->setDefault("ClassRef_ListID", $invoicelinedetail->getClassrefListid());
            $this->tag->setDefault("ClassRef_FullName", $invoicelinedetail->getClassrefFullname());
            $this->tag->setDefault("Amount", $invoicelinedetail->getAmount());
            $this->tag->setDefault("InventorySiteRef_ListID", $invoicelinedetail->getInventorysiterefListid());
            $this->tag->setDefault("InventorySiteRef_FullName", $invoicelinedetail->getInventorysiterefFullname());
            $this->tag->setDefault("InventorySiteLocationRef_ListID", $invoicelinedetail->getInventorysitelocationrefListid());
            $this->tag->setDefault("InventorySiteLocationRef_FullName", $invoicelinedetail->getInventorysitelocationrefFullname());
            $this->tag->setDefault("SerialNumber", $invoicelinedetail->getSerialnumber());
            $this->tag->setDefault("LotNumber", $invoicelinedetail->getLotnumber());
            $this->tag->setDefault("ServiceDate", $invoicelinedetail->getServicedate());
            $this->tag->setDefault("SalesTaxCodeRef_ListID", $invoicelinedetail->getSalestaxcoderefListid());
            $this->tag->setDefault("SalesTaxCodeRef_FullName", $invoicelinedetail->getSalestaxcoderefFullname());
            $this->tag->setDefault("Other1", $invoicelinedetail->getOther1());
            $this->tag->setDefault("Other2", $invoicelinedetail->getOther2());
            $this->tag->setDefault("LinkedTxnID", $invoicelinedetail->getLinkedtxnid());
            $this->tag->setDefault("LinkedTxnLineID", $invoicelinedetail->getLinkedtxnlineid());
            $this->tag->setDefault("CustomField1", $invoicelinedetail->getCustomfield1());
            $this->tag->setDefault("CustomField2", $invoicelinedetail->getCustomfield2());
            $this->tag->setDefault("CustomField3", $invoicelinedetail->getCustomfield3());
            $this->tag->setDefault("CustomField4", $invoicelinedetail->getCustomfield4());
            $this->tag->setDefault("CustomField5", $invoicelinedetail->getCustomfield5());
            $this->tag->setDefault("CustomField6", $invoicelinedetail->getCustomfield6());
            $this->tag->setDefault("CustomField7", $invoicelinedetail->getCustomfield7());
            $this->tag->setDefault("CustomField8", $invoicelinedetail->getCustomfield8());
            $this->tag->setDefault("CustomField9", $invoicelinedetail->getCustomfield9());
            $this->tag->setDefault("CustomField10", $invoicelinedetail->getCustomfield10());
            $this->tag->setDefault("CustomField11", $invoicelinedetail->getCustomfield11());
            $this->tag->setDefault("CustomField12", $invoicelinedetail->getCustomfield12());
            $this->tag->setDefault("CustomField13", $invoicelinedetail->getCustomfield13());
            $this->tag->setDefault("CustomField14", $invoicelinedetail->getCustomfield14());
            $this->tag->setDefault("CustomField15", $invoicelinedetail->getCustomfield15());
            $this->tag->setDefault("IDKEY", $invoicelinedetail->getIdkey());
            $this->tag->setDefault("GroupIDKEY", $invoicelinedetail->getGroupidkey());
            
        }
    }

    /**
     * Creates a new invoicelinedetail
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "invoicelinedetail",
                'action' => 'index'
            ]);

            return;
        }

        $invoicelinedetail = new Invoicelinedetail();
        $invoicelinedetail->setTxnlineid($this->request->getPost("TxnLineID"));
        $invoicelinedetail->setItemrefListid($this->request->getPost("ItemRef_ListID"));
        $invoicelinedetail->setItemrefFullname($this->request->getPost("ItemRef_FullName"));
        $invoicelinedetail->setDescription($this->request->getPost("Description"));
        $invoicelinedetail->setQuantity($this->request->getPost("Quantity"));
        $invoicelinedetail->setUnitofmeasure($this->request->getPost("UnitOfMeasure"));
        $invoicelinedetail->setOverrideuomsetrefListid($this->request->getPost("OverrideUOMSetRef_ListID"));
        $invoicelinedetail->setOverrideuomsetrefFullname($this->request->getPost("OverrideUOMSetRef_FullName"));
        $invoicelinedetail->setRate($this->request->getPost("Rate"));
        $invoicelinedetail->setRatepercent($this->request->getPost("RatePercent"));
        $invoicelinedetail->setClassrefListid($this->request->getPost("ClassRef_ListID"));
        $invoicelinedetail->setClassrefFullname($this->request->getPost("ClassRef_FullName"));
        $invoicelinedetail->setAmount($this->request->getPost("Amount"));
        $invoicelinedetail->setInventorysiterefListid($this->request->getPost("InventorySiteRef_ListID"));
        $invoicelinedetail->setInventorysiterefFullname($this->request->getPost("InventorySiteRef_FullName"));
        $invoicelinedetail->setInventorysitelocationrefListid($this->request->getPost("InventorySiteLocationRef_ListID"));
        $invoicelinedetail->setInventorysitelocationrefFullname($this->request->getPost("InventorySiteLocationRef_FullName"));
        $invoicelinedetail->setSerialnumber($this->request->getPost("SerialNumber"));
        $invoicelinedetail->setLotnumber($this->request->getPost("LotNumber"));
        $invoicelinedetail->setServicedate($this->request->getPost("ServiceDate"));
        $invoicelinedetail->setSalestaxcoderefListid($this->request->getPost("SalesTaxCodeRef_ListID"));
        $invoicelinedetail->setSalestaxcoderefFullname($this->request->getPost("SalesTaxCodeRef_FullName"));
        $invoicelinedetail->setOther1($this->request->getPost("Other1"));
        $invoicelinedetail->setOther2($this->request->getPost("Other2"));
        $invoicelinedetail->setLinkedtxnid($this->request->getPost("LinkedTxnID"));
        $invoicelinedetail->setLinkedtxnlineid($this->request->getPost("LinkedTxnLineID"));
        $invoicelinedetail->setCustomfield1($this->request->getPost("CustomField1"));
        $invoicelinedetail->setCustomfield2($this->request->getPost("CustomField2"));
        $invoicelinedetail->setCustomfield3($this->request->getPost("CustomField3"));
        $invoicelinedetail->setCustomfield4($this->request->getPost("CustomField4"));
        $invoicelinedetail->setCustomfield5($this->request->getPost("CustomField5"));
        $invoicelinedetail->setCustomfield6($this->request->getPost("CustomField6"));
        $invoicelinedetail->setCustomfield7($this->request->getPost("CustomField7"));
        $invoicelinedetail->setCustomfield8($this->request->getPost("CustomField8"));
        $invoicelinedetail->setCustomfield9($this->request->getPost("CustomField9"));
        $invoicelinedetail->setCustomfield10($this->request->getPost("CustomField10"));
        $invoicelinedetail->setCustomfield11($this->request->getPost("CustomField11"));
        $invoicelinedetail->setCustomfield12($this->request->getPost("CustomField12"));
        $invoicelinedetail->setCustomfield13($this->request->getPost("CustomField13"));
        $invoicelinedetail->setCustomfield14($this->request->getPost("CustomField14"));
        $invoicelinedetail->setCustomfield15($this->request->getPost("CustomField15"));
        $invoicelinedetail->setIdkey($this->request->getPost("IDKEY"));
        $invoicelinedetail->setGroupidkey($this->request->getPost("GroupIDKEY"));
        

        if (!$invoicelinedetail->save()) {
            foreach ($invoicelinedetail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "invoicelinedetail",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("invoicelinedetail was created successfully");

        $this->dispatcher->forward([
            'controller' => "invoicelinedetail",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a invoicelinedetail edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "invoicelinedetail",
                'action' => 'index'
            ]);

            return;
        }

        $TxnLineID = $this->request->getPost("TxnLineID");
        $invoicelinedetail = Invoicelinedetail::findFirstByTxnLineID($TxnLineID);

        if (!$invoicelinedetail) {
            $this->flash->error("invoicelinedetail does not exist " . $TxnLineID);

            $this->dispatcher->forward([
                'controller' => "invoicelinedetail",
                'action' => 'index'
            ]);

            return;
        }

        $invoicelinedetail->setTxnlineid($this->request->getPost("TxnLineID"));
        $invoicelinedetail->setItemrefListid($this->request->getPost("ItemRef_ListID"));
        $invoicelinedetail->setItemrefFullname($this->request->getPost("ItemRef_FullName"));
        $invoicelinedetail->setDescription($this->request->getPost("Description"));
        $invoicelinedetail->setQuantity($this->request->getPost("Quantity"));
        $invoicelinedetail->setUnitofmeasure($this->request->getPost("UnitOfMeasure"));
        $invoicelinedetail->setOverrideuomsetrefListid($this->request->getPost("OverrideUOMSetRef_ListID"));
        $invoicelinedetail->setOverrideuomsetrefFullname($this->request->getPost("OverrideUOMSetRef_FullName"));
        $invoicelinedetail->setRate($this->request->getPost("Rate"));
        $invoicelinedetail->setRatepercent($this->request->getPost("RatePercent"));
        $invoicelinedetail->setClassrefListid($this->request->getPost("ClassRef_ListID"));
        $invoicelinedetail->setClassrefFullname($this->request->getPost("ClassRef_FullName"));
        $invoicelinedetail->setAmount($this->request->getPost("Amount"));
        $invoicelinedetail->setInventorysiterefListid($this->request->getPost("InventorySiteRef_ListID"));
        $invoicelinedetail->setInventorysiterefFullname($this->request->getPost("InventorySiteRef_FullName"));
        $invoicelinedetail->setInventorysitelocationrefListid($this->request->getPost("InventorySiteLocationRef_ListID"));
        $invoicelinedetail->setInventorysitelocationrefFullname($this->request->getPost("InventorySiteLocationRef_FullName"));
        $invoicelinedetail->setSerialnumber($this->request->getPost("SerialNumber"));
        $invoicelinedetail->setLotnumber($this->request->getPost("LotNumber"));
        $invoicelinedetail->setServicedate($this->request->getPost("ServiceDate"));
        $invoicelinedetail->setSalestaxcoderefListid($this->request->getPost("SalesTaxCodeRef_ListID"));
        $invoicelinedetail->setSalestaxcoderefFullname($this->request->getPost("SalesTaxCodeRef_FullName"));
        $invoicelinedetail->setOther1($this->request->getPost("Other1"));
        $invoicelinedetail->setOther2($this->request->getPost("Other2"));
        $invoicelinedetail->setLinkedtxnid($this->request->getPost("LinkedTxnID"));
        $invoicelinedetail->setLinkedtxnlineid($this->request->getPost("LinkedTxnLineID"));
        $invoicelinedetail->setCustomfield1($this->request->getPost("CustomField1"));
        $invoicelinedetail->setCustomfield2($this->request->getPost("CustomField2"));
        $invoicelinedetail->setCustomfield3($this->request->getPost("CustomField3"));
        $invoicelinedetail->setCustomfield4($this->request->getPost("CustomField4"));
        $invoicelinedetail->setCustomfield5($this->request->getPost("CustomField5"));
        $invoicelinedetail->setCustomfield6($this->request->getPost("CustomField6"));
        $invoicelinedetail->setCustomfield7($this->request->getPost("CustomField7"));
        $invoicelinedetail->setCustomfield8($this->request->getPost("CustomField8"));
        $invoicelinedetail->setCustomfield9($this->request->getPost("CustomField9"));
        $invoicelinedetail->setCustomfield10($this->request->getPost("CustomField10"));
        $invoicelinedetail->setCustomfield11($this->request->getPost("CustomField11"));
        $invoicelinedetail->setCustomfield12($this->request->getPost("CustomField12"));
        $invoicelinedetail->setCustomfield13($this->request->getPost("CustomField13"));
        $invoicelinedetail->setCustomfield14($this->request->getPost("CustomField14"));
        $invoicelinedetail->setCustomfield15($this->request->getPost("CustomField15"));
        $invoicelinedetail->setIdkey($this->request->getPost("IDKEY"));
        $invoicelinedetail->setGroupidkey($this->request->getPost("GroupIDKEY"));
        

        if (!$invoicelinedetail->save()) {

            foreach ($invoicelinedetail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "invoicelinedetail",
                'action' => 'edit',
                'params' => [$invoicelinedetail->getTxnlineid()]
            ]);

            return;
        }

        $this->flash->success("invoicelinedetail was updated successfully");

        $this->dispatcher->forward([
            'controller' => "invoicelinedetail",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a invoicelinedetail
     *
     * @param string $TxnLineID
     */
    public function deleteAction($TxnLineID)
    {
        $invoicelinedetail = Invoicelinedetail::findFirstByTxnLineID($TxnLineID);
        if (!$invoicelinedetail) {
            $this->flash->error("invoicelinedetail was not found");

            $this->dispatcher->forward([
                'controller' => "invoicelinedetail",
                'action' => 'index'
            ]);

            return;
        }

        if (!$invoicelinedetail->delete()) {

            foreach ($invoicelinedetail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "invoicelinedetail",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("invoicelinedetail was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "invoicelinedetail",
            'action' => "index"
        ]);
    }

}
