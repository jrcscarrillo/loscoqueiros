<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("appliedtosync/index", "Go Back") }}</li>
            <li class="next">{{ link_to("appliedtosync/new", "Create ") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Search result</h1>
</div>

{{ content() }}

<div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
            <th>Datecreated</th>
            <th>User</th>
            <th>BillDesde</th>
            <th>BillHasta</th>
            <th>InvoiceDesde</th>
            <th>InvoiceHasta</th>
            <th>BillCreditDesde</th>
            <th>BillCreditHasta</th>
            <th>CreditMemoDesde</th>
            <th>CreditMemoHasta</th>
            <th>ProductionDesde</th>
            <th>ProductionHasta</th>
            <th>RetencionDesde</th>
            <th>RetencionHasta</th>
            <th>OtrosDesde</th>
            <th>OtrosHasta</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for appliedtosync in page.items %}
            <tr>
                <td>{{ appliedtosync.Id }}</td>
            <td>{{ appliedtosync.datecreated }}</td>
            <td>{{ appliedtosync.user }}</td>
            <td>{{ appliedtosync.billDesde }}</td>
            <td>{{ appliedtosync.billHasta }}</td>
            <td>{{ appliedtosync.invoiceDesde }}</td>
            <td>{{ appliedtosync.invoiceHasta }}</td>
            <td>{{ appliedtosync.billCreditDesde }}</td>
            <td>{{ appliedtosync.billCreditHasta }}</td>
            <td>{{ appliedtosync.creditMemoDesde }}</td>
            <td>{{ appliedtosync.creditMemoHasta }}</td>
            <td>{{ appliedtosync.productionDesde }}</td>
            <td>{{ appliedtosync.productionHasta }}</td>
            <td>{{ appliedtosync.retencionDesde }}</td>
            <td>{{ appliedtosync.retencionHasta }}</td>
            <td>{{ appliedtosync.otrosDesde }}</td>
            <td>{{ appliedtosync.otrosHasta }}</td>

                <td>{{ link_to("appliedtosync/edit/"~appliedtosync.Id, "Edit") }}</td>
                <td>{{ link_to("appliedtosync/delete/"~appliedtosync.Id, "Delete") }}</td>
            </tr>
        {% endfor %}
        {% endif %}
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            {{ page.current~"/"~page.total_pages }}
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li>{{ link_to("appliedtosync/search", "First") }}</li>
                <li>{{ link_to("appliedtosync/search?page="~page.before, "Previous") }}</li>
                <li>{{ link_to("appliedtosync/search?page="~page.next, "Next") }}</li>
                <li>{{ link_to("appliedtosync/search?page="~page.last, "Last") }}</li>
            </ul>
        </nav>
    </div>
</div>
