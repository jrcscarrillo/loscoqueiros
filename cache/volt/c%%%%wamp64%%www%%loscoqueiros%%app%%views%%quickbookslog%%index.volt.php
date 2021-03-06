<div class="page-header">
    <h1>
        Search quickbooks_log
    </h1>
    <p>
        <?= $this->tag->linkTo(['quickbookslog/new', 'Create quickbooks_log']) ?>
    </p>
</div>

<?= $this->getContent() ?>

<?= $this->tag->form(['quickbookslog/search', 'method' => 'post', 'autocomplete' => 'off', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <label for="fieldQuickbooksLogId" class="col-sm-2 control-label">Quickbooks Of Log</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['quickbooks_log_id', 'type' => 'numeric', 'class' => 'form-control', 'id' => 'fieldQuickbooksLogId']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldQuickbooksTicketId" class="col-sm-2 control-label">Quickbooks Of Ticket</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['quickbooks_ticket_id', 'type' => 'numeric', 'class' => 'form-control', 'id' => 'fieldQuickbooksTicketId']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldBatch" class="col-sm-2 control-label">Batch</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['batch', 'type' => 'numeric', 'class' => 'form-control', 'id' => 'fieldBatch']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldMsg" class="col-sm-2 control-label">Msg</label>
    <div class="col-sm-10">
        <?= $this->tag->textArea(['msg', 'cols' => '30', 'rows' => '4', 'class' => 'form-control', 'id' => 'fieldMsg']) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldLogDatetime" class="col-sm-2 control-label">Log Of Datetime</label>
    <div class="col-sm-10">
        <?= $this->tag->textField(['log_datetime', 'size' => 30, 'class' => 'form-control', 'id' => 'fieldLogDatetime']) ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?= $this->tag->submitButton(['Search', 'class' => 'btn btn-default']) ?>
    </div>
</div>

</form>
