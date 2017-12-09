<div class="page-header">
    <h1>
        Search appliedtosync
    </h1>
    <p>
        <?= $this->tag->linkTo(['appliedtosync/new', 'Create appliedtosync']) ?>
    </p>
</div>

<?= $this->getContent() ?>

<?= $this->tag->form(['appliedtosync/search', 'method' => 'post', 'autocomplete' => 'off', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <label for="fieldId" class="col-sm-2 control-label">Id</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['Id', 'type' => 'numeric', 'class' => 'form-control', 'id' => 'fieldId']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldDatecreated" class="col-sm-2 control-label">Datecreated</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['datecreated', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldDatecreated']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldUser" class="col-sm-2 control-label">User</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['user', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldUser']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldBilldesde" class="col-sm-2 control-label">BillDesde</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['billDesde', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldBilldesde']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldBillhasta" class="col-sm-2 control-label">BillHasta</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['billHasta', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldBillhasta']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldInvoicedesde" class="col-sm-2 control-label">InvoiceDesde</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['invoiceDesde', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldInvoicedesde']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldInvoicehasta" class="col-sm-2 control-label">InvoiceHasta</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['invoiceHasta', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldInvoicehasta']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldBillcreditdesde" class="col-sm-2 control-label">BillCreditDesde</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['billCreditDesde', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldBillcreditdesde']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldBillcredithasta" class="col-sm-2 control-label">BillCreditHasta</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['billCreditHasta', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldBillcredithasta']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldCreditmemodesde" class="col-sm-2 control-label">CreditMemoDesde</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['creditMemoDesde', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldCreditmemodesde']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldCreditmemohasta" class="col-sm-2 control-label">CreditMemoHasta</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['creditMemoHasta', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldCreditmemohasta']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldProductiondesde" class="col-sm-2 control-label">ProductionDesde</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['productionDesde', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldProductiondesde']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldProductionhasta" class="col-sm-2 control-label">ProductionHasta</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['productionHasta', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldProductionhasta']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldRetenciondesde" class="col-sm-2 control-label">RetencionDesde</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['retencionDesde', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldRetenciondesde']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldRetencionhasta" class="col-sm-2 control-label">RetencionHasta</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['retencionHasta', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldRetencionhasta']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldOtrosdesde" class="col-sm-2 control-label">OtrosDesde</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['otrosDesde', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldOtrosdesde']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldOtroshasta" class="col-sm-2 control-label">OtrosHasta</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['otrosHasta', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldOtroshasta']) ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?= $this->tag->submitButton(['Search', 'class' => 'btn btn-default']) ?>
    </div>
</div>

</form>
