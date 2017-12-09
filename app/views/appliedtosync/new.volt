<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("appliedtosync", "Go Back") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Create appliedtosync
    </h1>
</div>

{{ content() }}

{{ form("appliedtosync/create", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}

<div class="form-group">
    <label for="fieldDatecreated" class="col-sm-2 control-label">Datecreated</label>
    <div class="col-sm-10">
        {{ text_field("datecreated", "size" : 30, "class" : "form-control", "id" : "fieldDatecreated") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldUser" class="col-sm-2 control-label">User</label>
    <div class="col-sm-10">
        {{ text_field("user", "size" : 30, "class" : "form-control", "id" : "fieldUser") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldBilldesde" class="col-sm-2 control-label">BillDesde</label>
    <div class="col-sm-10">
        {{ text_field("billDesde", "size" : 30, "class" : "form-control", "id" : "fieldBilldesde") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldBillhasta" class="col-sm-2 control-label">BillHasta</label>
    <div class="col-sm-10">
        {{ text_field("billHasta", "size" : 30, "class" : "form-control", "id" : "fieldBillhasta") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldInvoicedesde" class="col-sm-2 control-label">InvoiceDesde</label>
    <div class="col-sm-10">
        {{ text_field("invoiceDesde", "size" : 30, "class" : "form-control", "id" : "fieldInvoicedesde") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldInvoicehasta" class="col-sm-2 control-label">InvoiceHasta</label>
    <div class="col-sm-10">
        {{ text_field("invoiceHasta", "size" : 30, "class" : "form-control", "id" : "fieldInvoicehasta") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldBillcreditdesde" class="col-sm-2 control-label">BillCreditDesde</label>
    <div class="col-sm-10">
        {{ text_field("billCreditDesde", "size" : 30, "class" : "form-control", "id" : "fieldBillcreditdesde") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldBillcredithasta" class="col-sm-2 control-label">BillCreditHasta</label>
    <div class="col-sm-10">
        {{ text_field("billCreditHasta", "size" : 30, "class" : "form-control", "id" : "fieldBillcredithasta") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldCreditmemodesde" class="col-sm-2 control-label">CreditMemoDesde</label>
    <div class="col-sm-10">
        {{ text_field("creditMemoDesde", "size" : 30, "class" : "form-control", "id" : "fieldCreditmemodesde") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldCreditmemohasta" class="col-sm-2 control-label">CreditMemoHasta</label>
    <div class="col-sm-10">
        {{ text_field("creditMemoHasta", "size" : 30, "class" : "form-control", "id" : "fieldCreditmemohasta") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldProductiondesde" class="col-sm-2 control-label">ProductionDesde</label>
    <div class="col-sm-10">
        {{ text_field("productionDesde", "size" : 30, "class" : "form-control", "id" : "fieldProductiondesde") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldProductionhasta" class="col-sm-2 control-label">ProductionHasta</label>
    <div class="col-sm-10">
        {{ text_field("productionHasta", "size" : 30, "class" : "form-control", "id" : "fieldProductionhasta") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldRetenciondesde" class="col-sm-2 control-label">RetencionDesde</label>
    <div class="col-sm-10">
        {{ text_field("retencionDesde", "size" : 30, "class" : "form-control", "id" : "fieldRetenciondesde") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldRetencionhasta" class="col-sm-2 control-label">RetencionHasta</label>
    <div class="col-sm-10">
        {{ text_field("retencionHasta", "size" : 30, "class" : "form-control", "id" : "fieldRetencionhasta") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldOtrosdesde" class="col-sm-2 control-label">OtrosDesde</label>
    <div class="col-sm-10">
        {{ text_field("otrosDesde", "size" : 30, "class" : "form-control", "id" : "fieldOtrosdesde") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldOtroshasta" class="col-sm-2 control-label">OtrosHasta</label>
    <div class="col-sm-10">
        {{ text_field("otrosHasta", "size" : 30, "class" : "form-control", "id" : "fieldOtroshasta") }}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Save', 'class': 'btn btn-default') }}
    </div>
</div>

</form>
