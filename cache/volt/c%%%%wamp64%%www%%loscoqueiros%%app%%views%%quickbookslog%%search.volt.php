<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous"><?= $this->tag->linkTo(['quickbookslog/index', 'Go Back']) ?></li>
            <li class="next"><?= $this->tag->linkTo(['quickbookslog/new', 'Create ']) ?></li>
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
                <th>Quickbooks Of Log</th>
            <th>Quickbooks Of Ticket</th>
            <th>Batch</th>
            <th>Msg</th>
            <th>Log Of Datetime</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (isset($page->items)) { ?>
        <?php foreach ($page->items as $quickbooks_log) { ?>
            <tr>
                <td><?= $quickbooks_log->quickbooks_log_id ?></td>
            <td><?= $quickbooks_log->quickbooks_ticket_id ?></td>
            <td><?= $quickbooks_log->batch ?></td>
            <td><?= $quickbooks_log->msg ?></td>
            <td><?= $quickbooks_log->log_datetime ?></td>

                <td><?= $this->tag->linkTo(['quickbooks_log/edit/' . $quickbooks_log->quickbooks_log_id, 'Edit']) ?></td>
                <td><?= $this->tag->linkTo(['quickbooks_log/delete/' . $quickbooks_log->quickbooks_log_id, 'Delete']) ?></td>
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
                <li><?= $this->tag->linkTo(['quickbookslog/search', 'First']) ?></li>
                <li><?= $this->tag->linkTo(['quickbookslog/search?page=' . $page->before, 'Previous']) ?></li>
                <li><?= $this->tag->linkTo(['quickbookslog/search?page=' . $page->next, 'Next']) ?></li>
                <li><?= $this->tag->linkTo(['quickbookslog/search?page=' . $page->last, 'Last']) ?></li>
            </ul>
        </nav>
    </div>
</div>
