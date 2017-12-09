<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous"><?= $this->tag->linkTo(['appliedtosync/index', 'Go Back']) ?></li>
            <li class="next"><?= $this->tag->linkTo(['appliedtosync/new', 'Create ']) ?></li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Search result</h1>
</div>

<?= $this->getContent() ?>

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
        <?php if (isset($page->items)) { ?>
        <?php foreach ($page->items as $appliedtosync) { ?>
            <tr>
                <td><?= $appliedtosync->Id ?></td>
            <td><?= $appliedtosync->datecreated ?></td>
            <td><?= $appliedtosync->user ?></td>
            <td><?= $appliedtosync->billDesde ?></td>
            <td><?= $appliedtosync->billHasta ?></td>
            <td><?= $appliedtosync->invoiceDesde ?></td>
            <td><?= $appliedtosync->invoiceHasta ?></td>
            <td><?= $appliedtosync->billCreditDesde ?></td>
            <td><?= $appliedtosync->billCreditHasta ?></td>
            <td><?= $appliedtosync->creditMemoDesde ?></td>
            <td><?= $appliedtosync->creditMemoHasta ?></td>
            <td><?= $appliedtosync->productionDesde ?></td>
            <td><?= $appliedtosync->productionHasta ?></td>
            <td><?= $appliedtosync->retencionDesde ?></td>
            <td><?= $appliedtosync->retencionHasta ?></td>
            <td><?= $appliedtosync->otrosDesde ?></td>
            <td><?= $appliedtosync->otrosHasta ?></td>

                <td><?= $this->tag->linkTo(['appliedtosync/edit/' . $appliedtosync->Id, 'Edit']) ?></td>
                <td><?= $this->tag->linkTo(['appliedtosync/delete/' . $appliedtosync->Id, 'Delete']) ?></td>
            </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            <?= $page->current . '/' . $page->total_pages ?>
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li><?= $this->tag->linkTo(['appliedtosync/search', 'First']) ?></li>
                <li><?= $this->tag->linkTo(['appliedtosync/search?page=' . $page->before, 'Previous']) ?></li>
                <li><?= $this->tag->linkTo(['appliedtosync/search?page=' . $page->next, 'Next']) ?></li>
                <li><?= $this->tag->linkTo(['appliedtosync/search?page=' . $page->last, 'Last']) ?></li>
            </ul>
        </nav>
    </div>
</div>
