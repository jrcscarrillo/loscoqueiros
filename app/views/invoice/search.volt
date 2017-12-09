{{ content() }}

<ul class="pager">
    <li class="previous pull-left">
        {{ link_to("invoice/index", "&larr; Go Back") }}
    </li>
</ul>

{% for miscodigos in page.items %}
    {% if loop.first %}
        <table class="table table-responsive table-bordered table-striped" align="center">
            <thead class="mdbcolor">
                <tr>
                    <th width="10%">Factura ID</th>
                    <th width="10%">Fecha Creada</th>
                    <th width="10%">Fecha Modificada</th>
                    <th>Secuencia</th>
                    <th>Transaccion</th>
                    <th>Cliente ID Lista</th>
                    <th width="20%">Cliente Nombres/Razon </th>
                    <th>Fecha Emision</th>
                    <th>Numero Factura</th>
                    <th>Direccion</th>
                    <th>Ciudad</th>
                    <th>Provincia</th>
                    <th>Codigo</th>
                    <th>Vendedor</th>
                    <th>Subtotal</th>
                    <th>%</th>
                    <th>Valor IVA</th>
                    <th>Total</th>
                    <th>Memo</th>
                    <th>Status</th>

                    <th></th>
                    <th></th>
                </tr>
            </thead>
        {% endif %}
        <tbody>
            <tr>
                <td>{{ miscodigos.getTxnid() }}</td>
                <td>{{ miscodigos.getTimecreated() }}</td>
                <td>{{ miscodigos.getTimemodified() }}</td>
                <td>{{ miscodigos.getEditsequence() }}</td>
                <td>{{ miscodigos.getTxnnumber() }}</td>
                <td>{{ miscodigos.getCustomerrefListid() }}</td>
                <td>{{ miscodigos.getCustomerrefFullname() }}</td>
                <td>{{ miscodigos.getTxndate() }}</td>
                <td>{{ miscodigos.getRefnumber() }}</td>
                <td>{{ miscodigos.getBilladdressAddr1() }}</td>
                <td>{{ miscodigos.getBilladdressCity() }}</td>
                <td>{{ miscodigos.getBilladdressState() }}</td>
                <td>{{ miscodigos.getBilladdressPostalcode() }}</td>
                <td>{{ miscodigos.getSalesreprefFullname() }}</td>
                <td>{{ miscodigos.getSubtotal() }}</td>
                <td>{{ miscodigos.getSalestaxpercentage() }}</td>
                <td>{{ miscodigos.getSalestaxtotal() }}</td>
                <td>{{ miscodigos.getAppliedamount() }}</td>
                <td>{{ miscodigos.getMemo() }}</td>
                <td>{{ miscodigos.getStatus() }}</td>
                <td width="2%">{{ link_to("invoice/firmar/" ~ miscodigos.getTxnid(), '<i class="glyphicon glyphicon-certificate"></i>', "class": "btn btn-default") }}</td>
                <td width="2%">{{ link_to("invoice/folder/" ~ miscodigos.Txnid, '<i class="glyphicon glyphicon-folder-open"></i>', "class": "btn btn-default") }}</td>
        </tbody>
        {% if loop.last %}
            <tbody>
                <tr>
                    <td colspan="7" align="right">
                        <div class="btn-group">
                            {{ link_to("invoice/search", '<i class="icon-fast-backward"></i> First', "class": "btn btn-default") }}
                            {{ link_to("invoice/search?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn btn-default") }}
                            {{ link_to("invoice/search?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn btn-default") }}
                            {{ link_to("invoice/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn btn-default") }}
                            <span class="help-inline">{{ page.current }}/{{ page.total_pages }}</span>
                        </div>
                    </td>
                </tr>
            <tbody>
        </table>
    {% endif %}
{% else %}
    No se han encontrado facturas sincronizadas desde el Quickbooks
{% endfor %}
